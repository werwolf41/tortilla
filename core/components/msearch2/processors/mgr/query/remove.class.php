<?php

class mseQueryRemoveProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'mseQuery';
	public $classKey = 'mseQuery';
	public $languageTopics = array('msearch2');
	public $primaryKeyField = 'query';

}

return 'mseQueryRemoveProcessor';