<?php

class mseQueryRemoveAllProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'mseQuery';
	public $classKey = 'mseQuery';
	public $languageTopics = array('msearch2');


	/** {@inheritDoc} */
	public function initialize() {
		if (!$this->checkRemovePermission) {
			return $this->modx->lexicon('access_denied');
		}
		return true;
	}


	/** {@inheritDoc} */
	public function process() {
		$canRemove = $this->beforeRemove();
		if ($canRemove !== true) {
			return $this->failure($canRemove);
		}
		$preventRemoval = $this->fireBeforeRemoveEvent();
		if (!empty($preventRemoval)) {
			return $this->failure($preventRemoval);
		}

		$this->modx->exec("TRUNCATE TABLE {$this->modx->getTableName($this->classKey)}");

		$this->logManagerAction();
		$this->cleanup();
		return $this->success('');
	}


	/** {@inheritDoc} */
	public function logManagerAction() {
		$this->modx->logManagerAction($this->objectType.'_truncate',$this->classKey, 0);
	}


}

return 'mseQueryRemoveAllProcessor';