<?php

/**
 * Remove an sfPieceContent
 */
class seoFilterPieceContentRemoveProcessor extends modObjectProcessor {
	public $objectType = 'sfPieceContent';
	public $classKey = 'sfPieceContent';
	public $languageTopics = array('seofilter');
	//public $permission = 'remove';


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('seofilter_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var sfPieceContent $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('seofilter_err_nf'));
			}

			$object->remove();
		}

		return $this->success();
	}

}

return 'seoFilterPieceContentRemoveProcessor';