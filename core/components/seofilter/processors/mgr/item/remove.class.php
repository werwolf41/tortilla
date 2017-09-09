<?php
//
///**
// * Remove an Items
// */
//class seofilterItemRemoveProcessor extends modObjectProcessor {
//	public $objectType = 'seofilterItem';
//	public $classKey = 'seofilterItem';
//	public $languageTopics = array('seofilter');
//	//public $permission = 'remove';
//
//
//	/**
//	 * @return array|string
//	 */
//	public function process() {
//		if (!$this->checkPermissions()) {
//			return $this->failure($this->modx->lexicon('access_denied'));
//		}
//
//		$ids = $this->modx->fromJSON($this->getProperty('ids'));
//		if (empty($ids)) {
//			return $this->failure($this->modx->lexicon('seofilter_item_err_ns'));
//		}
//
//		foreach ($ids as $id) {
//			/** @var seofilterItem $object */
//			if (!$object = $this->modx->getObject($this->classKey, $id)) {
//				return $this->failure($this->modx->lexicon('seofilter_item_err_nf'));
//			}
//
//			$object->remove();
//		}
//
//		return $this->success();
//	}
//
//}
//
//return 'seofilterItemRemoveProcessor';