<?php


use me\core\http\Request;
use me\controller\UsersController;

include_once __DIR__ . '/../controller/UsersController.php';
include_once __DIR__ . '/../core/http/Request.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {

  $request = new Request();

  $user = new UsersController();
  $show = $user->show($request);

  header('Content-Type: application/json', TRUE, $show['status']);
  echo json_encode($show);
}

if ($method == 'POST') {
  $request = new Request();

  $user = new UsersController();
  $create = $user->create($request);

  header('Content-Type: application/json', TRUE, $create['status']);
  echo json_encode($create);
}

if ($method == 'PUT') {

  $request = new Request();

  $user = new UsersController();
  $update = $user->update($request);

  header('Content-Type: application/json', TRUE, $update['status']);
  echo json_encode($update);
}

if ($method == 'DELETE') {

  $request = new Request();

  $user = new UsersController();
  $destroy = $user->destroy($request);

  header('Content-Type: application/json', TRUE, $destroy['status']);
  echo json_encode($destroy);
}
