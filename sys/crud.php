<?php

class CRUD {

  /*
  * приватное свойство
  * в нем мы будем хранить имя таблицы
  */    
  private $table;

  /*
  * приватное свойство
  * в нем мы будем хранить объект для запросов в БД (Базу Данных)
  */ 
  private $connect;


  /*
  * конструктор
  * сохраняем имя таблицы - $table
  * сохраняем объект для работы с БД - $connect
  */ 
  public function __construct($table, $connect) {
    $this->table = $table;
    $this->connect = $connect;
  }


  /*
  * вставка новых данных в БД
  * $data - массив с данными для вставки в формате 'ключ' -> 'знвчение'
  * в случае успешного выполнения возвращает $id созданой записи
  * иначе вызывается ошибка
  * 
  * $data = array(
  *   'name' => 'John Doe',
  *   'email' => 'john.doe@hotmail.com',
  *   ...
  * );
  * $id = $crud->insert($data);
  */
  public function insert($data) {

    # строка с именами колонок в БД
    # `name`, `email`, ...
    $cols = '';

    # строка со значениями колонок
    # 'John Doe', 'john.doe@hotmail.com', ...
    $vals = '';

    # перебираем массив с данными
    foreach ($data as $key => $val) {
      # собираем ключи в строку через запятую
      $cols .= ($cols ? ', ' : '') . "`{$key}`";

      # перед вставкой в БД экранируем спец символы в значениях
      # для избежания sql-инекции
      $val = $this->connect->real_escape_string($val);
      # собираем значения в строку через запятую
      $vals .= ($vals ? ', ' : '') . "'{$val}'";
    }

    # собираем строку запроса в БД
    $sql = 'INSERT INTO ' . $this->table . ' (' . $cols . ') VALUES (' . $vals . ')';

    # делаем запрос в базу
    if ($this->connect->query($sql) === false) {
      # если вернуло false - вызываем ошибку
      trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
    } else {
      # если true, получаем id вставленной в БД записи
      return $this->connect->insert_id;
    }    
  }


  /*
  * выборка записей из БД
  * $where - условие выборки
  * $limit - лимит выборки
  * возвращает массив с данными выборки или пустой массив
  * 
  * $result = $crud->select('name = "John Doe"', 1);
  */
  public function select($where = null, $limit = null) {

    # подготавливаем строку запроса в БД
    $sql = 'SELECT * FROM ' . $this->table . ' ';

    # если есть условие $where - добавляем в строку запроса
    if ($where) $sql .= 'WHERE ' . $where . ' ';
    # если есть лимит $limit - добавляем ...
    if ($limit) $sql .= 'LIMIT ' . $limit . ' ';

    # делаем запрос в БД
    $result = $this->connect->query($sql);

    if ($result === false) {
      # если вернуло false - значит ошибка в запросе (скорее всего синтаксическа)
      trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->connect->error, E_USER_ERROR);
    } else {
      # если не false - значим получаем результат выборки в виде массива
      return $result->fetch_all(MYSQLI_ASSOC);
    }   
  }


  /*
  * обновление записей в БД
  * $data - данные на обновление
  * $where - условие поиска записей
  * $limit - лимит записей на обновление
  * возвращает колличество затронутых записей
  *
  * $data = array('name' => 'Hugo Chavez');
  * $count = $crud->update($data, 'id = 2', 1);
  */
  public function update($data, $where = null, $limit = null) {
    # строка запроса
    $sql = '';

    # србираем строку запроса из массива данных на обновление
    foreach ($data as $key => $val) {
      # экранируем спец символы (предотвращаем sql-инекции)
      $val = $this->connect->real_escape_string($val);
      # добавляем в строку запроса пару 'key' = 'value'
      $sql .= ($sql ? ', ' : '') . "`{$key}` = '{$val}'";
    }

    # собираем строку запроса в БД
    $sql = 'UPDATE ' . $this->table . ' SET ' . $sql;

    # если есть условие - добавляем его
    if ($where) $sql .= ' WHERE ' . $where;
    # если есть лимит - добавляем
    if ($limit) $sql .= ' LIMIT ' . $limit;

    # выполняем запрос в БД
    if ($this->connect->query($sql) === false) {
      # если вернуло false - вызываем ошибку
      trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->connect->error, E_USER_ERROR);
    } else {
      # если все хорошо, возвращаем колличество затронутых записей
      return $this->connect->affected_rows;
    }
  }

  /*
  * удаление записей из в БД
  * $where - условие поиска записей
  * $limit - лимит записей на обновление
  * возвращает колличество затронутых записей
  *
  * $count = $crud->delete('id = 2');
  */
  public function delete($where = null, $limit = null) {

    # собираем строку запроса в БД
    $sql = 'DELETE FROM ' . $this->table;

    # если есть условие - добавляем его
    if ($where) $sql .= ' WHERE ' . $where;
    # если есть лимит - добавляем
    if ($limit) $sql .= ' LIMIT ' . $limit;

    # выполняем запрос в БД
    if ($this->connect->query($sql) === false) {
      # если вернуло false - вызываем ошибку
      trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $this->connect->error, E_USER_ERROR);
    } else {
      # если все хорошо, возвращаем колличество затронутых записей
      return $this->connect->affected_rows;
    }
  }

}