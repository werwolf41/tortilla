<?php
/** @var array $scriptProperties */
/** @var pdoTools $pdoTools */
$pdoTools = $modx->getService('pdoTools');
$pdoTools->setConfig($scriptProperties);
$pdoTools->addTime('pdoTools loaded.');

/** @var mSearch2 $mSearch2 */
if (!$modx->loadClass('msearch2', MODX_CORE_PATH . 'components/msearch2/model/msearch2/', false, true)) {return false;}
$mSearch2 = new mSearch2($modx);
$mSearch2->initialize($modx->context->key);

$config = array(
	'autocomplete' => !empty($autocomplete) ? $autocomplete : '',
	'queryVar' => !empty($queryVar) ? $queryVar : 'query',
	'minQuery' => !empty($minQuery) ? (integer) $minQuery : 3,
	'pageId' => !empty($pageId) ? (integer) $pageId : $modx->resource->id,
);
$scriptProperties = array_merge($scriptProperties, $config);

if (empty($tplForm)) {$tplForm = 'tpl.mSearch2.form';}
$form = $pdoTools->getChunk($tplForm, $scriptProperties);

if (!empty($config['autocomplete'])) {
	$hash = sha1(serialize($scriptProperties));
	$_SESSION['mSearch2'][$hash] = $scriptProperties;

	$form = str_ireplace('<form', '<form data-key="'.$hash.'"', $form);
	// Place for enabled log
	if ($modx->user->hasSessionContext('mgr') && !empty($showLog)) {
		$form = str_ireplace('</form>', "</form>\n<pre class=\"mSearchFormLog\"></pre>", $form);
	}

	// Setting values for frontend javascript
	$main_config = array(
		'cssUrl' => $mSearch2->config['cssUrl'].'web/',
		'jsUrl' => $mSearch2->config['jsUrl'].'web/',
		'actionUrl' => $mSearch2->config['actionUrl'],
	);

	$modx->regClientStartupScript('
	<script type="text/javascript">
		if (typeof mse2Config == "undefined") {mse2Config = ' . $modx->toJSON($main_config) . ';}
		if (typeof mse2FormConfig == "undefined") {mse2FormConfig = {};}
		mse2FormConfig["' . $hash . '"] = ' . $modx->toJSON($config) . ';
	</script>', true);
	$modx->regClientScript('
	<script type="text/javascript">
		if ($("form.msearch2").length) {
			mSearch2.Form.initialize("form.msearch2");
		}
	</script>', true);
}

return $form;