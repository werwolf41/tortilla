<?php
$xpdo_meta_map['mseAlias']= array (
  'package' => 'msearch2',
  'version' => '1.1',
  'table' => 'mse2_aliases',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'word' => NULL,
    'alias' => NULL,
    'replace' => 0,
  ),
  'fieldMeta' => 
  array (
    'word' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'alias' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'replace' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'word' => 
    array (
      'alias' => 'word',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'word' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'alias' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
