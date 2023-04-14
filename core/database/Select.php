<?php

namespace me\core\database;

use PDO;
use PDOException;

class Select extends Connection{

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
   * Field names.
   *
   * @var string
   */
  protected $where;

  /**
   * Construct function.
   */
  public function __construct($table)
  {
    parent::__construct();
    $this->table = $table;
    $this->fieldNames = NULL;
    $this->where = NULL;
  }

  /**
   * Fields function.
   */
  public function fields(array $values) {
    foreach ($values as $key => $value) {
      $this->fieldNames = $this->fieldNames == NULL ? "$value" : $this->fieldNames . ", $value";
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
   * Execute query function.
   */
  public function execute() {
    try {
      $this->fieldNames = $this->fieldNames ?: '*';
      // Sql to create table.
      $sql = "SELECT $this->fieldNames FROM $this->table $this->where";
      $prepare = $this->connection()->prepare($sql);
      $prepare->execute();
      $response = $prepare->fetchAll(PDO::FETCH_ASSOC);
      return  $response ?: FALSE;
    } catch (PDOException $e) {
      return $sql . "Error: " . $e->getMessage();
    }
  }
}
