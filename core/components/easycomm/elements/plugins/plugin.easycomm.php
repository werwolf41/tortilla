<?php
/** @var array $scriptProperties */
switch ($modx->event->name) {
    case 'OnDocFormRender':
        /** @var modResource $resource */
        if ($mode == 'new') {
            return;
        }

        $template = $resource->get('template');
        $showTemplates = trim($modx->getOption('ec_show_templates'));
        $showResources = trim($modx->getOption('ec_show_resources'));
        $showTab = false;
        if($showTemplates == '*' || $showResources == '*') {
            $showTab = true;
        }
        else {
            $showTemplates = array_map('trim', explode(',', $showTemplates));
            $showResources = array_map('trim', explode(',', $showResources));
            if (in_array($template, $showTemplates) || in_array($resource->get('id'), $showResources)) {
                $showTab = true;
            }
        }

        if(!$showTab) {
            return;
        }

        $modx23 = !empty($modx->version) && version_compare($modx->version['full_version'], '2.3.0', '>=');
        $modx->controller->addHtml('<script type="text/javascript">
			Ext.onReady(function() {
				MODx.modx23 = ' . (int)$modx23 . ';
			});
		</script>');


        /** @var easyComm $easyComm */
        $easyComm = $modx->getService('easyComm', 'easyComm', MODX_CORE_PATH.'components/easycomm/model/easycomm/');
        $modx->controller->addLexiconTopic('easycomm:default');
        $url = $easyComm->config['assetsUrl'];
        $modx->controller->addJavascript($url . 'js/mgr/easycomm.js');

        $modx->controller->addLastJavascript($url . 'js/mgr/misc/utils.js');
        $modx->controller->addLastJavascript($url . 'js/mgr/widgets/threads.grid.js');
        $modx->controller->addLastJavascript($url . 'js/mgr/widgets/threads.windows.js');
        $modx->controller->addLastJavascript($url . 'js/mgr/widgets/messages.grid.js');
        $modx->controller->addLastJavascript($url . 'js/mgr/widgets/messages.windows.js');
        $modx->controller->addLastJavascript($url . 'js/mgr/widgets/page.panel.js');

        $modx->controller->addCss($url . 'css/mgr/main.css');

        // TODO: разобраться, почему без этого не работает подключение плагинов
        $modx->newObject('ecMessage');

        $pluginsJS = $easyComm->getPluginsJS();
        if(!empty($pluginsJS)){
            foreach($pluginsJS as $js) {
                $modx->controller->addJavascript($js);
            }
        }

        $defaultReplyAuthor = '';
        if($modx->getOption('ec_auto_reply_author')) {
            $defaultReplyAuthor = addslashes($modx->user->getOne('Profile')->get('fullname'));
        }

        $defaultThread = $modx->getObject('ecThread', array('name' => 'resource-'.$resource->get('id')));
        $defaultThread = $defaultThread ? $defaultThread->get('id') : 'null';

        $ecConfig = '
            easyComm.config.rating_visual_editor = ' . $modx->getOption('ec_rating_visual_editor', null, true ) . ';
            easyComm.config.thread_fields = ' . json_encode($easyComm->getThreadFields()) . ';
            easyComm.config.thread_grid_fields = ' . json_encode($easyComm->getThreadGridFields()) . ';
            easyComm.config.thread_window_fields = ' . json_encode($easyComm->getThreadWindowFields()) . ';
            easyComm.config.message_fields = ' . json_encode($easyComm->getMessageFields()) . ';
            easyComm.config.message_grid_fields = ' . json_encode($easyComm->getMessageGridFields()) . ';
            easyComm.config.message_window_layout = ' . $easyComm->getMessageWindowLayout() . ';
            easyComm.config.default_reply_author = "' . $defaultReplyAuthor . '";
            easyComm.config.default_resource = ' . $resource->get('id') . ';
            easyComm.config.default_thread = ' . $defaultThread . ';
            easyComm.config.default_rating = ' . $modx->getOption('ec_rating_default', null, '""') . ';
        ';

        if ($modx->getCount('modPlugin', array('name' => 'AjaxManager', 'disabled' => false))) {
            $modx->controller->addHtml('
			<script type="text/javascript">
				easyComm.config = ' . $modx->toJSON($easyComm->config) . ';
				easyComm.config.connector_url = "' . $easyComm->config['connectorUrl'] . '";
				'.$ecConfig.'
				Ext.onReady(function() {
					window.setTimeout(function() {
						var tabs = Ext.getCmp("modx-resource-tabs");
						if (tabs) {
							tabs.add({
								xtype: "ec-panel-page",
								id: "ec-panel-page",
								title: _("ec"),
								record: {
									id: ' . $resource->get('id') . '
								}
							});
						}
					}, 10);
				});
			</script>');
        }
        else {
            $modx->controller->addHtml('
			<script type="text/javascript">
				easyComm.config = ' . $modx->toJSON($easyComm->config) . ';
				easyComm.config.connector_url = "' . $easyComm->config['connectorUrl'] . '";
				'.$ecConfig.'
				Ext.ComponentMgr.onAvailable("modx-resource-tabs", function() {
					this.on("beforerender", function() {
						this.add({
							xtype: "ec-panel-page",
							id: "ec-panel-page",
							title: _("ec"),
							record: {
								id: ' . $resource->get('id') . '
							}
						});
					});
					Ext.apply(this, {
							stateful: true,
							stateId: "modx-resource-tabs-state",
							stateEvents: ["tabchange"],
							getState: function() {return {activeTab:this.items.indexOf(this.getActiveTab())};
						}
					});
				});
			</script>');
        }

        break;
}