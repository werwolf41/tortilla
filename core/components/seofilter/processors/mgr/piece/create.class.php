<?php

/**
 * Create an sfPiece
 */
class seoFilterPieceCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'sfPiece';
	public $classKey = 'sfPiece';
	public $languageTopics = array('seofilter');
	//public $permission = 'create';

    private $param;

	/**
	 * @return bool
	 */
	public function beforeSet() {
        $paramId = $this->getProperty('param');
        if (!$this->param = $this->modx->getObject('sfParam', $paramId)) {
            $this->modx->error->addField('param', $this->modx->lexicon('seofilter_err_empty'));
        }

		$value = trim($this->getProperty('value'));
        $alias = trim($this->getProperty('alias'));
        $alias = trim($alias, "/");
		if (empty($value)) {
			$this->modx->error->addField('value', $this->modx->lexicon('seofilter_err_empty'));
		}
		elseif (!empty($alias) && $this->modx->getCount($this->classKey, array('alias' => $alias))) {
			$this->modx->error->addField('alias', $this->modx->lexicon('seofilter_err_unique'));
		}

        $this->setProperty('alias', $alias);

		return parent::beforeSet();
	}

}

return 'seoFilterPieceCreateProcessor';