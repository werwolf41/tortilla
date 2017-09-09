<?php

/**
 * Remove an ecThread
 */
class easyCommThreadRemoveProcessor extends modObjectRemoveProcessor {
    /** @var ecThread $object */
    public $object;
    public $objectType = 'ecThread';
    public $classKey = 'ecThread';
    public $languageTopics = array('easycomm');
    //public $permission = 'remove';

    public $beforeRemoveEvent = 'OnBeforeEcThreadRemove';
    public $afterRemoveEvent = 'OnEcThreadRemove';

    /**
     * Log the removal manager action
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_remove', $this->classKey, $this->object->get($this->primaryKeyField));
    }

}

return 'easyCommThreadRemoveProcessor';
