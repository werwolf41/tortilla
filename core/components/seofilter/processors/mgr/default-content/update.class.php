<?php

/**
 * Update an sfDefaultContent
 */
class seoFilterDefaultContentUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'sfDefaultContent';
	public $classKey = 'sfDefaultContent';
	public $languageTopics = array('seofilter');
	//public $permission = 'save';

    private $resource;

	/**
	 * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return bool|string
	 */
	public function beforeSave() {
		if (!$this->checkPermissions()) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$id = (int)$this->getProperty('id');
		if (empty($id)) {
			return $this->modx->lexicon('seofilter_err_ns');
		}

        $resourceId = $this->getProperty('resource_id');
        if (!$this->resource = $this->modx->getObject('modResource', $resourceId)) {
            $this->modx->error->addField('resource_id', $this->modx->lexicon('seofilter_err_empty'));
        }

        if ($this->modx->getCount($this->classKey, array('resource_id' => $resourceId, 'id:!=' => $id))) {
            $this->modx->error->addField('resource_id', $this->modx->lexicon('seofilter_err_unique'));
        }

		return parent::beforeSet();
	}
}

return 'seoFilterDefaultContentUpdateProcessor';
