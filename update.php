<?php

  require 'vendor/autoload.php';

  use App\Product;
  use App\SQLiteConnection;


  $conn = new SQLiteConnection();
  $pdo = $conn->connect();

  $sqliteP = new Product($pdo);

	$data = json_decode(file_get_contents('php://input'), true);

  if (!isset($data['id'])) {

    $res = -1;

  } elseif (!isset($data['actiu'])) {

    $res = -2;

  } elseif($data['id']==0) {

    $res = $sqliteP->deactivateProducts();

  } else {

    $res = $sqliteP->activateProduct( $data['id'], $data['actiu'] );

  }

  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($res);
