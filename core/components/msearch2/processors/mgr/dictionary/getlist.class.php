<?php

class mseDictionaryGetListProcessor extends modObjectGetListProcessor {

	/** {@inheritDoc} */
	public function process() {
		$dicts = $this->modx->mSearch2->getDictionaries();
		$all_dicts = array(
			'ru_RU' => $this->modx->lexicon('russian'),
			'en_EN' => $this->modx->lexicon('english'),
			'de_DE' => $this->modx->lexicon('german'),
			'uk_UA' => $this->modx->lexicon('ukrainian'),
			'et_EE' => $this->modx->lexicon('estonian'),
		);

		$list = array();
		foreach ($all_dicts as $k => $v) {
			$list[] = array(
				'dictionary' => $k,
				'language' => $this->modx->lexicon('mse2_dictionary_' . $v),
				'installed' => isset($dicts[strtolower($k)]) && count($dicts[strtolower($k)]) == 7
			);
		}

		return $this->outputArray($list, count($list));
	}

}

return 'mseDictionaryGetListProcessor';