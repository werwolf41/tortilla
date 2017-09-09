<?php

/**
 * The home manager controller for easyComm.
 *
 */
class easyCommHomeManagerController extends easyCommMainController {
	/* @var easyComm $easyComm */
	public $easyComm;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('easycomm');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addCss($this->easyComm->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->easyComm->config['jsUrl'] . 'mgr/misc/utils.js');
		$this->addJavascript($this->easyComm->config['jsUrl'] . 'mgr/widgets/threads.grid.js');
		$this->addJavascript($this->easyComm->config['jsUrl'] . 'mgr/widgets/threads.windows.js');
        $this->addJavascript($this->easyComm->config['jsUrl'] . 'mgr/widgets/messages.grid.js');
        $this->addJavascript($this->easyComm->config['jsUrl'] . 'mgr/widgets/messages.windows.js');
		$this->addJavascript($this->easyComm->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->easyComm->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "ec-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->easyComm->config['templatesPath'] . 'home.tpl';
	}
}