<?php

namespace me\core\database;

use PDOException;

class Update extends Connection{

  /**
   * Table name.
   *
   * @var string
   */
  protected $table;

  /**
   * Field values.
   *
   * @var string
   */
  protected $fieldValues;

  /**
   * Field names.
   *
   * @var string
   */
  protected $where;

  /**
   * Construct function.
   */
  function __construct($table)
  {
    parent::__construct();
    $this->table = $table;
    $this->fieldValues = NULL;
    $this->where = NULL;
  }

  /**
   * Fields function.
   */
  public function fields(array $values) {
    foreach ($values as $key => $value) {
      $this->fieldValues = $this->fieldValues == NULL ? "$key = '$value'" : $this->fieldValues . ", $key = '$value'";
    }
    return $this;
  }

  /**
   * Add where statement to query.
   */
  public function condition($field, $value, $operator) {
    $this->where = $this->where == NULL ? "WHERE $field $operator '$value'" : $this->where . " AND $field $operator '$value'";
    return $this;
  }

  /**
   * Execute query.
   */
  public function execute() {
    try {
      // Sql to create table.
      $sql = "UPDATE $this->table SET $this->fieldValues $this->where";
      return $this->connection()->exec($sql);
    } catch (PDOException $e) {
      return $sql . "Error: " . $e->getMessage();
    }
  }
}
