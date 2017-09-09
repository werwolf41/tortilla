<?php
/**
 * Create an Item
 */
class mseAliasUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'mseAlias';
	public $classKey = 'mseAlias';
	public $languageTopics = array('msearch2');
	public $permission = '';


	/** {@inheritDoc} */
	public function beforeSet() {

		foreach (array('word','alias') as $v) {
			$tmp = mb_strtolower(trim($this->getProperty($v)), 'UTF-8');
			if (empty($tmp)) {
				$this->addFieldError($v, $this->modx->lexicon('mse2_alias_err_rq'));
			}
			else {
				$this->setProperty($v, $tmp);
			}
		}

		if ($this->hasErrors()) {return false;}

		$key = array('word' => $this->getProperty('word'), 'alias' => $this->getProperty('alias'));

		if ($key['word'] == $key['alias']) {
			return $this->modx->lexicon('mse2_alias_err_eq');
		}

		$key['id:!='] = $this->object->id;
		$alreadyExists = $this->modx->getObject($this->classKey, $key);
		if ($alreadyExists) {
			return $this->modx->lexicon('mse2_alias_err_ae', $key);
		}

		return !$this->hasErrors();
	}

}

return 'mseAliasUpdateProcessor';