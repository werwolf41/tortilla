<?php
/**
 * Get a list of sfParam for combobox
 */
class seoFilterParamGetComboListProcessor extends modObjectGetListProcessor {
    public $objectType = 'sfParam';
    public $classKey = 'sfParam';
    public $defaultSortField = 'name';
    public $defaultSortDirection = 'ASC';

    //public $permission = 'list';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        if ($this->getProperty('combo')) {
            $c->select('id, name');
        }
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%'.$query.'%'
            ));
        }
        return $c;
    }
    /** {@inheritDoc} */
    public function prepareRow(xPDOObject $object) {
        if ($this->getProperty('combo')) {
            $array = array(
                'id' => $object->get('id'),
                'name' => $object->get('name').' ('.$object->get('id').')',
            );
        }
        else {
            $array = $object->toArray();
        }
        return $array;
    }
}
return 'seoFilterParamGetComboListProcessor';