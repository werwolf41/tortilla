<?php
$xpdo_meta_map['mseQuery']= array (
  'package' => 'msearch2',
  'version' => '1.1',
  'table' => 'mse2_queries',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'query' => NULL,
    'quantity' => 1,
    'found' => 0,
  ),
  'fieldMeta' => 
  array (
    'query' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
    'quantity' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 1,
    ),
    'found' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'query' => 
    array (
      'alias' => 'query',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'query' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'quantity' => 
    array (
      'alias' => 'quantity',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'quantity' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'found' => 
    array (
      'alias' => 'found',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'found' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
