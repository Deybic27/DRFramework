<?php

namespace me\core\database;

use PDOException;

class Insert extends Connection{

  /**
   * Table name.
   *
   * @var string
   */
  protected $table;

  /**
   * Field names.
   *
   * @var string
   */
  protected $fieldNames;

  /**
   * Field values.
   *
   * @var string
   */
  protected $fieldValues;

  /**
   * Construct function.
   */
  function __construct($table)
  {
    parent::__construct();
    $this->table = $table;
    $this->fieldNames = NULL;
    $this->fieldValues = NULL;
  }

  /**
   * Fields function.
   */
  public function fields(array $values) {
    foreach ($values as $key => $value) {
      $this->fieldNames = $this->fieldNames == NULL ? "$key" : $this->fieldNames . ", $key";
      $this->fieldValues = $this->fieldValues == NULL ? "'$value'" : $this->fieldValues . ", '$value'";
    }
    return $this;
  }

  /**
   * Execute query function.
   */
  public function execute() {
    try {
      // Sql to create table.
      $sql = "INSERT INTO $this->table ($this->fieldNames) VALUES ($this->fieldValues)";
      return $this->connection()->exec($sql);
    } catch (PDOException $e) {
      return $sql . "Error: " . $e->getMessage();
    }
  }
}
