<?php

  require 'vendor/autoload.php';

  use App\Product;
  use App\SQLiteConnection;


  $conn = new SQLiteConnection();
  $pdo = $conn->connect();

  $sqliteP = new Product($pdo);

  if (!isset($_POST['name'])) {

    $msg = "No name defined";

  } elseif (!isset($_POST['id_category'])) {

    $msg = "No category defined";

  } else {

    $msg = $sqliteP->insertProduct( $_POST['name'], $_POST['id_category'] );

  }

  header("Location: index.php?msg=" . $msg);
	die();
