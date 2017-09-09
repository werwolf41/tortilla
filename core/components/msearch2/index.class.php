<?php
/**
 * The main manager controller for mSearch2.
 *
 * @package msearch2
 */

require_once dirname(__FILE__) . '/model/msearch2/msearch2.class.php';

abstract class mSearch2MainController extends modExtraManagerController {
	/** @var mSearch2 $mSearch2 */
	public $mSearch2;


	public function initialize() {
		$version = $this->modx->getVersionData();
		$modx23 = !empty($version) && version_compare($version['full_version'], '2.3.0', '>=');
		if (!$modx23) {
			$this->addCss(MODX_ASSETS_URL . 'components/msearch2/css/mgr/font-awesome.min.css');
		}

		$this->mSearch2 = new mSearch2($this->modx);
		$this->addCSS($this->mSearch2->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->mSearch2->config['jsUrl'] . 'mgr/msearch2.js');
		$this->addHtml('<script type="text/javascript">
			MODx.modx23 = ' . (int)$modx23 . ';
			mSearch2.config = ' . $this->modx->toJSON($this->mSearch2->config) . ';
			mSearch2.config.connector_url = "' . $this->mSearch2->config['connectorUrl'] . '";
			Ext.onReady(function() {
				MODx.load({xtype: "msearch2-page-home"});
			});
		</script>');

		parent::initialize();
	}


	public function getLanguageTopics() {
		return array('msearch2:default');
	}


	public function checkPermissions() {
		return true;
	}
}


class IndexManagerController extends mSearch2MainController {
	public static function getDefaultController() {
		return 'home';
	}
}