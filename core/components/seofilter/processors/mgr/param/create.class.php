<?php

/**
 * Create an sfParam
 */
class seoFilterParamCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'sfParam';
	public $classKey = 'sfParam';
	public $languageTopics = array('seofilter');
	//public $permission = 'create';

	/**
	 * @return bool
	 */
	public function beforeSet() {
		$name = trim($this->getProperty('name'));
		if (empty($name)) {
			$this->modx->error->addField('name', $this->modx->lexicon('seofilter_err_empty'));
		}
		elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
			$this->modx->error->addField('name', $this->modx->lexicon('seofilter_err_unique'));
		}

		return parent::beforeSet();
	}

}

return 'seoFilterParamCreateProcessor';