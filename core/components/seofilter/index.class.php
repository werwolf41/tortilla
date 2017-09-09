<?php

/**
 * Class seoFilterMainController
 */
abstract class seoFilterMainController extends modExtraManagerController {
	/** @var seoFilter $seoFilter */
	public $seoFilter;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('seofilter_core_path', null, $this->modx->getOption('core_path') . 'components/seofilter/');
		require_once $corePath . 'model/seofilter/seofilter.class.php';

		$this->seoFilter = new seoFilter($this->modx);
		//$this->addCss($this->seoFilter->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->seoFilter->config['jsUrl'] . 'mgr/seofilter.js');
		$this->addHtml('
		<script type="text/javascript">
			seoFilter.config = ' . $this->modx->toJSON($this->seoFilter->config) . ';
			seoFilter.config.connector_url = "' . $this->seoFilter->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('seofilter:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends seoFilterMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}