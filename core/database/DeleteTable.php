<?php

namespace me\core\database;

use PDOException;
use me\core\database\Connection;

include_once __DIR__ . "/Connection.php";

class DeleteTable extends Connection{

  /**
   * Table name.
   *
   * @var string
   */
  protected $table;

  /**
   * Construct function.
   */
  function __construct($table)
  {
    parent::__construct();
    $this->table = $table;
  }

  /**
   * Execute query function.
   */
  public function execute() {
    try {
      // Sql to Delete table.
      $sql = "DROP TABLE $this->table";
      $this->connection()->exec($sql);
      return $sql . 'Successful';
    } catch (PDOException $e) {
      return $sql . "Error: " . $e->getMessage();
    }
  }
}
