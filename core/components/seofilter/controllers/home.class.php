<?php

/**
 * The home manager controller for seofilter.
 *
 */
class seoFilterHomeManagerController extends seoFilterMainController {
	/* @var seoFilter $seoFilter */
	public $seoFilter;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('seofilter');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addCss($this->seoFilter->config['cssUrl'] . 'mgr/main.css');
		$this->addCss($this->seoFilter->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
		$this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/misc/combos.js');
		$this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/params.grid.js');
		$this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/params.windows.js');
        $this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/pieces.grid.js');
        $this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/pieces.windows.js');
        $this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/pieces-content.grid.js');
        $this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/pieces-content.windows.js');
        $this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/default-content.grid.js');
        $this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/default-content.windows.js');
		$this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "seofilter-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->seoFilter->config['templatesPath'] . 'home.tpl';
	}
}