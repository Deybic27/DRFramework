<?php

namespace me\core\database;

use PDOException;

class Delete extends Connection{

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
  protected $where;

  /**
   * Construct function.
   */
  function __construct($table)
  {
    parent::__construct();
    $this->table = $table;
    $this->where = NULL;
  }

  /**
   * Add where statement to query.
   */
  public function condition($field, $value, $operator) {
    $this->where = $this->where == NULL ? "WHERE $field $operator '$value'" : $this->where . " AND $field $operator '$value'";
    return $this;
  }

  /**
   * Execute query function.
   */
  public function execute() {
    try {
      // Sql to create table.
      $sql = "DELETE FROM $this->table $this->where";
      return $this->connection()->exec($sql);
    } catch (PDOException $e) {
      return $sql . "Error: " . $e->getMessage();
    }
  }
}
