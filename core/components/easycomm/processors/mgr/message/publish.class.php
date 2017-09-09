<?php

/**
 * Publish an ecMessage
 */
class easyCommMessagePublishProcessor extends modObjectUpdateProcessor {
    /** @var ecMessage $object */
    public $object;
    public $objectType = 'ecMessage';
    public $classKey = 'ecMessage';
    public $languageTopics = array('easycomm');
    //public $permission = 'publish';

    public $beforeSaveEvent = 'OnBeforeEcMessagePublish';
    public $afterSaveEvent = 'OnEcMessagePublish';


    /**
     * @return bool|null|string
     */
    public function beforeSave()
    {
        $this->object->fromArray(array(
            'published' => 1,
            'publishedon' => date('Y-m-d H:i:s'),
            'publishedby' => $this->modx->user->get('id'),
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
        $this->modx->logManagerAction($this->objectType . '_publish', $this->classKey, $this->object->get($this->primaryKeyField));
    }
}

return 'easyCommMessagePublishProcessor';