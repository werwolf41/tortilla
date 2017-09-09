<?php

class msOrderStatusOfficeGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'msOrderStatus';
    public $defaultSortField = 'rank';
    public $defaultSortDirection = 'asc';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->select('id,name');
        $c->where(array('active' => 1));

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = array(
            'id' => $object->get('id'),
            'name' => $this->modx->lexicon(str_replace(array('[[%', ']]'), '', $object->get('name'))),
        );

        return $array;
    }


    /**
     * @param array $array
     * @param bool $count
     *
     * @return string
     */
    public function outputArray(array $array, $count = false)
    {
        $array = array_merge_recursive(array(
            array(
                'id' => 0,
                'name' => $this->modx->lexicon('office_ms2_all'),
            ),
        ), $array);

        return parent::outputArray($array, $count);
    }

}

return 'msOrderStatusOfficeGetListProcessor';