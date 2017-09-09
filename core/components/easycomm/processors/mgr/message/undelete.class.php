<?php

/**
 * Mark ecMessage is not deleted
 */
class easyCommMessageUnDeleteProcessor extends modObjectUpdateProcessor {
    /** @var ecMessage $object */
    public $object;
    public $objectType = 'ecMessage';
    public $classKey = 'ecMessage';
    public $languageTopics = array('easycomm');
    //public $permission = 'undelete';

    public $beforeSaveEvent = 'OnBeforeEcMessageUndelete';
    public $afterSaveEvent = 'OnEcMessageUndelete';


    /**
     * @return bool|null|string
     */
    public function beforeSave()
    {
        $this->object->fromArray(array(
            'deleted' => 0,
            'deletedon' => null,
            'deletedby' => 0,
        ));
        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function afterSave()
    {
        /** @var ecThread $thread */
        if ($thread = $this->object->getOne('Thread')) {
            $thread->updateMessagesInfo();
        }
        return parent::afterSave();
    }

    /**
     * Log the removal manager action
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_undelete', $this->classKey, $this->object->get($this->primaryKeyField));
    }
}

return 'easyCommMessageUnDeleteProcessor';