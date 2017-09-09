<?php

class mseQueryGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'mseQuery';
	public $classKey = 'mseQuery';
	public $defaultSortField = 'found';

	/** {@inheritDoc} */
	public function prepareQueryBeforeCount(xPDOQuery $c) {

		if (strtolower($this->getProperty('sort')) == 'found' && strtolower($this->getProperty('dir')) == 'asc') {
			$this->setProperty('dir', 'DESC');
			$this->setProperty('sort', 'found ASC, quantity');
		}

		if ($query = $this->getProperty('query')) {
			$c->where(array('query:LIKE' => "%$query%"));
		}

		return $c;
	}

}

return 'mseQueryGetListProcessor';