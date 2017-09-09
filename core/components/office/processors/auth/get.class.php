<?php

/** @noinspection PhpIncludeInspection */
require MODX_CORE_PATH . 'model/modx/processors/security/user/get.class.php';

class officeAuthUserGetProcessor extends modUserGetProcessor
{
    public $permission = '';


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        $primaryKey = $this->modx->user->get('id');
        $this->object = $this->modx->getObject($this->classKey, $primaryKey);

        return empty($this->object)
            ? $this->modx->lexicon($this->objectType . '_err_nfs', array($this->primaryKeyField => $primaryKey))
            : true;
    }

}

return 'officeAuthUserGetProcessor';