<?php
/** @var array $scriptProperties */
/** @var easyComm $easyComm */
if (!$easyComm = $modx->getService('easyComm', 'easyComm', $modx->getOption('ec_core_path', null, $modx->getOption('core_path') . 'components/easycomm/') . 'model/easycomm/', $scriptProperties)) {
    return 'Could not load easyComm class!';
}

/* @var pdoFetch $pdoFetch */
$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
if ($pdoClass = $modx->loadClass($fqn, '', false, true)) {
    $pdoFetch = new $pdoClass($modx, $scriptProperties);
}
elseif ($pdoClass = $modx->loadClass($fqn, MODX_CORE_PATH . 'components/pdotools/model/', false, true)) {
    $pdoFetch = new $pdoClass($modx, $scriptProperties);
}
else {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load pdoFetch from "MODX_CORE_PATH/components/pdotools/model/".');
    return false;
}
$pdoFetch->addTime('pdoTools loaded');
$fastMode = !empty($fastMode);
$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, "\n");

/* @var string $threads */
$threads = $modx->getOption('threads', $scriptProperties, '');
if($threads == '*') {
    $threads = array();
}
else {
    if(empty($threads)) {
        $threads = $modx->getOption('thread', $scriptProperties, '');
        if(empty($threads)) {
            $threads = 'resource-'.$modx->resource->get('id');
        }
    }
    $threads = explode(",", $threads);
    $threads = array_map('trim', $threads);
}

$class = 'ecMessage';
$threadClass = 'ecThread';
$resourceClass = 'modResource';
// Query conditions
$select = array(
    $class => $modx->getSelectColumns($class, $class),
    $threadClass => $modx->getSelectColumns($threadClass, 'Thread', 'thread_'),
);
$innerJoin = array($threadClass => array('alias' => 'Thread', 'on' => "`$class`.`thread` = `Thread`.`id`"));
$leftJoin = array();

if(!empty($resourceFields)) {
    $resourceFields = array_merge(array('id', 'pagetitle'), explode(",", $resourceFields));
    $resourceFields = array_map("trim", $resourceFields);

    $select[$resourceClass] = $modx->getSelectColumns($resourceClass, 'Resource', 'resource_', $resourceFields);
    $leftJoin[$resourceClass] = array('alias' => 'Resource', 'on' => "`Thread`.`resource` = `Resource`.`id`");
}


$where = array();
if(count($threads) == 1) {
    $where['`Thread`.`name`'] = $threads[0];
}
if(count($threads) > 1) {
    $where['`Thread`.`name`:IN'] = $threads;
}

// Filter by message ids
$messages = $modx->getOption('messages', $scriptProperties, '');
if(!empty($messages)) {
    $messages = explode(",", $messages);
    $messages = array_map("trim", $messages);
    $messages = array_map("intval", $messages);
    if(!empty($messages)) {
        $where[$class.'.`id`:IN'] = $messages;
    }
}

if(empty($showUnpublished)) {
    $where[$class.'.published'] = 1;
}

if(empty($showDeleted)) {
    $where[$class.'.deleted'] = 0;
}

if(!empty($subject)) {
    $where[$class.'.subject'] = $subject;
}

// Add custom parameters
foreach (array('where','leftJoin','innerJoin','select','groupby') as $v) {
    if (!empty($scriptProperties[$v])) {
        $tmp = $modx->fromJSON($scriptProperties[$v]);
        if (is_array($tmp)) {
            $$v = array_merge($$v, $tmp);
        }
    }
    unset($scriptProperties[$v]);
}
$pdoFetch->addTime('Conditions prepared');

// Default parameters
$default = array(
    'class' => $class,
    //'loadModels' => 'easyComm',
    'disableConditions' => true,
    'where' => $modx->toJSON($where),
    'select' => $modx->toJSON($select),
    'innerJoin' => $modx->toJSON($innerJoin),
    'leftJoin' => $modx->toJSON($leftJoin),
    //'groupby' => $class.'.id',
    'return' => 'data',
    'nestedChunkPrefix' => 'ec_'
);


// Merge all properties and run!
$pdoFetch->addTime('Query parameters ready');
$pdoFetch->setConfig(array_merge($default, $scriptProperties), false);


$rows = $pdoFetch->run();

/* @var $tmpChunk modChunk */
$tmpChunk = $modx->newObject('modChunk', array('name' => "tmp-".uniqid()));
$tmpChunk->setCacheable(false);
$gravatarDefault = $tmpChunk->process(null, $modx->getOption('ec_gravatar_default'));

$gravatarSize = $modx->getOption('ec_gravatar_size', null, 50);

$output = array();
$idx = $pdoFetch->idx;
foreach($rows as $row) {
    $row['idx'] = $idx++;
    $row['text_raw'] = $row['text'];
    $row['text'] = nl2br($row['text']);
    $row['reply_text_raw'] = $row['reply_text'];
    $row['reply_text'] = nl2br($row['reply_text']);

    $row['gravatar'] = $gravatarDefault;
    if(!empty($row['user_email'])) {
        $row['gravatar'] = 'https://www.gravatar.com/avatar/'.md5(strtolower($row['user_email'])).'?s=50';
        if(!empty($gravatarDefault)) {
            $row['gravatar'] .= '&d='.urlencode($gravatarDefault);
        }
    }

    $tpl = $pdoFetch->defineChunk($row);
    if (empty($tpl)) {
        $output[] = '<pre>'.$pdoFetch->getChunk('', $row).'</pre>';
    }
    else {
        $output[] = $pdoFetch->getChunk($tpl, $row, $fastMode);
    }
}
$log = '';
if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
    $log .= '<pre class="pdoResourcesLog">' . print_r($pdoFetch->getTime(), 1) . '</pre>';
}

// Return output
if (!empty($toSeparatePlaceholders)) {
    $output['log'] = $log;
    $modx->setPlaceholders($output, $toSeparatePlaceholders);
}
else {
    if(empty($output) && !empty($tplEmpty)) {
        $output = $pdoFetch->getChunk($tplEmpty);
    }
    else {
        $output = implode($outputSeparator, $output);
    }
    $output .= $log;
    if (!empty($tplWrapper) && (!empty($wrapIfEmpty) || !empty($output))) {
        $data = array('output' => $output);
        if( (count($threads) == 1) && ($threadObj = $modx->getObject('ecThread', array('name' => $threads[0]))) ) {
            $ratingMax = (float)$modx->getOption('ec_rating_max', $scriptProperties, 5);
            $data = array_merge($data, $threadObj->toArray(),
                array(
                    'rating_wilson_percent' => number_format($threadObj->get('rating_wilson') / $ratingMax * 100, 3),
                    'rating_simple_percent' => number_format($threadObj->get('rating_simple') / $ratingMax * 100, 3),
                )
            );
        }
        $output = $pdoFetch->getChunk($tplWrapper, $data, $pdoFetch->config['fastMode']);
    }
    if (!empty($toPlaceholder)) {
        $modx->setPlaceholder($toPlaceholder, $output);
    }
    else {
        return $output;
    }
}