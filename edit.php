<?php

# подготовка подключения
require 'sys/processing.php';

# массив для хранения данных о пользователе
$user = array();

# переменная для хранения сообщений
$message = false;

# если в массиве POST есть переменная name и она не пустая
# значит у нас создание нового или редактирование уже существующего пользователя
if (isset($_POST['name']) && $_POST['name']) {

  # заполняем массив данными из POST
  $user['name'] = $_POST['name'];
  $user['email'] = $_POST['email'];
  $user['location'] = $_POST['location'];
  $user['birthday'] = $_POST['birthday'];
  $user['phone'] = $_POST['phone'];
  
  # если в POST есть id - значит мы сейчас редактируем
  # существующего пользователя
  if ($_POST['id']) {
    
    # приводим переменную к int (целочисленное значение)
    $user['id'] = (int) $_POST['id'];

    # делаем запрос в БД
    if ($db->update($user, 'id = ' . $user['id'], 1)) {
      # если вернуло колличество затронутых строк, значит запись в БД успешно обновлена
      $message = 'User "' . $user['name'] . '" (id:' . $user['id'] . ') has been updated!';
    }    

  # если нет id в POST, значит мы создаем нового пользователя
  } elseif ($id = $db->insert($user)) {

    # пишем в куку сообщение о успешном создании пользователя
    # почему в куку? потому что потом мы будем делать редирект на другую страницу
    # и уже там выведем сообщение 
    setcookie('flash_message', 'User "' . $user['name'] . '" (id ' . $id . ') has been created!');

    # делаем редирект на список пользователей
    header('Location: ./');
  }

# если в POST нет name, но в GET есть user,
# значит нам надо получить информацию о конкретном пользователе 
} elseif (isset($_GET['user'])) {

  # приводим переменную к int (целочисленное значение)
  $id = (int) $_GET['user'];

  # делаем запрос в БД
  $result = $db->select('id = ' . $id);

  # если есть результат, помещаем его в переменную $user
  if (isset($result[0])) {
    $user = $result[0];
  }

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
  
  <div class="row">
    <div class="col-sm-4">
      <!-- 
        форма для создания нового или редактирования 
        уже существующего в базе пользователя 
      -->
      <form method="POST">
        <input type="hidden" name="id" value="<?php echo isset($user['id']) ? $user['id'] : ''?>">
        <div class="form-group">
          <label for="user-name">Name</label>
          <input type="text" class="form-control" id="user-name" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''?>" required>
        </div>
        <div class="form-group">
          <label for="user-email">Email</label>
          <input type="email" class="form-control" id="user-email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''?>" required>
        </div>
        <div class="form-group">
          <label for="user-location">Location</label>
          <input type="text" class="form-control" id="user-location" name="location" value="<?php echo isset($user['location']) ? $user['location'] : ''?>" required>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="user-birthday">Birthday</label>
              <input type="text" class="form-control" id="user-birthday" name="birthday" value="<?php echo isset($user['birthday']) ? $user['birthday'] : ''?>" placeholder="yyyy-mm-dd" required>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label for="user-phone">Phone</label>
              <input type="text" class="form-control" id="user-phone" name="phone" value="<?php echo isset($user['phone']) ? $user['phone'] : ''?>" placeholder="(xxx) xxx-xx-xx" required>
            </div>
          </div>
        </div>
    
        <a href="./" class="btn btn-default pull-right">Cancel</a>
        <button type="submit" class="btn btn-primary">
        <?php if (isset($user['id'])) { ?>Edit user<?php } else { ?>Add user<?php } ?>         
      </form>
    </div>
  </div>
  
</div>

<?php include 'partials/footer.php' ?>