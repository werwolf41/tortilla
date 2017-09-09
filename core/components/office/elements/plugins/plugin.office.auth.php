<?php
/** @var modX $modx */
switch ($modx->event->name) {

    case 'OnHandleRequest':
        $actions = array('auth/login', 'auth/logout', 'remote/login', 'remote/logout');

        if (!empty($_REQUEST['action']) && in_array(rawurldecode($_REQUEST['action']), $actions)) {
            $params = array();
            foreach ($_REQUEST as $k => $v) {
                $params[$k] = rawurldecode($v);
            }

            list($controller, $action) = explode('/', $params['action']);
            $cfg = !empty($_SESSION['Office'][ucfirst($controller)][$modx->context->key])
                ? $_SESSION['Office'][ucfirst($controller)][$modx->context->key]
                : array();

            /** @var Office $Office */
            $Office = $modx->getService('office', 'Office', MODX_CORE_PATH . 'components/office/model/office/', $cfg);
            if ($Office) {
                $Office->loadAction($params['action'], array_merge($params, $cfg));
            }
        } elseif ($modx->context->key != 'web' && !$modx->user->id) {
            if ($user = $modx->getAuthenticatedUser($modx->context->key)) {
                $modx->user = $user;
                $modx->getUser($modx->context->key);
            }
        }

        if (!empty($_SESSION['Office']['ReturnTo'][$modx->context->key]) && $modx->user->isAuthenticated($modx->context->key)) {
            $return = $_SESSION['Office']['ReturnTo'][$modx->context->key];
            unset($_SESSION['Office']['ReturnTo'][$modx->context->key]);
            $modx->sendRedirect($return);
        }
        break;

    case 'OnWebAuthentication':
        $modx->event->_output = !empty($_SESSION['Office']['Auth']['verified']);
        break;

    case 'OnUserSave':
        if (!empty($user) && !empty($mode) && $mode == 'new') {
            if (!$user->get('remote_key')) {
                $user->set('remote_key', $user->get('id'));
                $user->save();
            }
        }
        break;
}