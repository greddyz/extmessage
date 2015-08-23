<?php
$xpdo_meta_map['ExtMessage']= array (
  'package' => 'extmessage',
  'version' => '1.1',
  'table' => 'user_extmessages',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'type' => '',
    'sender' => 0,
    'recipient' => 0,
    'subject' => '',
    'message' => '',
    'private' => 0,
    'date_sent' => '0000-00-00 00:00:00',
    'read' => 0,
    'flag' => 0,
    'extended' => '',
  ),
  'fieldMeta' => 
  array (
    'type' => 
    array (
      'dbtype' => 'nvarchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'sender' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'recipient' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'subject' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'message' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'private' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'date_sent' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
      'default' => '0000-00-00 00:00:00',
    ),
    'read' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'flag' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'extended' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'sender' => 
    array (
      'alias' => 'sender',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'sender' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'recipient' => 
    array (
      'alias' => 'recipient',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'recipient' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'type' => 
    array (
      'alias' => 'type',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'type' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
