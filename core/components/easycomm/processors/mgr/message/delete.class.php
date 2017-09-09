<?php

/**
 * Mark ecMessage as deleted
 */
class easyCommMessageDeleteProcessor extends modObjectUpdateProcessor {
    /** @var ecMessage $object */
    public $object;
	public $objectType = 'ecMessage';
	public $classKey = 'ecMessage';
	public $languageTopics = array('easycomm');
	//public $permission = 'delete';

    public $beforeSaveEvent = 'OnBeforeEcMessageDelete';
    public $afterSaveEvent = 'OnEcMessageDelete';


    /**
     * @return bool|null|string
     */
    public function beforeSave()
    {
        $this->object->fromArray(array(
            'deleted' => 1,
            'deletedon' => date('Y-m-d H:i:s'),
            'deletedby' => $this->modx->user->get('id'),
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
        $this->modx->logManagerAction($this->objectType . '_delete', $this->classKey, $this->object->get($this->primaryKeyField));
    }
}

return 'easyCommMessageDeleteProcessor';