<?php

namespace me\core\http;

use me\core\http\Http;

include_once __DIR__ . '/Http.php';

/**
 * Class request.
 */
class Request extends Http {

  /**
   * Get query.
   * GET method.
   */
  public function query() {
    return $_GET;
  }

  /**
   * Get request.
   * POST method.
   */
  public function request() {
    return $_POST;
  }

  /**
   * Get contents.
   */
  public function getContent() {
    return json_decode(file_get_contents('php://input'), true);
  }

  /**
   * Get all.
   * GET, POST and CONTENTS.
   */
  public function getAll() {
    return [
      'query' => $_GET,
      'post' => $_POST,
      'content' => json_decode(file_get_contents('php://input'), true),
    ];
  }

}
