<?php

class seoFilterGetCategoriesProcessor extends modObjectGetListProcessor {

    public $classKey = 'msCategory';
    public $defaultSortField = 'id';
    public $defaultSortDirection  = 'ASC';
    protected $item_id = 0;

    /** {@inheritDoc} */
    public function initialize() {
        if ($this->getProperty('combo') && !$this->getProperty('limit') && $id = $this->getProperty('id')) {
            $this->item_id = $id;
        }
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 20,
            'sort' => $this->defaultSortField,
            'dir' => $this->defaultSortDirection,
            'combo' => false,
            'query' => '',
        ));
        return true;
    }
    /** {@inheritDoc} */
    public function process() {
        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();
        $list = $this->iterate($data);
        return $this->outputArray($list,$data['total']);
    }

    /** {@inheritDoc} */
    public function getData() {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        /* query for chunks */
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey,$c);
        $c = $this->prepareQueryAfterCount($c);
        $sortClassKey = $this->getSortClassKey();
        $sortKey = $this->modx->getSelectColumns($sortClassKey,$this->getProperty('sortAlias',$sortClassKey),'',array($this->getProperty('sort')));
        if (empty($sortKey)) $sortKey = $this->getProperty('sort');
        $c->sortby($sortKey,$this->getProperty('dir'));
        if ($limit > 0) {
            $c->limit($limit,$start);
        }
        if ($c->prepare() && $c->stmt->execute()) {
            $data['results'] = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }
    /** {@inheritDoc} */
    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $array) {
            $objectArray = $this->prepareResult($array);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }
    /** {@inheritDoc} */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->select('id,parent,pagetitle,context_key');
        $c->where(array(
            'class_key' => 'msCategory'
        ));
        if ($query = $this->getProperty('query')) {
            $c->where(array(
                array('pagetitle:LIKE' => "%$query%"),
                array('uri:LIKE' => "%$query%")
            ), xPDOQuery::SQL_OR);
        }
        else if ($this->item_id) {
            $c->where(array('id' => $this->item_id));
        }
        return $c;
    }
    /** {@inheritDoc} */
    public function prepareResult(array $resourceArray) {
        $resourceArray['parents'] = array();
        $parents = $this->modx->getParentIds($resourceArray['id'], 2, array('context' => $resourceArray['context_key']));
        if ($parents[count($parents) - 1] == 0) {
            unset($parents[count($parents) - 1]);
        }
        if (!empty($parents) && is_array($parents)) {
            $q = $this->modx->newQuery('msCategory', array('id:IN' => $parents));
            $q->select('id,pagetitle');
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $key = array_search($row['id'], $parents);
                    if ($key !== false) {
                        $parents[$key] = $row;
                    }
                }
            }
            $resourceArray['parents'] = array_reverse($parents);
        }
        return $resourceArray;
    }
}
return 'seoFilterGetCategoriesProcessor';