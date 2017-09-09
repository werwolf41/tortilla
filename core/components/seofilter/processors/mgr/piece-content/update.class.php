<?php

/**
 * Update an sfPieceContent
 */
class seoFilterPieceContentUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'sfPieceContent';
	public $classKey = 'sfPieceContent';
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

        $alias = trim($this->getProperty('alias'));
        $alias = trim($alias, "/");
        if (empty($alias)) {
            $this->modx->error->addField('alias', $this->modx->lexicon('seofilter_err_empty'));
        }
        elseif ($this->modx->getCount($this->classKey, array('alias' => $alias, 'resource_id' => $resourceId, 'id:!=' => $id))) {
            $this->modx->error->addField('resource_id', $this->modx->lexicon('seofilter_err_unique'));
            $this->modx->error->addField('alias', $this->modx->lexicon('seofilter_err_unique'));
        }

        $this->setProperty('alias', $alias);

		return parent::beforeSet();
	}
}

return 'seoFilterPieceContentUpdateProcessor';
