<?php
/** @var array $scriptProperties */
/** @var pdoFetch $pdoFetch */
$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
if (!$pdoClass = $modx->loadClass($fqn, '', false, true)) {return false;}
$pdoFetch = new $pdoClass($modx, $scriptProperties);
$pdoFetch->addTime('pdoTools loaded.');

/** @var array $scriptProperties */
/** @var seoFilter $seoFilter */
if (!$seoFilter = $modx->getService('seofilter', 'seofilter', $modx->getOption('seofilter_core_path', null, $modx->getOption('core_path') . 'components/seofilter/') . 'model/seofilter/', $scriptProperties)) {
	return 'Could not load seoFilter class!';
}

$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, "\n");
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

if (isset($_REQUEST['limit']) && is_numeric($_REQUEST['limit']) && abs($_REQUEST['limit']) > 0) {$limit = abs($_REQUEST['limit']);}
elseif ($limit == '') {$limit = 10;}




// Output
$output = '';
//$output = implode($outputSeparator, $list);
if (!empty($toPlaceholder)) {
	// If using a placeholder, output nothing and set output to specified placeholder
	$modx->setPlaceholder($toPlaceholder, $output);

	return '';
}
// By default just return output
return $output;
