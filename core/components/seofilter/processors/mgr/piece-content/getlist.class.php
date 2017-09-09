<?php

/**
 * Get a list of sfPieceContent
 */
class seoFilterPieceContentGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'sfPieceContent';
	public $classKey = 'sfPieceContent';
	public $defaultSortField = 'id';
	public $defaultSortDirection = 'DESC';
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
        $c->leftJoin('modResource','Resource','`sfPieceContent`.`resource_id` = `Resource`.`id`');
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey, ''));
        $c->select('`Resource`.`pagetitle` as `resource`, `Resource`.`uri` as `uri`');

        $filter = trim($this->getProperty('filter'));
        if ($filter) {
            $c->where(array(
                'resource_id' => $filter
            ));
        }

		$query = trim($this->getProperty('query'));
		if ($query) {
			$c->where(array(
				'alias:LIKE' => "%{$query}%",
                'OR:pagetitle:LIKE' => "%{$query}%",
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
        $array['resource'] = $array['resource'].' <span style="color:#999">('.$array['resource_id'].')</span><br /><small>'.$array['uri'].'</small>';

        $array['actions'] = array();

		// Edit
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-edit',
			'title' => $this->modx->lexicon('seofilter_piece_content_update'),
			//'multiple' => $this->modx->lexicon('seofilter_pieces_content_update'),
			'action' => 'updatePieceContent',
			'button' => true,
			'menu' => true,
		);

		// Remove
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-trash-o action-red',
			'title' => $this->modx->lexicon('seofilter_piece_content_remove'),
			'multiple' => $this->modx->lexicon('seofilter_pieces_content_remove'),
			'action' => 'removePieceContent',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'seoFilterPieceContentGetListProcessor';