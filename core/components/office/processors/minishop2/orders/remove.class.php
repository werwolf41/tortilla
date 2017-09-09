<?php

class msOrderOfficeRemoveProcessor extends modObjectRemoveProcessor
{
    /** @var msOrder $object */
    public $object;
    public $classKey = 'msOrder';
    public $languageTopics = array('minishop2:default');
    public $permission = '';


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->getProperty('allowRemove')) {
            return $this->modx->lexicon('access_denied');
        }

        if ($initialize = parent::initialize()) {
            if ($this->object->get('user_id') != $this->modx->user->id || $this->object->get('status') != 1) {
                return $this->modx->lexicon('access_denied');
            }
        }

        return $initialize;
    }

}

return 'msOrderOfficeRemoveProcessor';