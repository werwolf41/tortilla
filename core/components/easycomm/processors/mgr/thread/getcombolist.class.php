<?php

/**
 * Get a list of ecThread for combobox
 */
class easyCommThreadGetComboListProcessor extends modObjectGetListProcessor {
	public $objectType = 'ecThread';
	public $classKey = 'ecThread';
	public $defaultSortField = 'id';
	public $defaultSortDirection = 'DESC';
	//public $permission = 'list';


    public function prepareQueryBeforeCount(xPDOQuery $c) {
        if ($this->getProperty('combo')) {
            $c->select('id, name, title');
        }
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%'.$query.'%',
                'OR:title:LIKE' => "%{$query}%",
            ));
        }
        return $c;
    }
    /** {@inheritDoc} */
    public function prepareRow(xPDOObject $object) {
        if ($this->getProperty('combo')) {
            $title = $object->get('title');
            if(empty($title)) {
                $title = $object->get('name');
            }
            else {
                $title = $title.' ('.$object->get('name').')';
            }
            $array = array(
                'id' => $object->get('id'),
                'title' => $object->id.' - '.$title,
            );
        }
        else {
            $array = $object->toArray();
        }
        return $array;
    }

}

return 'easyCommThreadGetComboListProcessor';