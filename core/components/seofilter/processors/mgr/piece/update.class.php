<?php

/**
 * Update an sfPiece
 */
class seoFilterPieceUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'sfPiece';
	public $classKey = 'sfPiece';
	public $languageTopics = array('seofilter');
	//public $permission = 'save';

    private $param;

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

        $paramId = $this->getProperty('param');
        if (!$this->param = $this->modx->getObject('sfParam', $paramId)) {
            $this->modx->error->addField('param', $this->modx->lexicon('seofilter_err_empty'));
        }

        $value = trim($this->getProperty('value'));
        $alias = trim($this->getProperty('alias'));
        if (empty($value)) {
            $this->modx->error->addField('value', $this->modx->lexicon('seofilter_err_empty'));
        }
        elseif (!empty($alias) && $this->modx->getCount($this->classKey, array('alias' => $alias, 'id:!=' => $id))) {
            $this->modx->error->addField('alias', $this->modx->lexicon('seofilter_err_unique'));
        }

		return parent::beforeSet();
	}
}

return 'seoFilterPieceUpdateProcessor';
