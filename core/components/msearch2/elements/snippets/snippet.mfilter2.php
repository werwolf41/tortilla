<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var pdoFetch $pdoFetch */
$pdoFetch = $modx->getService('pdoFetch');
$pdoFetch->setConfig($scriptProperties);
$pdoFetch->addTime('pdoTools loaded.');
/** @var mSearch2 $mSearch2 */
if (!$modx->loadClass('msearch2', MODX_CORE_PATH . 'components/msearch2/model/msearch2/', false, true)) {return false;}
$mSearch2 = new mSearch2($modx, $scriptProperties);
$mSearch2->initialize($modx->context->key);
$savedProperties = array();

if (empty($queryVar)) {$queryVar = 'query';}
if (empty($parentsVar)) {$parentsVar = 'parents';}
if (empty($minQuery)) {$minQuery = $modx->getOption('index_min_words_length', null, 3, true);}
if (empty($classActive)) {$classActive = 'active';}
if (isset($scriptProperties['disableSuggestions'])) {$scriptProperties['suggestions'] = empty($scriptProperties['disableSuggestions']);}
if (empty($toPlaceholders) && !empty($toPlaceholder)) {$toPlaceholders = $toPlaceholder;}
if (empty($plPrefix)) {$plPrefix = 'mse2_';}
if (isset($_REQUEST['limit']) && is_numeric($_REQUEST['limit']) && abs($_REQUEST['limit']) > 0) {$limit = abs($_REQUEST['limit']);}
elseif (!isset($limit) || $limit === '') {$limit = 10;}
if (!isset($outputSeparator)) {$outputSeparator = "\n";}
$fastMode = !empty($fastMode);
// All templates of filters are converted to lowercase
foreach ($scriptProperties as $k => $v) {
	if (strpos($k, 'tplFilter') === 0) {
		$tmp = 'tplFilter.' . strtolower(substr($k, 10));
		if ($tmp != $k) {
			unset($scriptProperties[$k]);
			$scriptProperties[$tmp] = $v;
		}
	}
}

$class = 'modResource';
$output = array('filters' => array(), 'results' => array(), 'total' => 0, 'limit' => $limit);
$ids = $found = $log = $where = array();

// ---------------------- Retrieving ids of resources for filter
$query = !empty($_REQUEST[$queryVar])
	? $mSearch2->getQuery($_REQUEST[$queryVar])
	: '';

// Filter by ids
if (!empty($resources)) {
	$ids = array_map('trim', explode(',', $resources));
}
elseif (isset($_REQUEST[$queryVar]) && empty($query)) {
	$output['results'] =  $modx->lexicon('mse2_err_no_query');
}
elseif (empty($query) && !empty($forceSearch)) {
	$output['results'] = $modx->lexicon('mse2_err_no_query_var');
}
elseif (isset($_REQUEST[$queryVar]) && !preg_match('/^[0-9]{2,}$/', $query) && mb_strlen($query,'UTF-8') < $minQuery) {
	$output['results'] = $modx->lexicon('mse2_err_min_query');
}
elseif (isset($_REQUEST[$queryVar])) {
	$modx->setPlaceholder($plPrefix.$queryVar, $query);

	$found = $mSearch2->Search($query);
	$ids = array_keys($found);

	if (!empty($ids)) {
		$tmp = $scriptProperties;
		$tmp['returnIds'] = 1;
		$tmp['resources'] = implode(',', $ids);
		$tmp['parents'] = $scriptProperties['parents'];
		$tmp['limit'] = 0;
		$ids = explode(',', $modx->runSnippet($scriptProperties['element'], $tmp));
		$pdoFetch->addTime('Found ids: "'.implode(',',$ids).'"');
	}

	if (empty($ids[0])) {
		$output['results'] = $modx->lexicon('mse2_err_no_results');
	}
}

$modx->setPlaceholder($plPrefix.$queryVar, $query);

// Has error message - exit
if (!empty($output['results'])) {
	$log = '';
	if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
		$log = '<pre class="mFilterLog">' . print_r($pdoFetch->getTime(), 1) . '</pre>';
	}
	if (!empty($toSeparatePlaceholders)) {
		$output['log'] = $log;
		$modx->setPlaceholders($output, $toSeparatePlaceholders);
		return;
	}
	elseif (!empty($toPlaceholders)) {
		$output['log'] = $log;
		$modx->setPlaceholders($output, $toPlaceholders);
		return;
	}
	else {
		$output = $pdoFetch->getChunk($scriptProperties['tplOuter'], $output, $fastMode);
		$output .= $log;
		return $output;
	}
}

// ---------------------- Checking resources by status and custom "where" parameter
// Support for specifying property set in the element name
$elementName = $scriptProperties['element'];
$elementSet = array();
if (strpos($elementName, '@') !== false) {
	list($elementName, $elementSet) = explode('@', $elementName);
}
/** @var modSnippet $snippet */
if (!empty($elementName) && $element = $modx->getObject('modSnippet', array('name' => $elementName))) {
	$elementProperties = $element->getProperties();
	$elementPropertySet = !empty($elementSet)
		? $element->getPropertySet($elementSet)
		: array();
	if (!is_array($elementPropertySet)) {$elementPropertySet = array();}
	$params = array_merge(
		$elementProperties,
		$elementPropertySet,
		$scriptProperties,
		array(
			'parents' => empty($scriptProperties[$parentsVar]) && !empty($_REQUEST[$parentsVar])
				? $_REQUEST[$parentsVar]
				: $scriptProperties[$parentsVar],
			'returnIds' => 1,
			'limit' => 0,
		)
	);
	if (!empty($ids)) {
		$params['resources'] = implode(',', $ids);
	}
	$element->setCacheable(false);
	$tmp = $element->process($params);
	if (!empty($tmp)) {
		$tmp = explode(',', $tmp);
		$ids = !empty($ids)
			? array_intersect($ids, $tmp)
			: $tmp;
	}
	$pdoFetch->addTime('Fetched ids for building filters: "'.implode(',',$ids).'" from element "'.$elementName.'"');
}
else {
	$modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Could not find main snippet with name: "'.$elementName.'"');
	return '';
}

// ---------------------- Nothing to filter, exit
if (empty($ids)) {
	if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
		$log = '<pre class="mFilterLog">' . print_r($pdoFetch->getTime(), 1) . '</pre>';
	}
	else {$log = '';}

	$output = array_merge($output, array(
		'filters' => $modx->lexicon('mse2_err_no_filters'),
		'results' => $modx->lexicon('mse2_err_no_results'),
		'log' => $log
	));

	if (!empty($toSeparatePlaceholders)) {
		$modx->setPlaceholders($output, $toSeparatePlaceholders);
		return;
	}
	elseif (!empty($toPlaceholders)) {
		$modx->setPlaceholders($output, $toPlaceholders);
		return;
	}
	else {
		$output['results'] .= $log;
		return $pdoFetch->getChunk($scriptProperties['tplOuter'], $output, $fastMode);
	}
}

// ---------------------- Checking for suggestions processing
// Checking by results count
if (!empty($scriptProperties['suggestionsMaxResults']) && count($ids) > $scriptProperties['suggestionsMaxResults']) {
	$scriptProperties['suggestions'] = false;
	$pdoFetch->addTime('Suggestions disabled by "suggestionsMaxResults" parameter: results count is '.count($ids).', max allowed is '.$scriptProperties['suggestionsMaxResults']);
}
else {
	$pdoFetch->addTime('Total number of results: '.count($ids));
}

// Then get filters
$pdoFetch->addTime('Getting filters for ids: "'.implode(',',$ids).'"');
$filters = ''; $count = 0;
if (!empty($ids)) {
	$filters = $mSearch2->getFilters($ids);
	// And checking by filters count
	if (!empty($filters) && $scriptProperties['suggestions']) {
		foreach ($filters as $tmp) {
			if (!is_array($tmp)) {continue;}
			$count += count(array_values($tmp));
		}
		if (!empty($scriptProperties['suggestionsMaxFilters']) && $count > $scriptProperties['suggestionsMaxFilters']) {
			$scriptProperties['suggestions'] = false;
			$pdoFetch->addTime('Suggestions disabled by "suggestionsMaxFilters" parameter: filters count is '.$count.', max allowed is '.$scriptProperties['suggestionsMaxFilters']);
		}
		else {
			$pdoFetch->addTime('Total number of filters: '.$count);
		}
	}
}
$modx->setPlaceholder($plPrefix . 'filters_count', $count );


// ---------------------- Loading results
$start_sort = implode(',', array_map('trim' , explode(',', $scriptProperties['sort'])));
$start_limit = 0;
$suggestions = array();
$page = $sort = '';

// Support for specifying property set in the paginator name
$paginatorName = $scriptProperties['paginator'];
$paginatorSet = array();
if (strpos($paginatorName, '@') !== false) {
	list($paginatorName, $paginatorSet) = explode('@', $paginatorName);
}
if (!empty($ids)) {
	/* @var modSnippet $paginator */
	if ($paginator = $modx->getObject('modSnippet', array('name' => $paginatorName))) {
		$paginatorProperties = $paginator->getProperties();
		$paginatorPropertySet = !empty($paginatorSet)
			? $paginator->getPropertySet($paginatorSet)
			: array();
		if (!is_array($paginatorPropertySet)) {$paginatorPropertySet = array();}
		$paginatorProperties = array_merge(
			$paginatorProperties,
			$paginatorPropertySet,
			$elementPropertySet,
			$scriptProperties,
			array(
				'resources' => implode(',', $ids),
				'parents' => '0',
				'element' => $elementName,
				'defaultSort' => $start_sort,
				'toPlaceholder' => false,
				'limit' => $limit,
				'ajaxMode' => '',
				'ajax' => 0,
				'frontend_js' => '',
				'frontend_css' => '',
			)
		);

		// Switching chunk for rows, if specified
		if (!empty($scriptProperties['tpls'])) {
			$tmp = isset($_REQUEST['tpl']) ? (integer) $_REQUEST['tpl'] : 0;
			$tpls = array_map('trim', explode(',', $scriptProperties['tpls']));
			$paginatorProperties['tpls'] = $tpls;
			if (isset($tpls[$tmp])) {
				$paginatorProperties['tpl'] = $tpls[$tmp];
				$paginatorProperties['tpl_idx'] = $tmp;
			}
		}

		// Trying to save weight of found ids if using mSearch2
		$weight = false;
		if (!empty($found) && strtolower($elementName) == 'msearch2') {
			$tmp = array();
			foreach ($ids as $v) {
				$tmp[$v] = isset($found[$v])
					? $found[$v]
					: 0;
			}
			$paginatorProperties['resources'] = $modx->toJSON($tmp);
			$weight = true;
		}

		if (!empty($_REQUEST['sort'])) {$sort = $_REQUEST['sort'];}
		elseif (!empty($start_sort)) {$sort = $start_sort;}
		else {
			/*
			$sortby = !empty($scriptProperties['sortby']) ? $scriptProperties['sortby'] : '';
			if (!empty($sortby)) {
				$sortdir = !empty($scriptProperties['sortdir']) ? $scriptProperties['sortdir'] : 'asc';
				$sort = $sortby.$mSearch2->config['method_delimeter'].$sortdir;
			}
			*/
		}

		if (!empty($_REQUEST[$paginatorProperties['pageVarKey']])) {
			$page = (int) $_REQUEST[$paginatorProperties['pageVarKey']];
		}
		if (!empty($sort)) {
			$paginatorProperties['sortby'] = $mSearch2->getSortFields($sort);
			$paginatorProperties['sortdir'] = '';
		}

		$start_limit = !empty($scriptProperties['limit'])
				? $scriptProperties['limit']
				: $paginatorProperties['limit'];
		$paginatorProperties['start_limit'] = $start_limit;
		$savedProperties['paginatorProperties'] = $paginatorProperties;

		// We have a delimeters in $_GET, so need to filter resources
		if (strpos(implode(array_keys($_GET)), $mSearch2->config['filter_delimeter']) !== false || !empty($mSearch2->aliases)) {
			$matched = $mSearch2->Filter($ids, $_REQUEST);
			$matched = array_intersect($ids, $matched);
			if ($scriptProperties['suggestions']) {
				$suggestions = $mSearch2->getSuggestions($ids, $_REQUEST, $matched);
				$pdoFetch->addTime('Suggestions retrieved.');
			}
			// Trying to save weight of found ids again
			if ($weight) {
				$tmp = array();
				foreach ($matched as $v) {$tmp[$v] = isset($found[$v]) ? $found[$v] : 0;}
				$paginatorProperties['resources'] = $modx->toJSON($tmp);
			}
			else {
				$paginatorProperties['resources'] = implode(',', $matched);
			}
		}
		// Saving log
		$log = $pdoFetch->timings;
		$pdoFetch->timings = array();

		//$paginator->setProperties($paginatorProperties);
		$paginator->setCacheable(false);
		$output['results'] = !empty($paginatorProperties['resources'])
			? $paginator->process($paginatorProperties)
			: $modx->lexicon('mse2_err_no_results');
		$output['total'] = $modx->getPlaceholder($paginatorProperties['totalVar']);
	}
	else {
		$modx->log(modX::LOG_LEVEL_ERROR, '[mSearch2] Could not find pagination snippet with name: "'.$paginatorName.'"');
		return '';
	}
}

// ----------------------  Loading filters
$pdoFetch->timings = $log;
if (!empty($paginator)) {
	$pdoFetch->addTime('Fired paginator: "'.$paginatorName.'"');
}
else {
	$pdoFetch->addTime('Could not find pagination snippet with name: "'.$paginatorName.'"');
}
if (empty($filters)) {
	$pdoFetch->addTime('No filters retrieved');
	$output['filters'] = $modx->lexicon('mse2_err_no_filters');
	if (empty($output['results'])) {$output['results'] = $modx->lexicon('mse2_err_no_results');}
}
else {
	$pdoFetch->addTime('Filters retrieved');
	$request = array();
	foreach ($_GET as $k => $v) {
		$tmp = explode($mSearch2->config['values_delimeter'], $v);
		$request[$k] = array();
		foreach ($tmp as $v2) {
			$request[$k][] = str_replace('"', '&quot;', $v2);
		}
	}

	$aliases = $mSearch2->aliases;
	foreach ($filters as $filter => $data) {
		if (empty($data) || !is_array($data)) {
			continue;
		}
		$rows = $has_active = '';
		list($table, $method) = explode($mSearch2->config['filter_delimeter'], $filter);
		$filter_key = !empty($aliases[$filter])
			? $aliases[$filter]
			: $filter;

		$tplOuter = !empty($scriptProperties['tplFilter.outer.' . $filter_key])
			? $scriptProperties['tplFilter.outer.' . $filter_key]
			: $scriptProperties['tplFilter.outer.default'];
		$tplRow = !empty($scriptProperties['tplFilter.row.' . $filter_key])
			? $scriptProperties['tplFilter.row.' . $filter_key]
			: $scriptProperties['tplFilter.row.default'];
		$tplEmpty = !empty($scriptProperties['tplFilter.empty.' . $filter_key])
			? $scriptProperties['tplFilter.empty.' . $filter_key]
			: '';

		$idx = 0;
		foreach ($data as $v) {
			if (empty($v)) {continue;}
			$checked = isset($request[$filter_key]) && in_array((string)$v['value'], $request[$filter_key], true) && isset($v['type']) && $v['type'] != 'number';
			if ($scriptProperties['suggestions']) {
				if ($checked) {
					$num = '';
					$has_active = 'has_active';
				}
				elseif (isset($suggestions[$filter_key][$v['value']])) {
					$num = $suggestions[$filter_key][$v['value']];
				}
				else {
					$num = !empty($v['resources'])
						? count($v['resources'])
						: '';
				}
			}
			else {
				$num = '';
			}

			$rows .= $pdoFetch->getChunk($tplRow, array(
				'filter' => $method,
				'table' => $table,
				'title' => $v['title'],
				'value' => $v['value'],
				'type' => $v['type'],
				'checked' => $checked
					? 'checked'
					: '',
				'selected' => $checked
					? 'selected'
					: '',
				'disabled' => !$checked && empty($num) && $scriptProperties['suggestions']
					? 'disabled'
					: '',
				'delimeter' => $mSearch2->config['filter_delimeter'],
				'idx' => $idx++,
				'num' => $num,
				'filter_key' => $filter_key,
			), $fastMode);
		}

		$tpl = empty($rows) ? $tplEmpty : $tplOuter;
		if (!isset($output['filters'][$filter])) {
			$output['filters'][$filter] = '';
		}
		$output['filters'][$filter] = $pdoFetch->getChunk($tpl, array(
			'filter' => $method,
			'table' => $table,
			'rows' => $rows,
			'has_active' => $has_active,
			'delimeter' => $mSearch2->config['filter_delimeter'],
			'filter_key' => $filter_key,
		), $fastMode);
	}

	if (empty($output['filters'])) {
		$output['filters'] = $modx->lexicon('mse2_err_no_filters');
		if (empty($output['results'])) {$output['results'] = $modx->lexicon('mse2_err_no_results');}
	}
	else {
		$pdoFetch->addTime('Filters templated');
	}
}
$pdoFetch->addTime('Total filter operations: '.$mSearch2->filter_operations);

// Saving params into cache for ajax requests
$savedProperties['scriptProperties'] = $scriptProperties;
$hash = sha1(serialize($savedProperties));
$_SESSION['mSearch2'][$hash] = $savedProperties;

// Active class for sort links
if (!empty($sort)) {$output[$sort] = $classActive;}
if (isset($paginatorProperties['tpl_idx'])) {
	$output['tpl'.$paginatorProperties['tpl_idx']] = $classActive;
	$output['tpls'] = 1;
}

// Setting values for frontend javascript
$config = array(
	'cssUrl' => $mSearch2->config['cssUrl'].'web/',
	'jsUrl' => $mSearch2->config['jsUrl'].'web/',
	'actionUrl' => $mSearch2->config['actionUrl'],
	'queryVar' => $mSearch2->config['queryVar'],
	'idVar' => $modx->getOption('request_param_id', null, 'id'),
	'filter_delimeter' => $mSearch2->config['filter_delimeter'],
	'method_delimeter' => $mSearch2->config['method_delimeter'],
	'values_delimeter' => $mSearch2->config['values_delimeter'],
	'start_sort' => $start_sort,
	'start_limit' => $start_limit,
	'start_page' => 1,
	'start_tpl' => '',
	'sort' => $sort == $start_sort ? '' : $sort,
	'limit' => $limit == $start_limit ? '' : $limit,
	'page' => $page,
	'pageVar' => $paginatorProperties['pageVarKey'],
	'tpl' => !empty($paginatorProperties['tpl_idx'])
			? $paginatorProperties['tpl_idx']
			: '',
	'parentsVar' => $parentsVar,
	'key' => $hash,
	'pageId' => !empty($pageId) ? (integer) $pageId : $modx->resource->id,
	$queryVar => isset($_REQUEST[$queryVar]) ? $_REQUEST[$queryVar] : '',
	$parentsVar => isset($_REQUEST[$parentsVar]) ? $_REQUEST[$parentsVar] : '',
	'aliases' => array_flip($mSearch2->aliases),
	'options' => array(),
	'mode' => in_array($scriptProperties['ajaxMode'], array('button', 'scroll')) ? $scriptProperties['ajaxMode'] : '',
	'moreText' => $modx->lexicon('mse2_more'),
);
if (!empty($scriptProperties['filterOptions'])) {
	$filterOptions = $modx->fromJSON($scriptProperties['filterOptions']);
	if (is_array($filterOptions)) {
		$config['filterOptions'] = $filterOptions;
	}
}
if (empty($noJsConfig)) {
$modx->regClientStartupScript('
<script type="text/javascript">mse2Config = ' . json_encode($config) . ';</script>', true);
}
if (empty($noJsInitialize)) {
$modx->regClientScript('
<script type="text/javascript">
    if ($("#mse2_mfilter").length) {
        if (window.location.hash != "" && mSearch2.Hash.oldbrowser()) {
            var uri = window.location.hash.replace("#", "?");
            window.location.href = document.location.pathname + uri;
        }
        else {
            mSearch2.initialize("body");
        }
    }
    </script>', true);
}
$modx->setPlaceholders($config, $plPrefix);

// Prepare output
$log = '';
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
	$log = '<pre class="mFilterLog">' . print_r($pdoFetch->getTime(), 1) . '</pre>';
}

if (!empty($toSeparatePlaceholders)) {
	$modx->setPlaceholders($output['filters'], $toSeparatePlaceholders);
	$output['log'] = $log;
	if (is_array($output['filters'])) {
		$output['filters'] = implode($outputSeparator, $output['filters']);
	}

	$pcre = '#^' . preg_quote($toSeparatePlaceholders) . '(\d+)$#';
	$tmp = array();
	foreach ($modx->placeholders as $k => $v) {
		if (preg_match($pcre, $k)) {
			$tmp[] = $v;
		}
	}

	$output['results'] = !empty($tmp)
		? implode($outputSeparator, $tmp)
		: $modx->lexicon('mse2_err_no_results');

	$modx->setPlaceholders($output, $toSeparatePlaceholders);
}
else {
	if (is_array($output['filters'])) {
		$output['filters'] = implode($outputSeparator, $output['filters']);
	}
	if (!empty($toPlaceholders)) {
		$output['log'] = $log;
		$modx->setPlaceholders($output, $toPlaceholders);
	}
	else {
		$output = $pdoFetch->getChunk($scriptProperties['tplOuter'], $output, $fastMode);
		$output .= $log;

		return $output;
	}
}