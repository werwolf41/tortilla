<?php
/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnHandleRequest':
        if (!empty($_GET['action']) && $_GET['action'] == 'office/login_as') {
            $authorized = $modx->user->getSessionContexts();
            if (!empty($authorized['mgr']) && $modx->context->key != 'mgr') {
                if ((int)$_GET['user_id'] && $user = $modx->getObject('modUser', (int)$_GET['user_id'])) {
                    $modx->user = $user;
                    $contexts = $modx->getIterator('modContext', array('key:!=' => 'mgr'));
                    /** @var modContext $context */
                    foreach ($contexts as $context) {
                        $modx->user->addSessionContext($context->key);
                        $modx->getUser($context->key, true);
                    }
                }
            }
            $url = preg_replace('#\?.*#', '', $_SERVER['REQUEST_URI']);
            unset($_GET['action'], $_GET['user_id'], $_GET[$modx->getOption('request_param_alias')]);
            if (!empty($_GET)) {
                $url .= '?' . http_build_query($_GET);
            }
            if (empty($url)) {
                $url = $modx->makeUrl($modx->getOption('site_start'));
            }
            $modx->sendRedirect($url);
        }
        break;
    case 'OnManagerPageBeforeRender':
        /** @var modManagerController $controller */
        switch ($controller->config['controller']) {
            case 'security/user':
                $controller->addLexiconTopic('office:default');
                $controller->addLastJavascript(MODX_ASSETS_URL . 'components/office/js/main/mgr/users.js');
                break;
            case 'security/user/update':
                $controller->addLexiconTopic('office:default');
                $controller->addLastJavascript(MODX_ASSETS_URL . 'components/office/js/main/mgr/user.js');
                break;
        }
        break;
}