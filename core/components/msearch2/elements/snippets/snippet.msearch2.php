<?php
/** @var array $scriptProperties */
/** @var pdoFetch $pdoFetch */
$pdoFetch = $modx->getService('pdoFetch');
$pdoFetch->setConfig($scriptProperties);
$pdoFetch->addTime('pdoTools loaded.');
/** @var mSearch2 $mSearch2 */
if (!$modx->loadClass('msearch2', MODX_CORE_PATH . 'components/msearch2/model/msearch2/', false, true)) {return false;}
$mSearch2 = new mSearch2($modx, $scriptProperties);

if (empty($queryVar)) {$queryVar = 'query';}
if (empty($parentsVar)) {$parentsVar = 'parents';}
if (empty($minQuery)) {$minQuery = $modx->getOption('index_min_words_length', null, 3, true);}
if (empty($htagOpen)) {$htagOpen = '<b>';}
if (empty($htagClose)) {$htagClose = '</b>';}
if (empty($outputSeparator)) {$outputSeparator = "\n";}
if (empty($plPrefix)) {$plPrefix = 'mse2_';}
$returnIds = !empty($returnIds);
$fastMode = !empty($fastMode);

$class = 'modResource';
$found = array();
$output = null;
$query = !empty($_REQUEST[$queryVar])
	? $mSearch2->getQuery($_REQUEST[$queryVar])
	: '';

if (empty($resources)) {
	if (empty($query) && isset($_REQUEST[$queryVar])) {
		$output = $modx->lexicon('mse2_err_no_query');
	}
	elseif (empty($query) && !empty($forceSearch)) {
		$output = $modx->lexicon('mse2_err_no_query_var');
	}
	elseif (!empty($query) && !preg_match('/^[0-9]{2,}$/', $query) && mb_strlen($query,'UTF-8') < $minQuery) {
		$output = $modx->lexicon('mse2_err_min_query');
	}

	$modx->setPlaceholder($plPrefix.$queryVar, $query);

	if (!empty($output)) {
		return !$returnIds
			? $output
			: '';
	}
	elseif (!empty($query)) {
		$found = $mSearch2->Search($query);
		$ids = array_keys($found);
		$resources = implode(',', $ids);
		if (empty($found)) {
			if ($returnIds) {
				return '';
			}
			elseif (!empty($query)) {
				$output = $modx->lexicon('mse2_err_no_results');
			}
			if (!empty($tplWrapper) && !empty($wrapIfEmpty)) {
				$output = $pdoFetch->getChunk(
					$tplWrapper,
					array(
						'output' => $output,
						'total' => 0,
						'query' => $query,
						'parents' => $modx->getPlaceholder($plPrefix.$parentsVar),
					),
					$fastMode
				);
			}
			if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
				$output .= '<pre class="mSearchLog">' . print_r($pdoFetch->getTime(), 1) . '</pre>';
			}
			if (!empty($toPlaceholder)) {
				$modx->setPlaceholder($toPlaceholder, $output);
				return;
			}
			else {
				return $output;
			}
		}
	}
}
elseif (strpos($resources, '{') === 0) {
	$found = $modx->fromJSON($resources);
	$resources = implode(',', array_keys($found));
	unset($scriptProperties['resources']);
}
/*----------------------------------------------------------------------------------*/
// Joining tables
$leftJoin = array(
	'mseIntro' => array(
		'class' => 'mseIntro',
		'alias' => 'Intro',
		'on' => $class . '.id = Intro.resource'
	)
);
// Fields to select
$resourceColumns = !empty($includeContent)
	? $modx->getSelectColumns($class, $class)
	: $modx->getSelectColumns($class, $class, '', array('content'), true);
$select = array(
	$class => $resourceColumns,
	'Intro' => 'intro'
);

// Add custom parameters
foreach (array('leftJoin','select') as $v) {
	if (!empty($scriptProperties[$v])) {
		$tmp = $modx->fromJSON($scriptProperties[$v]);
		if (is_array($tmp)) {
			$$v = array_merge($$v, $tmp);
		}
	}
	unset($scriptProperties[$v]);
}

// Default parameters
$default = array(
	'class' => $class,
	'leftJoin' => $modx->toJSON($leftJoin),
	'select' => $modx->toJSON($select),
	'groupby' => $class.'.id, Intro.intro',
	'fastMode' => $fastMode,
	'return' => !empty($returnIds)
		? 'ids'
		: 'data',
	'nestedChunkPrefix' => 'msearch2_',
);
if (!empty($resources)) {
	$default['resources'] = is_array($resources)
		? implode(',', $resources)
		: $resources;
}

// Merge all properties and run!
$pdoFetch->setConfig(array_merge($default, $scriptProperties), false);
$pdoFetch->addTime('Query parameters are prepared.');
$rows = $pdoFetch->run();

$log = '';
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
	$log .= '<pre class="mSearchLog">' . print_r($pdoFetch->getTime(), 1) . '</pre>';
}

// Processing results
if (!empty($returnIds)) {
	$modx->setPlaceholder('mSearch.log', $log);
	if (!empty($toPlaceholder)) {
		$modx->setPlaceholder($toPlaceholder, $rows);
		return '';
	}
	else {
		return $rows;
	}
}
elseif (!empty($rows) && is_array($rows)) {
	$output = array();
	foreach ($rows as $k => $row) {
		// Processing main fields
		$row['weight'] = isset($found[$row['id']]) ? $found[$row['id']] : '';
		$row['intro'] = $mSearch2->Highlight($row['intro'], $query, $htagOpen, $htagClose);

		$row['idx'] = $pdoFetch->idx++;
		$tplRow = $pdoFetch->defineChunk($row);
		$output[] .= empty($tplRow)
			? $pdoFetch->getChunk('', $row)
			: $pdoFetch->getChunk($tplRow, $row, $fastMode);
	}
	$pdoFetch->addTime('Returning processed chunks');
	if (!empty($toSeparatePlaceholders)) {
		$output['log'] = $log;
		$modx->setPlaceholders($output, $toSeparatePlaceholders);
	}
	else {
		$output = implode($outputSeparator, $output);
	}
}
else {
	$output = $modx->lexicon('mse2_err_no_results');
}

// Return output
if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
	$output = $pdoFetch->getChunk(
		$tplWrapper,
		array(
			'output' => $output . $log,
			'total' => $modx->getPlaceholder($pdoFetch->config['totalVar']),
			'query' => $modx->getPlaceholder($plPrefix.$queryVar),
			'parents' => $modx->getPlaceholder($plPrefix.$parentsVar),
		),
		$fastMode
	);
}

if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
}
else {
	return $output;
}