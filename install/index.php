<?php

error_reporting(0);

$err = false;
$msg = false;

if ($_POST['db-host']) {

  // Create connection
  $conn = new mysqli($_POST['db-host'], $_POST['db-user'], $_POST['db-pass']);

  // Check connection
  if ($conn->connect_error) {

    $err = 'Connection failed: ' . $conn->connect_error;

  } else {

    $sql = file_get_contents('database.sql');    
    $sql = preg_replace('/{{dbname}}/', $_POST['db-name'], $sql);

    if ($conn->multi_query($sql)) {

      $config = array(
        'host' => $_POST['db-host'],
        'user' => $_POST['db-user'],
        'pass' => $_POST['db-pass'],
        'name' => $_POST['db-name']
      );

      $config = var_export($config, true);

      $config = "<?php\n\n# данные для подключения к БД (Базе Данных)\nreturn $config;\n\n?>";

      file_put_contents('../sys/dbconfig.php', $config);

      $msg = 'Database created successfully';

    } else {

      $err = 'Error creating database: ' . $conn->error;

    }

    $conn->close();

  }
}

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>CRUD Example Install</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <span class="navbar-brand">CRUD Example</span>
      </div>
    </div>
  </nav>
</header>

<div class="container">
  <div class="row">
  <?php if ($err || $msg): ?>

    <div class="col-sm-6 col-sm-offset-3">
      <?php if ($err): ?>
      <div class="alert alert-danger"><?php echo $err ?></div>
      <a href="" class="btn btn-default">Retry</a>
      <?php endif ?>

      <?php if ($msg): ?>
      <div class="alert alert-success"><?php echo $msg ?></div>
      <a href="../" class="btn btn-default">Go to user list</a>
      <?php endif ?>
    </div>

  <?php else: ?>
  
    <div class="col-sm-4 col-sm-offset-4">
      <form method="POST">
        <h3>Install CRUD Example</h3>
        <div class="form-group">
          <label for="db-host">Database host</label>
          <input type="text" class="form-control" id="db-host" value="localhost" name="db-host" required>
        </div>
        <div class="form-group">
          <label for="db-user">Database User</label>
          <input type="text" class="form-control" id="db-user" value="root" name="db-user" required>
        </div>
        <div class="form-group">
          <label for="db-pass">Database Password</label>
          <input type="password" class="form-control" id="db-pass" value="" name="db-pass">
        </div>
        <div class="form-group">
          <label for="db-name">Database Name</label>
          <input type="text" class="form-control" id="db-name" value="crud_example" name="db-name" required>
        </div>
        <button type="submit" class="btn btn-primary">Install</button>
      </form>
    </div>  

  <?php endif ?>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

</body>
</html>