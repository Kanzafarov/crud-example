<?php

# подготовка подключения
require 'sys/processing.php';

# выбираем из БД всех пользователей
$users = $db->select();

# переменная для хранения сообщений
$message = false;

# если есть кука под именем flash_message, 
# передаем ее содержимое в переменную $message
# и очищаем куку
if (isset($_COOKIE["flash_message"]) && $_COOKIE["flash_message"]) {
  $message = $_COOKIE["flash_message"];
  setcookie('flash_message', null, -1);
}

# подключаем header
include 'partials/header.php';
?>

<div class="container">
  
  <div class="row">
    <div class="col-sm-6">
      <!-- если есть сообщение, выводим его -->
      <?php if ($message) { ?>
        <div class="alert alert-info" role="alert"><?php echo $message ?></div>
      <?php } ?>
    </div>
  </div>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>id</th>
        <th>Name</th>
        <th>Email</th>
        <th>Birthday</th>
        <th>Location</th>
        <th>Phone</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <!-- выводим пользователей -->
    <?php foreach ($users as $user): ?>    
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= $user['name'] ?></td>
        <td><?= $user['email'] ?></td>
        <td><?= $user['birthday'] ?></td>
        <td><?= $user['location'] ?></td>
        <td><?= $user['phone'] ?></td>
        <td>
          <!-- ссылка на редактирование пользователя -->
          <a class="btn btn-sm btn-default" href="edit.php?user=<?= $user['id'] ?>" title="Edit user">
            <span class="glyphicon glyphicon-pencil"></span>
          </a>
          <!-- ссылка на удаление пользователя -->
          <a class="btn btn-sm btn-default" href="delete.php?user=<?= $user['id'] ?>" title="Remove user">
            <span class="glyphicon glyphicon-remove"></span>
          </a>
        </td>
      </tr>    
    <?php endforeach ?>

    </tbody>  
  </table>

  <div class="text-right">
    <!-- ссылка на добавление нового пользователя -->
    <a href="edit.php" class="btn btn-primary">Add user</a>
  </div>

</div>

<!-- подключаем footer -->
<?php include 'partials/footer.php' ?>