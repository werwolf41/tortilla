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
$modx->addPackage('seofilter',$modx->getOption('seofilter_core_path', null, $modx->getOption('core_path') . 'components/seofilter/').'model/');

/* @var seoFilter $seoFilter */
define('MODX_ACTION_MODE', true);
$seoFilter = $modx->getService('seoFilter','seoFilter',$modx->getOption('seofilter_core_path',null,$modx->getOption('core_path').'components/seofilter/').'model/seofilter/', $properties);
if ($modx->error->hasError() || !($seoFilter instanceof seoFilter)) {
    die('Error');
}
switch ($action) {
    case 'category/get_content':
        $response = $seoFilter->getCategoryContentAjax($_POST);
        break;
    default:
        $response = $modx->toJSON(array('success' => false, 'message' => $modx->lexicon('seofilter_unknown_action')));
}
if (is_array($response)) {
    $response = $modx->toJSON($response);
}
@session_write_close();
exit($response);