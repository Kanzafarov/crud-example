<?php

if (isset($_GET['database'])) {

  $db_host = $_POST['db-host'];
  $db_user = $_POST['db-user'];
  $db_pass = $_POST['db-pass'];
  $db_name = $_POST['db-name'];

  // Create connection
  $connection = new mysqli($db_host, $db_user, $db_pass);

  // Check connection
  if ($connection->connect_error) {

    die(json_encode(array(
      'status' => 'connection failed',
      'info' => $connection->connect_error
    )));
  }

  $sql = sprintf(file_get_contents('database.sql'), $db_name, $db_name, $db_name);

  if ($connection->multi_query($sql)) {

    echo json_encode(array(
      'status' => 'success',
      'info' => "database created successfully"
    ));

  } else {

    die(json_encode(array(
      'status' => 'error creating database',
      'info' => $connection->error
    )));

  }

  $connection->close();
}