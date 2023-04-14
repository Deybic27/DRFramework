<?php

use me\core\database\Database;

include_once __DIR__ . '/core/database/Database.php';

$fields = [
  'id' => [
    'type' => 'INT',
    'properties' => 'UNSIGNED AUTO_INCREMENT PRIMARY KEY'
  ],
  'name' => [
    'type' => 'VARCHAR(60)',
  ],
  'email' => [
    'type' => 'VARCHAR(60)',
  ],
  'password' => [
    'type' => 'TEXT'
  ]
];

$database = new Database();
echo $database->deleteTable('users')->execute();

if (!$database->tableExists('users')) {
  echo $database->createTable('users')->fields($fields)->execute();
}
