<?php

# подготовка подключения
require 'sys/processing.php';

# если есть user в GET
if (isset($_GET['user'])) {

  # приводим переменную к int (целочисленное значение)
  $id = (int) $_GET['user'];  

  # получаем данные о пользователе, которого хотим удалить
  # что бы показать его имя в предупреждении о удалении
  # и случайно не удалить не того пользователя
  $result = $db->select('id = ' . $id);

  # помещаем данные о пользователе в переменную $user
  $user = $result[0];

}

# если есть user в POST, значит пользователя пора удалять
if (isset($_POST['user'])) {

  # приводим переменную к int (целочисленное значение)
  $id = (int) $_POST['user']; 

  # удаляем пользователя из БД
  $db->delete('id = ' . $id, 1);

  # пишем в куку сообщение о успешном создании пользователя
  # почему в куку? потому что потом мы будем делать редирект на другую страницу
  # и уже там выведем сообщение 
  setcookie('flash_message', 'User "' . $user['name'] . '" (id ' . $id . ') removed from database!');

  # делаем редирект на список пользователей
  header('Location: ./');

}

# подключаем header
include 'partials/header.php';
?>

<div class="container">

  <div class="row">
    <div class="col-sm-5 col-sm-offset-3">
      <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Removing!</h3>
        </div>
        <div class="panel-body">
          <p>
            Are you sure you want to delete a user "<?php echo $user['name']?>" (id:<?php echo $user['id']?>)?
          </p>
          <form class="text-right" method="POST" action="">
            <input type="hidden" name="user" value="<?php echo $user['id']?>">
            <a href="./" class="btn btn-default">Cancel</a>
            <button type="submit" class="btn btn-danger">Delete</button>          
          </form>
        </div>        
      </div>
    </div>
  </div>
  
</div>

<?php include 'partials/footer.php' ?>