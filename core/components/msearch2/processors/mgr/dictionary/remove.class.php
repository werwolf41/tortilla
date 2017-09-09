<?php

class mseDictionaryRemoveProcessor extends modProcessor {

	/** {@inheritDoc} */
	public function process() {
		$dicts = $this->modx->mSearch2->getDictionaries();

		$dictionary = strtolower($this->getProperty('dictionary'));
		if (isset($dicts[$dictionary])) {
			foreach ($dicts[$dictionary] as $file) {
				@unlink($this->modx->mSearch2->config['dictsPath'] . $file);
			}
		}

		return $this->success();
	}

}

return 'mseDictionaryRemoveProcessor';