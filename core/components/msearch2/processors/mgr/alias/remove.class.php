<?php

class mseAliasRemoveProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'mseAlias';
	public $classKey = 'mseAlias';
	public $languageTopics = array('msearch2');

}

return 'mseAliasRemoveProcessor';