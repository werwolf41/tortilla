<?php

/**
 * Publish an ecMessage
 */
class easyCommMessageUnPublishProcessor extends modObjectUpdateProcessor {
    /** @var ecMessage $object */
    public $object;
    public $objectType = 'ecMessage';
    public $classKey = 'ecMessage';
    public $languageTopics = array('easycomm');
    //public $permission = 'unpublish';

    public $beforeSaveEvent = 'OnBeforeEcMessageUnpublish';
    public $afterSaveEvent = 'OnEcMessageUnpublish';


    /**
     * @return bool|null|string
     */
    public function beforeSave()
    {
        $this->object->fromArray(array(
            'published' => 0,
            'publishedon' => null,
            'publishedby' => 0,
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
        $this->modx->logManagerAction($this->objectType . '_unpublish', $this->classKey, $this->object->get($this->primaryKeyField));
    }
}

return 'easyCommMessageUnPublishProcessor';
