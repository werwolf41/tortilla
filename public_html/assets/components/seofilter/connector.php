<?php

// Load MODX config
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';
$corePath = $modx->getOption('seofilter_core_path', null, $modx->getOption('core_path') . 'components/seofilter/');
require_once $corePath . 'model/seofilter/seofilter.class.php';
$modx->seoFilter = new seoFilter($modx);
$modx->lexicon->load('seofilter:default');
/* handle request */
$path = $modx->getOption('processorsPath', $modx->seoFilter->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));