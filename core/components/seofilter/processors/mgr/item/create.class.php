<?php
//
///**
// * Create an Item
// */
//class seofilterItemCreateProcessor extends modObjectCreateProcessor {
//	public $objectType = 'seofilterItem';
//	public $classKey = 'seofilterItem';
//	public $languageTopics = array('seofilter');
//	//public $permission = 'create';
//
//
//	/**
//	 * @return bool
//	 */
//	public function beforeSet() {
//		$name = trim($this->getProperty('name'));
//		if (empty($name)) {
//			$this->modx->error->addField('name', $this->modx->lexicon('seofilter_item_err_name'));
//		}
//		elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
//			$this->modx->error->addField('name', $this->modx->lexicon('seofilter_item_err_ae'));
//		}
//
//		return parent::beforeSet();
//	}
//
//}
//
//return 'seofilterItemCreateProcessor';