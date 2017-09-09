<?php

/**
 * Get a list of sfPiece
 */
class seoFilterPieceGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'sfPiece';
	public $classKey = 'sfPiece';
	public $defaultSortField = 'value';
	public $defaultSortDirection = 'ASC';
	//public $permission = 'list';

    private $findUnused = false;
    /** @var sfParam $param */
    private $param;

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
        $c->leftJoin('sfParam','Param','`sfPiece`.`param` = `Param`.`id`');
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey, ''));
        $c->select('`Param`.`name` as `param_name`');

        $paramId = trim($this->getProperty('filter'));
        if ($paramId) {
            $c->where(array(
                'param' => $paramId
            ));
            $this->findUnused = true;
            $this->param = $this->modx->getObject('sfParam', $paramId);
        }

		$query = trim($this->getProperty('query'));
		if ($query) {
			$c->where(array(
				'value:LIKE' => "%{$query}%",
                'OR:alias:LIKE' => "%{$query}%",
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
        $array['use_count'] = '-';

        if($this->findUnused) {
            $paramType = $this->param->get('type');
            if($paramType == 'field') {
                $paramName = $this->param->get('name');
                $q = $this->modx->newQuery('msProductData');
                $q->where(array(
                    $paramName => $array['value']
                ));
                $array['use_count'] = $this->modx->getCount('msProductData', $q);
            }
            else if($paramType == 'field_json') {
                $paramName = $this->param->get('name');
                $q = $this->modx->newQuery('msProductOption');
                $q->select('COUNT(*) as count');
                $q->where(array(
                    'key' => $paramName,
                    'value' => $array['value']
                ));
                $q->prepare();
                $q->stmt->execute();
                $result = $q->stmt->fetch(PDO::FETCH_ASSOC);
                $array['use_count'] = $result['count'];
            }
            else if($paramType == 'vendor') {
                $q = $this->modx->newQuery('msProductData');
                $q->where(array(
                    'vendor' => $array['value']
                ));
                $array['use_count'] = $this->modx->getCount('msProductData', $q);
            }
        }



		$array['actions'] = array();

		// Edit
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-edit',
			'title' => $this->modx->lexicon('seofilter_piece_update'),
			//'multiple' => $this->modx->lexicon('seofilter_pieces_update'),
			'action' => 'updatePiece',
			'button' => true,
			'menu' => true,
		);

		// Remove
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-trash-o action-red',
			'title' => $this->modx->lexicon('seofilter_piece_remove'),
			'multiple' => $this->modx->lexicon('seofilter_pieces_remove'),
			'action' => 'removePiece',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'seoFilterPieceGetListProcessor';