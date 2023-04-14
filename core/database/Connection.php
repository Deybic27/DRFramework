<?php

namespace me\core\database;

use PDO;
use PDOException;

/**
 * Connection database.
 */
abstract class Connection {

  /**
   * @var string
   */
  protected $databaseEngine;

  /**
   * @var string
   */
  protected $serverName;

  /**
   * @var string
   */
  protected $dbName;

  /**
   * @var string
   */
  protected $userName;

  /**
   * @var string
   */
  protected $password;

  /**
   * Function construct.
   */
  function __construct($dbName = 'me', $userName = 'root', $password = 'root', $databaseEngine = 'mysql', $serverName = 'localhost')
  {
    $this->databaseEngine = $databaseEngine;
    $this->serverName = $serverName;
    $this->dbName = $dbName;
    $this->userName = $userName;
    $this->password = $password;
  }

  /**
   * Connection function to database.
   */
  public function connection() {
    try {
      $connection = new PDO("$this->databaseEngine:host=$this->serverName;dbname=$this->dbName", $this->userName, $this->password);
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $connection;
    } catch (PDOException $e) {
      echo "Connection failed: " . "$this->databaseEngine:host=$this->serverName;dbname=$this->dbName" . $e->getMessage();
    }
  }

}
