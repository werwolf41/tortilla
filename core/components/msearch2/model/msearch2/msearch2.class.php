<?php

/**
 * The base class for mSearch2.
 *
 * @package msearch2
 */
class mSearch2 {
	/** @var modX $modx */
	public $modx;
	/** @var array $config */
	public $config = array();
	/** @var mSearch2ControllerRequest $request */
	protected $request;
	/** @var mse2FiltersHandler $filtersHandler */
	public $filtersHandler = null;
	/** @var array $phpMorphy */
	public $phpMorphy = array();
	/** @var array $methods */
	public $methods = array();
	/** @var array $filters */
	public $filters = array();
	/** @var array $aliases */
	public $aliases = array();
	/** @var integer Total number of filter operations */
	public $filter_operations = 0;
	/** @var string Current search query */
	public $query = '';
	/** @var pdoTools $pdoTools */
	public $pdoTools = null;
	/** @var array $fields */
	public $fields = array();


	/**
	 * mSearch2 constructor.
	 *
	 * @param modX $modx
	 * @param array $config
	 * @param pdoTools|null $pdoTools
	 */
	public function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('msearch2.core_path', $config, $this->modx->getOption('core_path') . 'components/msearch2/');
		$assetsPath = $this->modx->getOption('msearch2.assets_path', $config, $this->modx->getOption('assets_path') . 'components/msearch2/');
		$assetsUrl = $this->modx->getOption('msearch2.assets_url', $config, $this->modx->getOption('assets_url') . 'components/msearch2/');
		$actionUrl = $this->modx->getOption('msearch2.action_url', $config, $assetsUrl . 'action.php');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'jsPath' => $assetsPath . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'customPath' => $corePath . 'custom/',
			'dictsPath' => $corePath . 'phpmorphy/dicts/',

			'connectorUrl' => $connectorUrl,
			'actionUrl' => $actionUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'templatesPath' => $corePath . 'elements/templates/',
			'processorsPath' => $corePath . 'processors/',

			'cacheTime' => 1800,
			'min_word_length' => $this->modx->getOption('mse2_index_min_words_length', null, 3, true),
			'exact_match_bonus' => $this->modx->getOption('mse2_search_exact_match_bonus', null, 10, true),
			'like_match_bonus' => $this->modx->getOption('mse2_search_like_match_bonus', null, 3, true),
			'all_words_bonus' => $this->modx->getOption('mse2_search_all_words_bonus', null, 5, true),
			'old_search_algorithm' => $this->modx->getOption('mse2_old_search_algorithm', null, false),
			'introCutBefore' => 50,
			'introCutAfter' => 250,
			'filter_delimeter' => '|',
			'method_delimeter' => ':',
			'values_delimeter' => ',',
			'split_words' => $this->modx->getOption('mse2_search_split_words', null, '#\s#', true),
			'split_all' => $this->modx->getOption('mse2_index_split_words', null, '#\s|[,.:;!?"\'(){}\\/\#]#', true),
			'index_all' => $this->modx->getOption('mse2_index_all', null, false),
			'suggestionsRadio' => array(),

			'autocomplete' => 0,
			'queryVar' => 'query',
			'minQuery' => 3,
			'onlyIndex' => false,
			'config_file' => $assetsPath . 'js/web/config.js',
		), $config);

		if (!is_array($this->config['suggestionsRadio'])) {
			$this->config['suggestionsRadio'] = array_map('trim', explode(',', $this->config['suggestionsRadio']));
		}

		mb_internal_encoding($this->modx->getOption('modx_charset', null, 'UTF-8'));

		$this->modx->addPackage('msearch2', $this->config['modelPath']);
		$this->modx->lexicon->load('msearch2:default');
		$this->pdoTools = $this->modx->getService('pdoTools');

		$this->getWorkFields();
	}


	/**
	 * @param string $query
	 *
	 * @return string
	 */
	public function getQuery($query = '') {
		$query = htmlspecialchars_decode($query, ENT_QUOTES);
		$query = strip_tags($query);
		$query = preg_replace('#[^\w\s]#u', '', $query);
		$query = htmlspecialchars($query);

		return trim($query);
	}


	/**
	 * Prepares working fields of resources for search
	 *
	 * @param array $config
	 *
	 * @return array
	 */
	public function getWorkFields($config = array()) {
		$config = array_merge($this->config, $config);
		$setting = $this->modx->getOption('mse2_index_fields', null, 'content:3,description:2,introtext:2,pagetitle:3,longtitle:3', true);
		$fields = $default = array();

		// Preparing default fields for work
		$tmp = array_map('trim', explode(',', strtolower($setting)));
		foreach ($tmp as $v) {
			$tmp2 = explode(':', $v);
			$default[$tmp2[0]] = !empty($tmp2[1])
				? $tmp2[1]
				: 1;
		}
		if ($this->modx->getOption('mse2_index_comments', null, true)) {
			$default['comment'] = $this->modx->getOption('mse2_index_comments', null, 1, true);
		}

		if (!empty($config['fields'])) {
			$tmp = array_map('trim', explode(',', strtolower($config['fields'])));
			foreach ($tmp as $v) {
				$tmp2 = explode(':', $v);
				$fields[$tmp2[0]] = !empty($tmp2[1])
					? $tmp2[1]
					: (isset($default[$tmp[0]])
						? $default[$tmp[0]]
						: 1
					);
			}
		}
		else {
			$fields = $default;
		}

		$this->fields = $fields;

		return $fields;
	}


	/**
	 * Initializes mSearch2 into different contexts.
	 *
	 * @param string $ctx The context to load. Defaults to web.
	 * @param array $scriptProperties
	 *
	 * @return boolean
	 */
	public function initialize($ctx = 'web', $scriptProperties = array()) {
		switch ($ctx) {
			case 'mgr':
				if (!$this->modx->loadClass('msearch2.request.mSearch2ControllerRequest', $this->config['modelPath'], true, true)) {
					return 'Could not load controller request handler.';
				}
				$this->request = new mSearch2ControllerRequest($this);

				return $this->request->handleRequest();
				break;
			default:
				$this->config = array_merge($this->config, $scriptProperties);
				$this->config['ctx'] = $ctx;

				if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
					$config = $this->makePlaceholders($this->config);
					if ($css = trim($this->modx->getOption('mse2_frontend_css'))) {
						$this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
					}
					if ($js = trim($this->modx->getOption('mse2_frontend_js'))) {
						$this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
					}
				}
		}

		return true;
	}


	/**
	 * Method loads custom classes from specified directory
	 *
	 * @var string $dir Directory for load classes
	 * @return void
	 */
	public function loadCustomClasses($dir)
	{
		$customPath = $this->config['customPath'];
		$placeholders = array(
			'base_path' => MODX_BASE_PATH,
			'core_path' => MODX_CORE_PATH,
			'assets_path' => MODX_ASSETS_PATH,
		);
		$pl1 = $this->pdoTools->makePlaceholders($placeholders, '', '[[+', ']]', false);
		$pl2 = $this->pdoTools->makePlaceholders($placeholders, '', '[[++', ']]', false);
		$pl3 = $this->pdoTools->makePlaceholders($placeholders, '', '{', '}', false);
		$customPath = str_replace($pl1['pl'], $pl1['vl'], $customPath);
		$customPath = str_replace($pl2['pl'], $pl2['vl'], $customPath);
		$customPath = str_replace($pl3['pl'], $pl3['vl'], $customPath);
		if (strpos($customPath, MODX_BASE_PATH) === false) {
			$customPath = MODX_BASE_PATH . ltrim($customPath, '/');
		}
		$customPath = rtrim($customPath, '/') . '/' . ltrim($dir, '/');
		if (file_exists($customPath) && $files = scandir($customPath)) {
			foreach ($files as $file) {
				if (preg_match('#\.class\.php$#i', $file)) {
					/** @noinspection PhpIncludeInspection */
					include $customPath . '/' . $file;
				}
			}
		} else {
			$this->modx->log(modX::LOG_LEVEL_ERROR, "[mSearch2] Custom path is not exists: \"{$customPath}\"");
		}
	}


	/**
	 * Initializes phpMorphy for needed language
	 *
	 * @return boolean
	 */
	public function loadPhpMorphy() {
		if (!class_exists('phpMorphy_Exception')) {
			/** @noinspection PhpIncludeInspection */
			require_once $this->config['corePath'] . 'phpmorphy/src/common.php';
		}

		$dicts = $this->getDictionaries();
		foreach (array_keys($dicts) as $lang) {
			if (!empty($this->phpMorphy[$lang]) && $this->phpMorphy[$lang] instanceof phpMorphy) {
				continue;
			}
			else {
				try {
					$this->phpMorphy[$lang] = new phpMorphy(
						$this->config['corePath'] . 'phpmorphy/dicts/',
						$lang,
						array(
							'storage' => 'file',
						)
					);
				}
				catch (phpMorphy_Exception $e) {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Could not initialize phpMorphy for language .' . $lang . ': .' . $e->getMessage());
				}
			}
		}

		if (!count($this->phpMorphy)) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Could not find any phpMorphy dictionary.');

			return false;
		}
		else {
			return true;
		}
	}


	/**
	 * Get dictionaries from phpMorphy directory
	 *
	 * @return array
	 */
	public function getDictionaries() {
		$dicts = array();

		$path = $this->config['dictsPath'];
		if (!file_exists($path)) {
			return $dicts;
		}

		$files = scandir($path);
		foreach ($files as $file) {
			if (preg_match('/\.([a-z]{2,2}\_[a-z]{2,2})\./i', $file, $matches)) {
				$dicts[$matches[1]][] = $file;
			}
		}

		return $dicts;
	}


	/**
	 * Returns array with words for search
	 *
	 * @param string $text
	 * @param string $pcre
	 * @param bool $with_count
	 *
	 * @return array
	 */
	public function getBulkWords($text = '', $pcre = '', $with_count = false) {
		if (empty($pcre)) {
			$pcre = $this->config['split_words'];
		}
		$words = preg_split($pcre, $text, -1, PREG_SPLIT_NO_EMPTY);
		$bulk_words = array();
		foreach ($words as $v) {
			if (preg_match('/^[0-9]{2,}$/', $v) || mb_strlen($v) >= $this->config['min_word_length']) {
				$word = mb_strtoupper($v);
				if (!$with_count) {
					$bulk_words[$word] = $word;
				}
				elseif (isset($bulk_words[$word])) {
					$bulk_words[$word] += 1;
				}
				else {
					$bulk_words[$word] = 1;
				}
			}
		}

		return $bulk_words;
	}


	/**
	 * Gets base form of the words
	 *
	 * @param array|string $text
	 * @param boolean $only_words
	 *
	 * @return array|string
	 */
	public function getBaseForms($text, $only_words = true) {
		$result = array();
		if (is_array($text)) {
			foreach ($text as $v) {
				$result = array_merge($result, $this->getBaseForms($v, $only_words));
			}
		}
		else {
			$text = str_ireplace('ё', 'е', $this->modx->stripTags($text));
			$text = preg_replace('#\[.*\]#isU', '', $text);

			$bulk_words = $this->getBulkWords($text, $this->config['split_all']);
			$this->loadPhpMorphy();
			/* @var phpMorphy $phpMorphy */
			$base_forms = array();
			foreach ($this->phpMorphy as $phpMorphy) {
				$locale = $phpMorphy->getLocale();
				$base_forms[$locale] = $phpMorphy->getBaseForm($bulk_words);
			}

			$result = array();
			foreach ($base_forms as $lang => $array) {
				if (!empty($array)) {
					foreach ($array as $word => $forms) {
						if (!$forms) {
							if (!$this->config['index_all']) {
								continue;
							}
							else {
								$forms = array($word);
							}
						}
						foreach ($forms as $form) {
							if (preg_match('/^[0-9]{2,}$/', $form) || mb_strlen($form) >= $this->config['min_word_length']) {
								$result[$form] = $lang == 'en_EN'
									? @iconv('WINDOWS-1250', 'UTF-8//IGNORE', $word)
									: $word;
							}
						}
					}
				}
			}
			if ($only_words) {
				$result = array_keys($result);
			}
		}

		return $result;
	}


	/**
	 * Gets all morphological forms of the words
	 *
	 * @param array|string $text
	 *
	 * @return array|string
	 */
	public function getAllForms($text) {
		$result = array();
		if (is_array($text)) {
			foreach ($text as $v) {
				$result = array_merge($result, $this->getAllForms($v));
			}
		}
		else {
			$text = str_ireplace('ё', 'е', $this->modx->stripTags($text));

			$bulk_words = $this->getBulkWords($text);
			$this->loadPhpMorphy();
			/* @var phpMorphy $phpMorphy */
			$all_forms = array();
			foreach ($this->phpMorphy as $phpMorphy) {
				$locale = $phpMorphy->getLocale();
				$all_forms[$locale] = $phpMorphy->getAllForms($bulk_words);
			}

			$result = array();
			if (!empty($all_forms)) {
				foreach ($all_forms as $lang => $array) {
					if (!empty($array)) {
						foreach ($array as $word => $forms) {
							if (!empty($forms)) {
								if ($lang == 'en_EN') {
									foreach ($forms as &$v) {
										$v = iconv('WINDOWS-1250', 'UTF-8//IGNORE', $v);
									}
								}
								$result[$word] = isset($result[$word])
									? array_merge($result[$word], $forms)
									: $forms;
							}
						}
					}
				}
			}
		}

		return $result;
	}


	/**
	 * Search and return array with resources ids as a key and sum of weight as value
	 *
	 * @param $query
	 * @param bool $process_aliases
	 *
	 * @return array
	 */
	function Search($query, $process_aliases = true) {
		if (!empty($this->config['old_search_algorithm'])) {
			return $this->OldSearch($query);
		}

		if ($process_aliases) {
			$query = preg_replace('/[^_-а-яёa-z0-9\s\.\/]+/iu', ' ', $this->modx->stripTags($query));
			$this->log('Filtered search query: "' . mb_strtolower($query) . '"');
			if ($aliases = $this->getAliases($query)) {
				$this->log('Generated aliases for search query: "' . implode('; ', $aliases) . '"');
				$results = array();
				foreach ($aliases as $query) {
					$this->log('Search by alias: "' . $query . '"');
					$results[] = $this->Search($query, false);
				}
				$found = array();
				foreach ($results as $result) {
					foreach ($result as $k => $v) {
						if (isset($found[$k])) {
							$found[$k] += $v;
						}
						else {
							$found[$k] = $v;
						}
					}
				}
				arsort($found);

				return $found;
			}
		}

		$search_forms = $this->getBaseForms($query, false);
		$bulk_words = $this->getBulkWords($query);

		// Search by words index
		$index = $debug = array();
		if (!empty($search_forms)) {
			$q = $this->modx->newQuery('mseWord');
			$q->select($this->modx->getSelectColumns('mseWord', 'mseWord'));
			$q->where(array('word:IN' => array_keys($search_forms), 'field:IN' => array_keys($this->fields)));
			$tstart = microtime(true);
			if ($q->prepare() && $q->stmt->execute()) {
				$this->modx->queryTime += microtime(true) - $tstart;
				$this->modx->executedQueries++;
				while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
					$weight = $this->fields[$row['field']] * $row['count'];
					// Add to results
					if (!isset($index[$row['resource']])) {
						$index[$row['resource']] = array(
							'words' => array(
								$row['word'] => $weight,
							),
							'weight' => $weight,
						);
					}
					else {
						if (!isset($index[$row['resource']][$row['word']])) {
							$index[$row['resource']]['words'][$row['word']] = $weight;
						}
						else {
							$index[$row['resource']]['words'][$row['word']] += $weight;
						}
						$index[$row['resource']]['weight'] += $weight;
					}

					// Search by INDEX debug
					$debug[$row['resource']][] = array(
						'field' => $row['field'],
						'word' => $row['word'],
						'count' => $row['count'],
						'weight' => $this->fields[$row['field']],
						'total' => $weight,
					);
				}
			}
		}

		$message = '';
		$leaved = $removed = 0;

		$result = array();
		if (!empty($index)) {
			$this->log('Found results by words INDEX (' . implode(',', $bulk_words) . '): <b>' . count($index) . '</b>');
			$all_forms = $this->getAllForms($bulk_words);
			foreach ($index as $k => $v) {
				$not_found = array();
				foreach ($bulk_words as $word) {
					if (!isset($all_forms[$word])) {
						$not_found[] = $word;
					}
					elseif (!array_intersect($all_forms[$word], array_keys($v['words']))) {
						$not_found[] = $word;
					}
				}
				if (!empty($not_found)) {
					if ($this->simpleSearch(implode(' ', $not_found), false, $k)) {
						foreach ($not_found as $word) {
							$index[$k]['words'][$word] = $this->config['like_match_bonus'];
							$index[$k]['weight'] += $this->config['like_match_bonus'];
							$message .= "\n\t+ {$this->config['like_match_bonus']} points to resource {$k} for word \"{$word}\" in LIKE search";
						}
					}
					else {
						unset($index[$k]);
						$message .= "\n\t- resource {$k} because it does not contain all necessary words";
						$removed++;
						continue;
					}
				}
				foreach ($debug[$k] as $v2) {
					$message .= "\n\t+ {$v2['total']} points to resource {$k} for word \"{$v2['word']}\" in field \"{$v2['field']}\" ({$v2['count']} * {$v2['weight']})";
				}
				$result[$k] = $index[$k]['weight'];
				$leaved++;
			}
			$this->log("Filtering results of INDEX search: <b>{$leaved}</b> leaved, <b>{$removed}</b> removed." . $message);
		}
		else {
			$this->log('Nothing found by words INDEX');
		}

		$added = 0;
		$like = $this->simpleSearch($query, false);
		$message = '';
		foreach ($like as $k) {
			if (!isset($result[$k])) {
				$result[$k] = $this->config['like_match_bonus'];
				$message .= "\n\t+ {$this->config['like_match_bonus']} points to resource {$k} for words \"" . implode(',', $bulk_words) . "\"";
				$added++;
			}
		}
		if ($added) {
			$this->log("Added results by LIKE search: <b>{$added}</b>" . $message);
		}

		if (count($bulk_words) > 1) {
			if ($exact = $this->simpleSearch($query, true, array_keys($result))) {
				$message = 'Trying to apply "exact_match_bonus":';
				foreach ($exact as $k) {
					$result[$k] += $this->config['exact_match_bonus'];
					$message .= "\n\t+ {$this->config['exact_match_bonus']} to resource {$k}";
				}
				$this->log($message);
			}
		}

		// Sort results by weight
		arsort($result);

		// Log the search query
		$query = preg_replace('#[^\w\s-]#iu', '', $query);
		/** @var mseQuery $object */
		if ($object = $this->modx->getObject('mseQuery', array('query' => $query))) {
			$object->set('quantity', $object->get('quantity') + 1);
		}
		else {
			$object = $this->modx->newObject('mseQuery');
			$object->set('query', $query);
			$object->set('quantity', 1);
		}
		$object->set('found', count($result));
		$object->save();

		return $result;
	}


	/**
	 * Search and return array with resources ids as a key and sum of weight as value
	 *
	 * @param $query
	 *
	 * @deprecated
	 *
	 * @return array
	 */
	public function OldSearch($query) {
		$string = preg_replace('/[^_-а-яёa-z0-9\s\.\/]+/iu', ' ', $this->modx->stripTags($query));
		$this->log('Filtered search query: "' . mb_strtolower($query . '"'));
		$query = $this->query = $this->addAliases($string);
		$this->log('Search query with processed aliases: "' . mb_strtolower($query . '"'));
		$words = $this->getBaseForms($query, false);
		$result = $all_words = $found_words = $debug = array();

		// Search by words index
		if (!empty($words)) {
			$q = $this->modx->newQuery('mseWord');
			$q->select($this->modx->getSelectColumns('mseWord', 'mseWord'));
			$q->where(array('word:IN' => array_keys($words), 'field:IN' => array_keys($this->fields)));
			$tstart = microtime(true);
			if ($q->prepare() && $q->stmt->execute()) {
				$this->modx->queryTime += microtime(true) - $tstart;
				$this->modx->executedQueries++;
				while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
					$weight = $this->fields[$row['field']] * $row['count'];
					// Add to results
					if (isset($result[$row['resource']])) {
						$result[$row['resource']] += $weight;
					}
					else {
						$result[$row['resource']] = $weight;
					}

					// Search by INDEX debug
					$debug[$row['resource']][] = array(
						'field' => $row['field'],
						'word' => $row['word'],
						'count' => $row['count'],
						'weight' => $this->fields[$row['field']],
						'total' => $weight,
					);

					if (isset($words[$row['word']])) {
						$all_words[$row['resource']][$words[$row['word']]] = 1;
						$found_words[$words[$row['word']]] = 1;
					}
				}
			}
		}
		$added = 0;

		if (!empty($found_words)) {
			$message = 'Found results by words INDEX (' . implode(',', array_keys($found_words)) . '): ' . count($result);
			ksort($debug);
			foreach ($debug as $k => $v) {
				foreach ($v as $v2) {
					$message .= "\n\t+ {$v2['total']} points to resource {$k} for word \"{$v2['word']}\" in field \"{$v2['field']}\" ({$v2['count']} * {$v2['weight']})";
				}
			}
			$this->log($message);
		}
		else {
			$this->log('Nothing found by words INDEX');
		}

		// Add bonuses
		$bulk_words = $this->getBulkWords($query);
		if (!empty($this->config['all_words_bonus'])) {
			if (count($bulk_words) > 1) {
				// All words bonus
				foreach ($all_words as $k => $v) {
					if (count($bulk_words) == count($v)) {
						$result[$k] += $this->config['all_words_bonus'];
						$this->log("+ {$this->config['all_words_bonus']} points to resource {$k} for system setting \"all_words_bonus\" with words \"" . implode(', ', $bulk_words) . "\"");
					}
					elseif (!empty($this->config['onlyAllWords'])) {
						unset($result[$k]);
						$this->log("Resource {$k} was removed because of enabled &onlyAllWords parameter with words \"" . implode(', ', array_keys($v)) . "\"");
					}
				}
			}
		}

		if (empty($this->config['onlyIndex'])) {
			$tmp_words = preg_split($this->config['split_words'], $query, -1, PREG_SPLIT_NO_EMPTY);
			if (count($bulk_words) > 1 || count($tmp_words) > 1 /*|| empty($result)*/) {
				if (!empty($this->config['exact_match_bonus']) || !empty($this->config['all_words_bonus'])) {
					$exact = $this->simpleSearch($query);
					// Exact match bonus
					if (!empty($this->config['exact_match_bonus'])) {
						foreach ($exact as $v) {
							if (isset($result[$v])) {
								$result[$v] += $this->config['exact_match_bonus'];
								$this->log("+ {$this->config['exact_match_bonus']} points to resource {$v} for system setting \"exact_match_bonus\" with words \"{$query}\"");
							}
							else {
								$result[$v] = $this->config['exact_match_bonus'];
								$this->log("+ {$this->config['exact_match_bonus']} points to new resource {$v} that was found by LIKE search with exact matched words \"{$query}\"");
								$added++;
							}
						}
					}
				}
			}
			elseif (!empty($this->config['like_match_bonus'])) {
				// Add the whole possible results for query
				$all_results = $this->simpleSearch($query);
				foreach ($all_results as $v) {
					if (!isset($result[$v])) {
						$weight = round($this->config['like_match_bonus']);
						$result[$v] = $weight;
						$this->log("+ {$weight} points to resource {$v} by LIKE search with words \"{$query}\"");
						$added++;
					}
				}
			}

			// Add matches by %LIKE% search
			if (!empty($this->config['like_match_bonus']) && $not_found = array_diff($bulk_words, $words)) {
				foreach ($not_found as $word) {
					$found = $this->simpleSearch($word);
					foreach ($found as $v) {
						$weight = round($this->config['like_match_bonus']);
						if (!isset($result[$v])) {
							$result[$v] = $weight;
							$this->log("+ {$weight} points to new resource {$v} that was found by LIKE search with single word \"{$word}\"");
							$added++;
						}
						else {
							$result[$v] += $weight;
							$this->log("+ {$weight} points to resource {$v} by LIKE search for word \"{$word}\"");
						}
					}
				}
			}
			$this->log('Added resources by LIKE search: ' . $added);
		}

		// Log the search query
		$query = preg_replace('#[^\w\s-]#iu', '', $query);
		/** @var mseQuery $object */
		if ($object = $this->modx->getObject('mseQuery', array('query' => $query))) {
			$object->set('quantity', $object->get('quantity') + 1);
		}
		else {
			$object = $this->modx->newObject('mseQuery');
			$object->set('query', $query);
			$object->set('quantity', 1);
		}
		$object->set('found', count($result));
		$object->save();
		arsort($result);

		return $result;
	}


	/**
	 * Search and return array with resources that matched for LIKE search
	 *
	 * @param $query
	 * @param bool $exact
	 * @param array $resources
	 *
	 * @return array
	 */
	public function simpleSearch($query, $exact = true, $resources = array()) {
		$string = $this->modx->stripTags($query);

		$result = array();
		$q = $this->modx->newQuery('mseIntro');
		$q->select('resource');
		if ($exact) {
			$q->where(array('intro:LIKE' => '%' . $string . '%'));
		}
		else {
			$bulk_words = $this->getBulkWords($string, $this->config['split_all']);
			foreach ($bulk_words as $word) {
				$q->andCondition("intro LIKE '%{$word}%'");
			}
		}
		if (!empty($resources)) {
			if (!is_array($resources)) {
				$q->where(array('resource' => $resources));
			}
			else {
				$q->where(array('resource:IN' => $resources));
			}
		}
		$tstart = microtime(true);
		if ($q->prepare() && $q->stmt->execute()) {
			$this->modx->queryTime += microtime(true) - $tstart;
			$this->modx->executedQueries++;
			$result = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
		}

		return $result;
	}


	/**
	 * @param $string
	 *
	 * @return string
	 */
	public function addAliases($string) {
		$string = mb_strtoupper(str_ireplace('ё', 'е', $this->modx->stripTags($string)));
		$string = preg_replace('#\[.*\]#isU', '', $string);

		$pcre = $this->config['split_words'];
		$words = preg_split($pcre, $string, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($words as $k => $v) {
			if (!preg_match('/^[0-9]{2,}$/', $v) && mb_strlen($v) < $this->config['min_word_length']) {
				unset($words[$k]);
			}
		}
		$words = array_unique($words);
		$forms = $this->getBaseForms($words, false);
		if (!$words && !$forms) {
			return '';
		}
		$q = $this->modx->newQuery('mseAlias', array('word:IN' => array_merge($words, array_keys($forms))));
		$q->select('word,alias,replace');
		$tstart = microtime(true);
		if ($q->prepare() && $q->stmt->execute()) {
			$this->modx->queryTime += microtime(true) - $tstart;
			$this->modx->executedQueries++;
			while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
				if ($row['replace']) {
					$all = current($this->getAllForms($row['word']));
					$all[] = $row['word'];
					foreach ($all as $word) {
						$key = array_search(mb_strtoupper($word), array_map('mb_strtoupper', $words), false);
						if ($key !== false) {
							$words[$key] = $row['alias'];
							break;
						}
					}
				}
				else {
					$words[] = $row['alias'];
				}
			}
		}
		$words = array_unique(array_map('mb_strtoupper', $words));

		return implode(' ', $words);
	}


	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	public function getAliases($string) {
		$string = mb_strtoupper(str_ireplace('ё', 'е', $this->modx->stripTags($string)));
		$string = preg_replace('#\[.*\]#isU', '', $string);

		$words = array_map('mb_strtoupper', array_unique($this->getBulkWords($string)));
		$forms = $this->getBaseForms($words, false);

		if (!$words && !$forms) {
			return array();
		}
		$queries = array();
		$add_original = false;

		$q = $this->modx->newQuery('mseAlias', array('word:IN' => array_merge($words, array_keys($forms))));
		$q->select('word, alias, replace');
		$tstart = microtime(true);
		if ($q->prepare() && $q->stmt->execute()) {
			$this->modx->queryTime += microtime(true) - $tstart;
			$this->modx->executedQueries++;
			while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
				$tmp = $words;
				$all = current($this->getAllForms($row['word']));
				$all[] = $row['word'];
				foreach ($all as $word) {
					$key = array_search(mb_strtoupper($word), $tmp, false);
					if ($key !== false) {
						$tmp[$key] = $row['alias'];
					}
				}

				if (!$add_original && !$row['replace']) {
					$add_original = true;
				}
				$queries[] = implode(' ', array_unique(array_map('mb_strtoupper', $tmp)));
			}
		}
		if ($add_original) {
			$queries[] = implode(' ', array_unique($words));
		}

		return array_unique($queries);
	}


	/**
	 * Highlight search string in given text string
	 *
	 * @param $text
	 * @param string $query
	 * @param string $htag_open
	 * @param string $htag_close
	 * @param boolean $strict
	 *
	 * @return mixed
	 */
	public function Highlight($text, $query, $htag_open = '<b>', $htag_close = '</b>', $strict = true) {
		if (empty($query)) {
			return $text;
		}
		$from = $to = array();

		$tmp_words = preg_split($this->config['split_words'], $query, -1, PREG_SPLIT_NO_EMPTY);
		// Exact match
		$pcre = $strict
			? '#\b' . preg_quote($query) . '\b#imus'
			: '#' . preg_quote($query) . '#imus';
		if (count($tmp_words) > 1 && preg_match($pcre, $text, $matches)) {
			$pos = mb_stripos($text, $matches[0], 0);
			if ($pos >= $this->config['introCutBefore']) {
				$text_cut = '... ';
				$pos -= $this->config['introCutBefore'];
			}
			else {
				$text_cut = '';
				$pos = 0;
			}
			$text_cut .= mb_substr($text, $pos, $this->config['introCutAfter']);
			if (mb_strlen($text) > $this->config['introCutAfter']) {
				$text_cut .= ' ...';
			}

			foreach ($matches as $v) {
				$from[$v] = $v;
				$to[$v] = $htag_open . $v . $htag_close;
			}
		}
		// Matching by separate words
		else {
			if (empty($this->query)) {
				$this->query = $this->addAliases($query);
			}

			$tmp = array_merge(
				$tmp_words,
				explode(' ', $this->query)
			);
			$tmp = array_unique($tmp);
			$tmp = $this->getAllForms($tmp);

			$words = array_keys($tmp);
			foreach ($tmp as $v) {
				$words = array_merge($words, $v);
			}

			if (empty($words)) {
				$words = array($query => $query);
			}

			$text_cut = '';
			foreach ($words as $key => $word) {
				/*
				if (!preg_match('/^[0-9]{2,}$/', $word) && mb_strlen($word) < $this->config['min_word_length']) {
					unset($words[$key]);
					continue;
				}
				*/
				$word = preg_quote($word, '/');
				$words[$key] = $word;

				// Cutting text on first occurrence
				$pcre = $strict ? '/\b' . $word . '\b/imu' : '/' . $word . '/imu';
				if (empty($text_cut) && !empty($word) && preg_match($pcre, $text, $matches)) {
					$pos = mb_stripos($text, $matches[0], 0);
					if ($pos >= $this->config['introCutBefore']) {
						$text_cut = '... ';
						$pos -= $this->config['introCutBefore'];
					}
					else {
						$text_cut = '';
						$pos = 0;
					}
					$text_cut .= mb_substr($text, $pos, $this->config['introCutAfter']);
					if (mb_strlen($text) > $this->config['introCutAfter']) {
						$text_cut .= ' ...';
					}
				}
			}

			if (empty($text_cut) && $strict) {
				return $this->Highlight($text, $query, $htag_open, $htag_close, false);
			}

			$pcre = $strict ? '/\b(' . implode('|', $words) . ')\b/imu' : '/(' . implode('|', $words) . ')/imu';
			if (preg_match_all($pcre, $text_cut, $matches)) {
				foreach ($matches[0] as $v) {
					$from[$v] = $v;
					$to[$v] = $htag_open . $v . $htag_close;
				}
			}

			if (!empty($matches[1])) {
				foreach ($matches[1] as $v) {
					$from[$v] = $v;
					$to[$v] = $htag_open . $v . $htag_close;
				}
			}
			elseif ($strict) {
				return $this->Highlight($text, $query, $htag_open, $htag_close, false);
			}
		}

		return str_replace($from, $to, $text_cut);
	}


	/**
	 * Return array with filters
	 *
	 * @param array|string $ids
	 * @param boolean $build
	 *
	 * @return array|boolean
	 */
	public function getFilters($ids, $build = true) {
		// prepare ids
		if (!is_array($ids)) {
			$ids = array_map('trim', explode(',', $ids));
		}
		if (empty($ids)) {
			return false;
		}

		// Return results from cache
		if ($build && $cache = $this->modx->cacheManager->get('msearch2/prep_' . md5(implode(',', $ids) . $this->config['filters']))) {
			$this->methods = $cache['methods'];
			$this->aliases = $cache['aliases'];

			return $cache['filters'];
		}
		elseif (!$build && !empty($this->filters)) {
			return $this->filters;
		}
		elseif (!$build && $this->filters = $this->modx->cacheManager->get('msearch2/fltr_' . md5(implode(',', $ids)))) {
			return $this->filters;
		}

		if (!is_object($this->filtersHandler)) {
			$this->loadHandler();
		}
		if (empty($this->filters) || empty($this->methods)) {
			// Preparing filters
			$filters = $built = $duplicates = array();
			$tmp_filters = array_map('trim', explode(',', $this->config['filters']));
			foreach ($tmp_filters as $v) {
				$v = strtolower($v);
				if (empty($v)) {
					continue;
				}
				elseif (strpos($v, $this->config['filter_delimeter']) !== false) {
					@list($table, $filter) = explode($this->config['filter_delimeter'], $v);
				}
				else {
					$table = 'resource';
					$filter = $v;
				}

				$tmp = explode($this->config['method_delimeter'], $filter);
				$name = $tmp[0];
				$filter = !empty($tmp[1])
					? $tmp[1]
					: 'default';
				// Duplicates
				if (isset($filters[$table][$name])) {
					$old_filter = $built[$table . $this->config['filter_delimeter'] . $name];
					$new_name = $name . '-' . $old_filter;
					$built[$table . $this->config['filter_delimeter'] . $new_name] = $old_filter;
					$filters[$table][$new_name] = $filters[$table][$name];
					$duplicates[$table][$new_name] = $name;

					$new_name = $name . '-' . $filter;
					$duplicates[$table][$new_name] = $name;
					$name = $new_name;
				}
				$filters[$table][$name] = array();
				$built[$table . $this->config['filter_delimeter'] . $name] = $filter;
			}

			// Retrieving filters
			foreach ($filters as $table => &$fields) {
				$method = 'get' . ucfirst($table) . 'Values';
				$keys = !empty($duplicates[$table])
					? array_diff(array_keys($fields), array_keys($duplicates[$table]))
					: array_keys($fields);
				if (method_exists($this->filtersHandler, $method)) {
					$fields = call_user_func_array(array($this->filtersHandler, $method), array($keys, $ids));
					if (!empty($duplicates[$table])) {
						foreach ($duplicates[$table] as $key => $field) {
							$fields[$key] = $fields[$field];
						}
					}
				}
				else {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Method "' . $method . '" not exists in class "' . get_class($this->filtersHandler) . '". Could not retrieve filters from "' . $table . '"');
				}
			}
			unset($fields);

			// Remove duplicates
			foreach ($duplicates as $table => $fields) {
				$tmp = array_unique($fields);
				foreach ($tmp as $tmp2) {
					unset($filters[$table][$tmp2]);
					unset($built[$table . $this->config['filter_delimeter'] . $tmp2]);
				}
			}

			// Prepare aliases
			$aliases = array();
			if (!empty($this->config['aliases'])) {
				$tmp = array_map('trim', explode(',', $this->config['aliases']));
				foreach ($tmp as $v) {
					if (strpos($v, '==') !== false) {
						$tmp2 = array_map('trim', explode('==', $v));
						$aliases[str_replace('.', '_', $tmp2[0])] = $tmp2[1];
					}
				}
				$this->aliases = $aliases;
			}

			$this->filters = $filters;
			$this->methods = $built;
		}
		// The replacement dots to underscores in filters names
		foreach ($this->filters as $table => $fields) {
			foreach ($fields as $key => $values) {
				if (strpos($key, '.') !== false) {
					$this->filters[$table][str_replace('.', '_', $key)] = $values;
					unset($this->filters[$table][$key]);
				}
			}
		}

		if (!$build) {
			return $this->filters;
		}
		$built = $this->methods;

		$prepared = array();
		foreach ($this->filters as $table => $filters) {
			foreach ($filters as $key => $values) {
				$new_key = $table . $this->config['filter_delimeter'] . $key;
				$filter = !empty($built[$new_key])
					? $built[$new_key]
					: 'default';
				$method = 'build' . ucfirst($filter) . 'Filter';
				if ($filter == 'default') {
					switch ($table) {
						case 'tv':
							$method = 'buildTVsFilter';
							break;
						case 'msoption':
							$method = 'buildOptionsFilter';
							break;
					}
				}
				if (method_exists($this->filtersHandler, $method)) {
					$prepared[$new_key] = call_user_func_array(array($this->filtersHandler, $method), array($values, $key));
				}
				elseif (method_exists($this->filtersHandler, 'buildDefaultFilter')) {
					$prepared[$new_key] = call_user_func_array(array($this->filtersHandler, 'buildDefaultFilter'), array($values, $key));
				}
				else {
					$this->modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Method "' . $method . '" not exists in class "' . get_class($this->filtersHandler) . '". Could not build filter for "' . $new_key . '"');
					$prepared[$new_key] = $values;
				}
			}
		}

		// Sort filters
		foreach ($built as $key => &$values) {
			if (isset($prepared[$key])) {
				$values = $prepared[$key];
				unset($prepared[$key]);
			}
		}
		// Add new generated filters to the end of list
		$built = array_merge($built, $prepared);
		$cache = array(
			'filters' => $built,
			'methods' => $this->methods,
			'aliases' => $this->aliases,
		);
		// Set cache
		if (!empty($this->config['cacheTime'])) {
			$this->modx->cacheManager->set('msearch2/prep_' . md5(implode(',', $ids) . $this->config['filters']), $cache, $this->config['cacheTime']);
		}

		return $built;
	}


	/**
	 * Filters resources by given params
	 *
	 * @param array|string $ids
	 * @param array $request
	 *
	 * @return array
	 */
	public function Filter($ids, array $request) {
		if (!is_array($ids)) {
			$ids = explode(',', $ids);
		}
		if (!is_object($this->filtersHandler)) {
			$this->loadHandler();
		}

		$this->getFilters($ids, false);
		$filters = $this->filters;
		$methods = $this->methods;
		$aliases = array_flip($this->aliases);

		foreach ($request as $filter => $requested) {
			if (!empty($aliases[$filter])) {
				$filter = $aliases[$filter];
			}
			if (!preg_match('/(.*?)' . preg_quote($this->config['filter_delimeter'], '/') . '(.*?)/', $filter)) {
				continue;
			}
			$method = !empty($methods[$filter])
				? 'filter' . ucfirst($methods[$filter])
				: 'filterDefault';

			list($table, $filter) = explode($this->config['filter_delimeter'], $filter);
			if (isset($filters[$table][$filter])) {
				$values = $filters[$table][$filter];
				$requested = explode($this->config['values_delimeter'], $requested);

				if (method_exists($this->filtersHandler, $method)) {
					$ids = call_user_func_array(array($this->filtersHandler, $method), array($requested, $values, $ids));
				}
				else {
					//$this->modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Method "'.$method.'" not exists in class "'.get_class($this->filtersHandler).'". Could not build filter "'.$table.$this->config['filter_delimeter'].$filter.'"');
					$ids = @call_user_func_array(array($this->filtersHandler, 'filterDefault'), array($requested, $values, $ids));
				}
			}
			$this->filter_operations++;
		}

		return $ids;
	}


	/**
	 * This method returns preliminary results for each filter
	 *
	 * @param $ids
	 * @param array $request
	 * @param array $current
	 *
	 * @return array
	 */
	public function getSuggestions($ids, array $request, array $current = array()) {
		if (!is_array($ids)) {
			$ids = explode(',', $ids);
		}
		if (!is_object($this->filtersHandler)) {
			$this->loadHandler();
		}
		foreach ($request as $k => $v) {
			$request[$k] = str_replace('"', '&quot;', $v);
		}

		if (method_exists($this->filtersHandler, 'getSuggestions')) {
			return $this->filtersHandler->getSuggestions($ids, $request, $current);
		}
		else {
			$current = array_flip($current);
			$filters = $this->getFilters($ids, false);
			$built = $this->getFilters($ids, true);
			$radio = $this->config['suggestionsRadio'];
			$aliases = $this->aliases;

			$suggestions = array();
			foreach ($filters as $table => $fields) {
				foreach ($fields as $field => $values) {
					$key = $alias = $table . $this->config['filter_delimeter'] . $field;
					if (!empty($aliases[$key])) {
						$alias = $aliases[$key];
					}

					$values = $built[$key];
					foreach ($values as $tmp) {
						$value = $tmp['value'];
						$suggest = $request;

						$added = 0;
						if (isset($request[$alias])) {
							// Types of suggestion can depend from method
							if (!empty($radio) && in_array($key, $radio)) {
								$suggest[$alias] = $value;
							}
							else {
								$tmp2 = explode($this->config['values_delimeter'], $request[$alias]);
								if (!in_array($value, $tmp2)) {
									$suggest[$alias] .= $this->config['values_delimeter'] . $value;
									$added = 1;
								}
							}
							$res = $this->Filter($ids, $suggest);
							if ($added && !empty($res)) {
								foreach ($res as $k => $v) {
									if (isset($current[$v])) {
										unset($res[$k]);
									}
								}
								$count = count($res);
								if ($count != 0) {
									$count = '+' . $count;
								}
							}
							else {
								$count = count($res);
							}
						}
						else {
							$suggest[$alias] = $value;
							$res = $this->Filter($ids, $suggest);
							$count = count($res);
						}

						$suggestions[$alias][$value] = $count;
					}
				}
			}

			return $suggestions;
		}
	}


	/**
	 * Method for transform array to placeholders
	 *
	 * @var array $array With keys and values
	 * @var string $prefix
	 *
	 * @return array $array Two nested arrays With placeholders and values
	 */
	public function makePlaceholders(array $array = array(), $prefix = '') {
		$result = array(
			'pl' => array(),
			'vl' => array(),
		);
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$result = array_merge_recursive($result, $this->makePlaceholders($v, $prefix . $k . '.'));
			}
			else {
				$result['pl'][$prefix . $k] = '[[+' . $prefix . $k . ']]';
				$result['vl'][$prefix . $k] = $v;
			}
		}

		return $result;
	}


	/**
	 * Returns string for insert into sorting properties of pdoTools snippet
	 *
	 * @param string
	 *
	 * @return string
	 */
	public function getSortFields($sort) {
		$this->loadHandler();

		return $this->filtersHandler->getSortFields($sort);
	}


	/**
	 * Loads custom filters handler class
	 *
	 * @return bool
	 */
	public function loadHandler() {
		if (!is_object($this->filtersHandler)) {
			require_once 'filters.class.php';
			$filters_class = $this->modx->getOption('mse2_filters_handler_class', null, 'mse2FiltersHandler', true);
			if ($filters_class != 'mse2FiltersHandler') {
				$this->loadCustomClasses('filters');
			}
			if (!class_exists($filters_class)) {
				$filters_class = 'mse2FiltersHandler';
			}

			$this->filtersHandler = new $filters_class($this, $this->config);
			if (!($this->filtersHandler instanceof mse2FiltersHandler)) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Could not initialize filters handler class: "' . $filters_class . '"');

				return false;
			}
		}

		return true;
	}


	/**
	 * Checks for exists of miniShop2
	 *
	 * @return bool
	 */
	public function checkMS2() {
		return file_exists(MODX_CORE_PATH . 'components/minishop2/model/minishop2/msproduct.class.php');
	}


	/**
	 * @param $entry
	 */
	public function log($entry) {
		if ($this->pdoTools && !empty($this->config['showSearchLog'])) {
			$this->pdoTools->addTime('[mSearch2] ' . $entry);
		}
	}

}
