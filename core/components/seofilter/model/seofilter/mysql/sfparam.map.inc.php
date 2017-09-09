<?php
$xpdo_meta_map['sfParam']= array (
  'package' => 'seofilter',
  'version' => '1.1',
  'table' => 'sf_params',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'type' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'composites' => 
  array (
    'Pieces' => 
    array (
      'class' => 'sfPiece',
      'local' => 'id',
      'foreign' => 'param',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
