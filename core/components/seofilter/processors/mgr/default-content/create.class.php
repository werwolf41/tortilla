<?php

/**
 * Create an sfDefaultContent
 */
class seoFilterDefaultContentCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'sfDefaultContent';
	public $classKey = 'sfDefaultContent';
	public $languageTopics = array('seofilter');
	//public $permission = 'create';

    private $resource;

	/**
	 * @return bool
	 */
	public function beforeSet() {
        $resourceId = $this->getProperty('resource_id');
        if (!$this->resource = $this->modx->getObject('modResource', $resourceId)) {
            $this->modx->error->addField('resource_id', $this->modx->lexicon('seofilter_err_empty'));
        }

        if($this->modx->getCount($this->classKey, array('resource_id' => $resourceId))) {
			$this->modx->error->addField('resource_id', $this->modx->lexicon('seofilter_err_unique'));
		}

		return parent::beforeSet();
	}

}

return 'seoFilterDefaultContentCreateProcessor';