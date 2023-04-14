<?php

namespace me\controller;

use me\core\http\Request;
use me\core\database\Database;

include_once __DIR__ . '/../core/database/Database.php';

/**
 * Users.
 */
class UsersController {

  /**
   * Get user.
   */
  public function show(Request $request) {
    $query = $request->query();

    if (!isset($query['id']) || !$query['id']) {
      header('Content-Type: application/json', TRUE, 400);
      return ['status' => 400, 'messaje' => 'Incomplete data'];
    }

    $database = new Database();

    $select = $database->select('users')->fields(['name', 'email', 'password'])->condition('id', $query['id'], '=')->execute();
    if (!$select) {
      return ['status' => 404, 'messaje' => 'Not Found'];
    }

    return ['status' => 200, 'messaje' => 'Successful', 'data' => $select[0], 'rq' => $request->getAll()];

  }

  /**
   * Insert user.
   */
  public function create(Request $request) {
    $data = $request->getContent();
    $error = [];

    if (!isset($data['name']) || !$data['name']) {
      header('Content-Type: application/json', TRUE, 400);
      $error['name'] = 'Is required';
    }else if (is_numeric($data['name'])) {
      $error['name'] = 'Invalid name format';
    }

    if (!isset($data['email']) || !$data['email']) {
      $error['email'] = 'Is required';
    }else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
      $error['email'] = 'Invalid email format';
    }

    if (!isset($data['password']) || !$data['password']) {
      $error['password'] = 'Is required';
    }else if (strlen($data['password']) < 8) {
      $error['password'] = 'Short password: minimum 8 characters';
    }else if (strlen($data['password']) > 15) {
      $error['password'] = 'Long password: maximum 15 characters';
    }else if (!preg_match('`[a-z]`', $data['password'])) {
      $error['password'] = 'The key must have at least one lowercase letter';
    }else if (!preg_match('`[A-Z]`', $data['password'])) {
      $error['password'] = 'Key must have at least one capital letter';
    }else if (!preg_match('`[0-9]`', $data['password'])){
      $error['password'] = 'The key must have at least one numeric character';
    }

    if ($error) {
      return ['status' => 400, 'messaje' => 'Incomplete parameters', 'error' => $error];
    }

    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    $database = new Database();

    $select = $database->select('users')->fields(['email'])->condition('email', $email, '=')->execute();
    if ($select) {
      return ['status' => 406, 'messaje' => 'Registered email'];
    }

    $fields = [
      'name' => $name,
      'email' => $email,
      'password' => $password
    ];

    $database->insert('users')->fields($fields)->execute();
    return ['status' => 201, 'messaje' => 'Created'];
  }

  /**
   * Update user.
   */
  public function update(Request $request) {
    $query = $request->query();
    $data = $request->getContent();

    if (!isset($query['id']) || !$query['id']) {
      header('Content-Type: application/json', TRUE, 400);
      return ['status' => 400, 'messaje' => 'Incomplete data'];
    }

    $database = new Database();

    $select = $database->select('users')->fields(['name', 'email', 'password'])->condition('id', $query['id'], '=')->execute();
    if (!$select) {
      return ['status' => 404, 'messaje' => 'Not Found'];
    }

    $user = $select[0];

    $fields = [
      'name' => $data['name'] ?? $user['name'],
      'email' => $data['email'] ?? $user['email'],
      'password' => $data['password'] ?? $user['password']
    ];

    $database->update('users')->fields($fields)->condition('id', $query['id'], '=')->execute();

    return ['status' => 200, 'messaje' => 'Updated'];
  }

  /**
   * Delete user.
   */
  public function destroy(Request $request) {
    $query = $request->query();

    if (!isset($query['id']) || !$query['id']) {
      header('Content-Type: application/json', TRUE, 400);
      return ['status' => 400, 'messaje' => 'Incomplete data'];
    }

    $database = new Database();

    $delete = $database->delete('users')->condition('id', $query['id'], '=')->execute();
    if (!$delete) {
      return ['status' => 404, 'messaje' => 'Not Found'];
    }
    return ['status' => 200, 'messaje' => 'Successful'];
  }
}
