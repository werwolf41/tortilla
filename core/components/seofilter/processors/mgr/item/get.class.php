<?php
//
///**
// * Get an Item
// */
//class seofilterItemGetProcessor extends modObjectGetProcessor {
//	public $objectType = 'seofilterItem';
//	public $classKey = 'seofilterItem';
//	public $languageTopics = array('seofilter:default');
//	//public $permission = 'view';
//
//
//	/**
//	 * We doing special check of permission
//	 * because of our objects is not an instances of modAccessibleObject
//	 *
//	 * @return mixed
//	 */
//	public function process() {
//		if (!$this->checkPermissions()) {
//			return $this->failure($this->modx->lexicon('access_denied'));
//		}
//
//		return parent::process();
//	}
//
//}
//
//return 'seofilterItemGetProcessor';