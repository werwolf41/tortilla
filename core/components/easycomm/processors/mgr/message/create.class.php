<?php

/**
 * Create an ecMessage
 */
class easyCommMessageCreateProcessor extends modObjectCreateProcessor {
    public $objectType = 'ecMessage';
    public $classKey = 'ecMessage';
    public $languageTopics = array('easycomm');
    //public $permission = 'create';

    public $beforeSaveEvent = 'OnBeforeEcMessageSave';
    public $afterSaveEvent = 'OnEcMessageSave';

    /** @var ecMessage $object */
    public $object;

    /** @var ecThread $thread */
    private $thread;

    /**
     * @return bool
     */
    public function beforeSet() {
        $threadId = $this->getProperty('thread');

        if (!$this->thread = $this->modx->getObject('ecThread', $threadId)) {
            $this->modx->error->addField('thread', $this->modx->lexicon('ec_message_err_thread'));
        }

        $rating = intval($this->getProperty('rating'));
        $ratingMax = intval($this->modx->getOption('ec_rating_max'));
        if($rating < 0) {
            $rating = 0;
        }
        if($rating > $ratingMax) {
            $rating = $ratingMax;
        }

        $now = date('Y-m-d H:i:s');
        $this->setProperties(array(
            'rating' => $rating,
            'createdon' => $now,
            'createdby' => $this->modx->user->isAuthenticated($this->modx->context->key) ? $this->modx->user->id : 0,
            'editedon' => null,
            'editedby' => 0
        ));
        if($this->getProperty('published')) {
            $this->setProperties(array(
                'publishedon' => $now,
                'publishedby' => $this->modx->user->isAuthenticated($this->modx->context->key) ? $this->modx->user->id : 0,
            ));
        }
        return parent::beforeSet();
    }

    /** {@inheritDoc} */
    public function afterSave() {
        $this->thread->updateMessagesInfo();

        /* @var ecMessage $m */
        if($m = $this->modx->getObject('ecMessage', $this->getProperty('id'))){
            if($m->notifyUser()){
                $m->set('notify', 0);
                $m->set('notify_date', date('Y-m-d H:i:s'));
                $m->save();
            }
        }

        return parent::afterSave();
    }

}

return 'easyCommMessageCreateProcessor';