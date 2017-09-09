<?php
/** @noinspection PhpIncludeInspection */

// Load MODX config
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var easyComm $easyComm */
$easyComm = $modx->getService('easyComm', 'easyComm', $modx->getOption('easycomm_core_path', null, $modx->getOption('core_path') . 'components/easycomm/') . 'model/easycomm/');
$modx->lexicon->load('easycomm:default');

// handle request
$corePath = $modx->getOption('easycomm_core_path', null, $modx->getOption('core_path') . 'components/easycomm/');
$path = $modx->getOption('processorsPath', $easyComm->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));

