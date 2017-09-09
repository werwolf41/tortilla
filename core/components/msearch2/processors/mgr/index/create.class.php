<?php
/**
 * Create search index of all resources
 *
 * @package msearch2
 * @subpackage processors
 */
class mseIndexCreateProcessor extends modProcessor {
	/** @var string $objectType The object "type", this will be used in various lexicon error strings */
	public $objectType = 'mseWord';
	/** @var string $classKey The class key of the Object to iterate */
	public $classKey = 'mseWord';
	/** @var array $languageTopics An array of language topics to load */
	public $languageTopics = array('msearch2:default');
	/** @var string $permission The Permission to use when checking against */
	public $permission = '';
	/** @var mSearch2 $mSearch2 */
	public $mSearch2;
	protected $_limit = 100;
	protected $_offset = 0;
	protected $_total = 0;


	/**
	 * {@inheritDoc}
	 */
	public function checkPermissions() {
		return !empty($this->permission) ? $this->modx->hasPermission($this->permission) : true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLanguageTopics() {
		return $this->languageTopics;
	}


	/**
	 * {@inheritDoc}
	 */
	public function process() {
		$this->loadClass();
		$this->mSearch2->getWorkFields();

		$collection = $this->getResources();
		if (!is_array($collection) && empty($collection)) {
			return $this->failure('mse2_err_no_resources_for_index');
		}

		$process_comments = $this->modx->getOption('mse2_index_comments', null, true, true) && class_exists('Ticket');
		$i = 0;
		/* @var modResource|Ticket|msProduct $resource */
		foreach ($collection as $data) {
			if ($data['deleted'] || !$data['searchable']) {
				$this->unIndex($data['id']);
				continue;
			}

			$class_key = $data['class_key'];
			if (!isset($this->modx->map[$class_key])) {
				continue;
			}
			$resource = $this->modx->newObject($class_key);
			$resource->fromArray($data, '', true, true);

			$comments = '';
			if ($process_comments) {
				$q = $this->modx->newQuery('TicketComment', array('deleted' => 0, 'published' => 1));
				$q->innerJoin('TicketThread', 'Thread', '`TicketComment`.`thread`=`Thread`.`id` AND `Thread`.`deleted`=0');
				$q->innerJoin('modResource', 'Resource', '`Thread`.`resource`=`Resource`.`id` AND `Resource`.`id`='.$resource->get('id'));
				$q->select('text');
				if ($q->prepare() && $q->stmt->execute()) {
					while ($row = $q->stmt->fetch(PDO::FETCH_COLUMN)) {
						$comments .= $row.' ';
					}
				}
			}
			$resource->set('comment', $comments);

			$this->Index($resource);
			$i++;
		}
		$offset = $this->_offset + $this->_limit;
		$done = $offset >= $this->_total;

		return $this->success('', array(
			'indexed' => $i,
			'offset' => $done ? 0 : $offset,
			'done' => $done,
		));
	}


	/**
	 * Prepares query and returns resource for indexing
	 *
	 * @return array|null
	 */
	public function getResources() {
		$this->_limit = $this->getProperty('limit', 100);
		$this->_offset = $this->getProperty('offset', 0);

		$select_fields = array_intersect(
			array_keys($this->modx->getFieldMeta('modResource'))
			,array_keys($this->mSearch2->fields)
		);
		$select_fields = array_unique(array_merge($select_fields, array('id','class_key','deleted','searchable')));

		$c = $this->modx->newQuery('modResource');
		$c->sortby('id','ASC');
		$c->select($this->modx->getSelectColumns('modResource', 'modResource', '', $select_fields));
		$c = $this->prepareQuery($c);
		$this->_total = $this->modx->getCount('modResource', $c);
		$c->limit($this->_limit, $this->_offset);

		$collection = array();
		if ($c->prepare() && $c->stmt->execute()) {
			$collection = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			$this->modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Could not retrieve collection of resources: '.$c->stmt->errorInfo());
		}

		return $collection;
	}


	/**
	 * Prepares query before retrieving resources
	 *
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQuery(xPDOQuery $c) {
		//$c->where(array('searchable' => 1));

		return $c;
	}


	/**
	 * Create index of resource
	 *
	 * @param xPDOObject $resource
	 */
	public function Index(xPDOObject $resource) {
		$this->modx->invokeEvent('mse2OnBeforeSearchIndex', array(
			'object' => $resource,
			'resource' => $resource,
			'mSearch2' => $this->mSearch2,
		));

		$words = array();
		$intro = '';
		// For proper transliterate umlauts
		setlocale(LC_ALL, 'en_US.UTF8', LC_CTYPE);

		foreach ($this->mSearch2->fields as $field => $weight) {
			if (strpos($field, 'tv_') !== false && $resource instanceof modResource) {
				$text = $resource->getTVValue(substr($field, 3));
				// Support for arrays in TVs
				if (!empty($text) && ($text[0] == '[' || $text[0] == '{')) {
					$tmp = $this->modx->fromJSON($text);
					if (is_array($tmp)) {
						$text = $tmp;
					}
				}
			}
			else {
				$text = $resource->get($field);
			}
			$forms = $this->_getBaseForms($text);
			$intro .= $this->modx->stripTags(is_array($text) ? $this->_implode_r(' ', $text) : $text) . ' ';
			foreach ($forms as $form => $count) {
				$words[$form][$field] = $count;
			}
		}

		$tword = $this->modx->getTableName('mseWord');
		$tintro = $this->modx->getTableName('mseIntro');
		$resource_id = $resource->get('id');

		$intro = str_replace(array("\n","\r\n","\r"), ' ', $intro);
		$intro = preg_replace('#\s+#u', ' ', str_replace(array('\'','"','«','»','`'), '', $intro));
		$sql = "INSERT INTO {$tintro} (`resource`, `intro`) VALUES ('$resource_id', '$intro') ON DUPLICATE KEY UPDATE `intro` = '$intro';";
		$sql .= "DELETE FROM {$tword} WHERE `resource` = '$resource_id';";

		if (!$class_key = $resource->get('class_key')) {
			$class_key = get_class($resource);
		}
		if (!empty($words)) {
			$rows = array();
			foreach ($words as $word => $fields) {
				foreach ($fields as $field => $count) {
					$rows[] = "({$resource_id}, '{$field}', '{$word}', '{$count}', '{$class_key}')";
				}
			}
			$sql .= "INSERT INTO {$tword} (`resource`, `field`, `word`, `count`, `class_key`) VALUES " . implode(',', $rows);
		}

		$q = $this->modx->prepare($sql);
		if ($q->execute()) {
			$this->modx->invokeEvent('mse2OnSearchIndex', array(
				'object' => $resource,
				'resource' => $resource,
				'words' => $words,
				'mSearch2' => $this->mSearch2,
			));
		}
		else {
			$this->modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Could not save search index of resource '.$resource_id.': '.print_r($q->errorInfo(),1));
		}
	}


	/**
	 * Remove index of resource
	 *
	 * @param integer $resource_id
	 */
	public function unIndex($resource_id) {
		$sql = "DELETE FROM {$this->modx->getTableName('mseWord')} WHERE `resource` = '$resource_id';";
		$sql .= "DELETE FROM {$this->modx->getTableName('mseIntro')} WHERE `resource` = '$resource_id';";

		$this->modx->exec($sql);
	}


	/**
	 * Loads mSearch2 class to processor
	 *
	 * @return bool
	 */
	public function loadClass() {
		/** @noinspection PhpUndefinedFieldInspection */
		if (!empty($this->modx->mSearch2) && $this->modx->mSearch2 instanceof mSearch2) {
			/** @noinspection PhpUndefinedFieldInspection */
			$this->mSearch2 = & $this->modx->mSearch2;
		}
		else {
			if (!class_exists('mSearch2')) {
				require_once MODX_CORE_PATH . 'components/msearch2/model/msearch2/msearch2.class.php';
			}
			$this->mSearch2 = new mSearch2($this->modx, array());
		}
		$this->modx->sanitizePatterns['fenom'] = '#\{.*\}#si';

		return $this->mSearch2 instanceof mSearch2;
	}


	protected function _getBaseForms($text) {
		$text = str_ireplace('ё', 'е', $this->modx->stripTags($text));
		$text = preg_replace('#\[.*\]#isU', '', $text);

		$bulk_words = $this->mSearch2->getBulkWords($text, $this->mSearch2->config['split_all'], true);
		$this->mSearch2->loadPhpMorphy();
		/* @var phpMorphy $phpMorphy */
		$base_forms = array();
		foreach ($this->mSearch2->phpMorphy as $phpMorphy) {
			$lang = $phpMorphy->getLocale();
			foreach ($bulk_words as $word => $count) {
				$base = $phpMorphy->getBaseForm(array($word));
				foreach ($base as $forms) {
					if (!$forms) {
						if (!$this->mSearch2->config['index_all']) {
							continue;
						}
						else {
							$forms = array($word);
						}
					}
					$form = current($forms);
					if ($lang == 'en_EN') {
						$form = iconv('UTF-8', 'ASCII//TRANSLIT', $word);
					}
					if (preg_match('/^[0-9]{2,}$/', $form) || mb_strlen($form,'UTF-8') >= $this->mSearch2->config['min_word_length']) {
						if (!isset($base_forms[$form])) {
							$base_forms[$form] = $count;
						}
						else {
							$base_forms[$form] += $count;
						}
					}
				}
			}
		}

		return $base_forms;
	}

	/**
	 * Recursive implode
	 *
	 * @param $glue
	 * @param array $array
	 *
	 * @return string
	 */
	protected function _implode_r($glue, array $array) {
		$result = array();
		foreach ($array as $v) {
			$result[] = is_array($v) ? $this->_implode_r($glue, $v) : $v;
		}

		return implode($glue, $result);
	}

}

return 'mseIndexCreateProcessor';
