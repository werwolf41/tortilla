<?php
/** @var array $scriptProperties */
/** @var easyComm $easyComm */
if (!$easyComm = $modx->getService('easyComm', 'easyComm', $modx->getOption('ec_core_path', null, $modx->getOption('core_path') . 'components/easycomm/') . 'model/easycomm/', $scriptProperties)) {
    return 'Could not load easyComm class!';
}
$easyComm->initialize($modx->context->key, $scriptProperties);

$fqn = $modx->getOption('pdoTools.class', null, 'pdotools.pdotools', true);
if ($pdoClass = $modx->loadClass($fqn, '', false, true)) {
    $pdoTools = new $pdoClass($modx, $scriptProperties);
}
elseif ($pdoClass = $modx->loadClass($fqn, MODX_CORE_PATH . 'components/pdotools/model/', false, true)) {
    $pdoTools = new $pdoClass($modx, $scriptProperties);
}
else {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load pdoTools from "MODX_CORE_PATH/components/pdotools/model/".');
    return false;
}

$tplForm = $modx->getOption('tplForm', $scriptProperties, 'tpl.ecForm');
$threadName = $modx->getOption('thread', $scriptProperties, '');
if(empty($threadName)) {
    $threadName = 'resource-'.$modx->resource->get('id');
    $scriptProperties['thread'] = $threadName;
}
$formId = $modx->getOption('formId', $scriptProperties, '');
if(empty($formId)) {
    $formId = $threadName;
    $scriptProperties['formId'] = $formId;
}

// Prepare ecThread
/** @var ecThread $thread */
if (!$thread = $modx->getObject('ecThread', array('name' => $threadName))) {
    $thread = $modx->newObject('ecThread');
    $thread->fromArray(array(
        'resource' => $modx->resource->id,
        'name' => $threadName,
        'title' => $modx->getOption('threadTitle', $scriptProperties, ''),
    ));
}
$thread->set('properties', $scriptProperties);
$thread->save();

$data = array(
    'fid' => $formId,
    'thread' => $thread->get('name'),
    'antispam_field' => $modx->getOption('antispamField', $scriptProperties)
);

if ($modx->user->hasSessionContext($modx->context->get('key'))) {
    $profile = $modx->user->getOne('Profile');
    $data['user_name'] = $profile->get('fullname');
    if(empty($data['user_name'])) {
        $data['user_name'] = $modx->user->get('username');
    }
    $data['user_email'] = $profile->get('email');
}

if($modx->getOption('ec_captcha_enable')) {
    $tplFormReCaptcha = $modx->getOption('tplFormReCaptcha', $scriptProperties, 'tpl.ecForm.ReCaptcha');
    $data['recaptcha'] = $pdoTools->getChunk($tplFormReCaptcha, $data);
}

return $pdoTools->getChunk($tplForm, $data);