<?php

namespace me\core\database;

use PDOException;
use me\core\database\DeleteTable;

include_once __DIR__ . "/Connection.php";
include_once __DIR__ . '/CreateTable.php';
include_once __DIR__ . '/DeleteTable.php';
include_once __DIR__ . '/Database.php';
include_once __DIR__ . '/Insert.php';
include_once __DIR__ . '/Select.php';
include_once __DIR__ . '/Update.php';
include_once __DIR__ . '/Delete.php';

class Database extends Connection{

  public function tableExists($table) {
    try {
      $this->connection()->exec("SELECT 1 FROM $table");
      return TRUE;
    } catch (PDOException $e) {
      return FALSE;
    }
  }

  /**
   * Delete table to database.
   */
  public function deleteTable($table) {
    return new DeleteTable($table);
  }

  /**
   * Create table to database.
   */
  public function createTable($table) {
    return new CreateTable($table);
  }

  /**
   * Insert register to database.
   */
  public function insert($table) {
    return new Insert($table);
  }

  /**
   * Select register to database.
   */
  public function select($table) {
    return new Select($table);
  }

  /**
   * Update register to database.
   */
  public function update($table) {
    return new Update($table);
  }

  /**
   * Delete register to database.
   */
  public function delete($table) {
    return new Delete($table);
  }
}

