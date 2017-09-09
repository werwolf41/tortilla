<?php

if (empty($_REQUEST['action'])) {
    die('Access denied');
}
else {
    $action = $_REQUEST['action'];
}

define('MODX_API_MODE', true);


// Load MODX
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))).'/index.php')) {
    require_once dirname(dirname(dirname(dirname(__FILE__)))).'/index.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/index.php';
}

$modx->getService('error','error.modError');
$modx->getRequest();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->message = null;


// Get properties
$properties = array();

$modx->addPackage('easycomm',$modx->getOption('ec_core_path', null, $modx->getOption('core_path') . 'components/easycomm/').'model/');

/* @var ecThread $thread */
if (!empty($_REQUEST['thread']) && $thread = $modx->getObject('ecThread', array('name' => $_REQUEST['thread']))) {
    $properties = $thread->get('properties');

    /* @var modResource $resource */
    if ($resource = $thread->getOne('Resource')) {
        if ($resource->get('context_key') != 'web') {
            $modx->switchContext($resource->get('context_key'));
        }
        // $modx->resource = $resource;
    }
}

/* @var easyComm $easyComm */
define('MODX_ACTION_MODE', true);
$easyComm = $modx->getService('easyComm','easyComm',$modx->getOption('ec_core_path',null,$modx->getOption('core_path').'components/easycomm/').'model/easycomm/', $properties);
if ($modx->error->hasError() || !($easyComm instanceof easyComm)) {
    die('Error');
}

switch ($action) {
    case 'message/create':
        $response = $easyComm->createMessage($_POST);
        break;
    default:
        $response = $modx->toJSON(array('success' => false, 'message' => $modx->lexicon('ec_unknown_action')));
}

if (is_array($response)) {
    $response = $modx->toJSON($response);
}
@session_write_close();
exit($response);