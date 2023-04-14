<?php

namespace me\core\database;

use PDOException;

class CreateTable extends Connection{

  /**
   * Table name.
   *
   * @var string
   */
  protected $table;

  /**
   * Table fields.
   *
   * @var string
   */
  protected $fields;

  /**
   * Construct function.
   */
  function __construct($table)
  {
    parent::__construct();
    $this->table = $table;
    $this->fields = NULL;
  }

  /**
   * Add field function.
   */
  public function addField($field, $type, $properties = '') {
    $this->fields = $this->fields == NULL ? "$field $type $properties" : $this->fields . ", $field $type $properties";
  }

  /**
   * Fields function.
   */
  public function fields(array $fields) {
    foreach ($fields as $key => $field) {
      $this->addField($key, $field['type'], $field['properties'] ?? '');
    }
    return $this;
  }

  /**
   * Execute query function.
   */
  public function execute() {
    try {
      $sql = "CREATE TABLE $this->table ($this->fields)";
      // Sql to create table.
      $this->connection()->exec($sql);
      return 'Successful';
    } catch (PDOException $e) {
      return $sql . "Error: " . $e->getMessage();
    }
  }
}
