<?php

class mseAliasGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'mseAlias';
	public $classKey = 'mseAlias';
	public $defaultSortField = 'word';


	/** {@inheritDoc} */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		if ($query = $this->getProperty('query')) {
			$c->where(array('word:LIKE' => "%$query%", 'OR:alias:LIKE' => "%$query%"));
		}

		return $c;
	}

}

return 'mseAliasGetListProcessor';