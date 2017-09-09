<?php
/** @var array $scriptProperties */
/** @var easyComm $easyComm */
if (!$easyComm = $modx->getService('easyComm', 'easyComm', $modx->getOption('ec_core_path', null, $modx->getOption('core_path') . 'components/easycomm/') . 'model/easycomm/', $scriptProperties)) {
    return 'Could not load easyComm class!';
}

/* @var string $threads */
$threads = $modx->getOption('threads', $scriptProperties, '');
if($threads == '*') {
    $threads = array();
}
else {
    if(empty($threads)) {
        /* @var string $thread */
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

$q = $modx->newQuery($class);
$q->innerJoin($threadClass, 'Thread', "`$class`.`thread` = `Thread`.`id`");

if(count($threads) == 1) {
    $q->where(array(
        '`Thread`.`name`' => $threads[0]
    ));
}
if(count($threads) > 1) {
    $q->where(array(
        '`Thread`.`name`:IN' => $threads
    ));
}
if(empty($showUnpublished)) {
    $q->where(array(
        $class.'.published' => 1
    ));
}
if(empty($showDeleted)) {
    $q->where(array(
        $class.'.deleted' => 0
    ));
}
if(!empty($subject)) {
    $q->where(array(
        $class.'.subject' => $subject
    ));
}

return $modx->getCount($class, $q);