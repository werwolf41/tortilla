<?php

/**
 * Get a list of sfParams
 */
class seoFilterParamGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'sfParam';
	public $classKey = 'sfParam';
	public $defaultSortField = 'name';
	public $defaultSortDirection = 'ASC';
	//public $permission = 'list';


	/**
	 * * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return boolean|string
	 */
	public function beforeQuery() {
		if (!$this->checkPermissions()) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$query = trim($this->getProperty('query'));
		if ($query) {
			$c->where(array(
				'name:LIKE' => "%{$query}%",
			));
		}

		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray();
		$array['actions'] = array();

		// Edit
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-edit',
			'title' => $this->modx->lexicon('seofilter_param_update'),
			//'multiple' => $this->modx->lexicon('seofilter_params_update'),
			'action' => 'updateParam',
			'button' => true,
			'menu' => true,
		);

		// Remove
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-trash-o action-red',
			'title' => $this->modx->lexicon('seofilter_param_remove'),
			'multiple' => $this->modx->lexicon('seofilter_params_remove'),
			'action' => 'removeParam',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'seoFilterParamGetListProcessor';