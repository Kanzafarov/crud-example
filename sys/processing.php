<?php

# подключаем CRUD
require 'crud.php';
# получаем конфиги
$dbconfig = require 'dbconfig.php';

# создаем подключение БД
$connect = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['pass'], $dbconfig['name']);

# если есть текст ошибки в connect_error, вызываем свою ошибку
if ($connect->connect_error) {
  trigger_error('Database connection failed: '  . $connect->connect_error, E_USER_ERROR);
}

# создаем экземпляр CRUD для работы с таблицей users
$db = new CRUD('users', $connect);