<?php

/**
 * Create an sfPieceContent
 */
class seoFilterPieceContentCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'sfPieceContent';
	public $classKey = 'sfPieceContent';
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

        $alias = trim($this->getProperty('alias'));
		if (empty($alias)) {
			$this->modx->error->addField('alias', $this->modx->lexicon('seofilter_err_empty'));
		}
		elseif ($this->modx->getCount($this->classKey, array('alias' => $alias, 'resource_id' => $resourceId))) {
			$this->modx->error->addField('resource_id', $this->modx->lexicon('seofilter_err_unique'));
            $this->modx->error->addField('alias', $this->modx->lexicon('seofilter_err_unique'));
		}

		return parent::beforeSet();
	}

}

return 'seoFilterPieceContentCreateProcessor';