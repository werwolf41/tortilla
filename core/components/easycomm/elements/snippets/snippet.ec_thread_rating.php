<?php
/** @var array $scriptProperties */
/** @var easyComm $easyComm */
if (!$easyComm = $modx->getService('easyComm', 'easyComm', $modx->getOption('ec_core_path', null, $modx->getOption('core_path') . 'components/easycomm/') . 'model/easycomm/', $scriptProperties)) {
    return 'Could not load easyComm class!';
}

/* @var string $thread */
$thread = $modx->getOption('thread', $scriptProperties, '');
if(empty($thread)) {
    $thread = 'resource-'.$modx->resource->get('id');
}

/* @var MODx $modx */
/* @var ecThread $thread */
$thread = $modx->getObject('ecThread', array('name' => $thread));
if(empty($thread)) {
    return;
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

$fastMode = !empty($fastMode);

$ratingMax = (float)$modx->getOption('ec_rating_max', $scriptProperties, 5);
$data = array_merge(
    $thread->toArray(),
    array(
        'rating_wilson_percent' => number_format($thread->get('rating_wilson') / $ratingMax * 100, 3),
        'rating_simple_percent' => number_format($thread->get('rating_simple') / $ratingMax * 100, 3),
    )
);

$output = $pdoFetch->getChunk($tpl, $data, $fastMode);

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);
}
else {
    return $output;
}
