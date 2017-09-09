<?php

/**
 * Remove an ecMessage
 */
class easyCommMessageRemoveProcessor extends modObjectRemoveProcessor {
    /** @var ecMessage $object */
    public $object;
	public $objectType = 'ecMessage';
	public $classKey = 'ecMessage';
	public $languageTopics = array('easycomm');
	//public $permission = 'remove';

    public $beforeRemoveEvent = 'OnBeforeEcMessageRemove';
    public $afterRemoveEvent = 'OnEcMessageRemove';

    /**
     * @return bool
     */
    public function afterRemove() {
        /** @var ecThread $thread */
        if ($thread = $this->object->getOne('Thread')) {
            $thread->updateMessagesInfo();
        }
    }

    /**
     * Log the removal manager action
     * @return void
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction($this->objectType . '_remove', $this->classKey, $this->object->get($this->primaryKeyField));
    }

}

return 'easyCommMessageRemoveProcessor';