<?php 

class DB
{
  public static function find($table, $where_value, $where_key="id") {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$where_key} = :value");
    $stmt->execute([":value" => $where_value]);
    return $stmt->fetch();
  }

  public static function insert($table, $array) {
    global $pdo;
    $k = array_str(array_keys($array));
    $v = "";
    $args = [];
    $last = array_slice($array, -1);
    foreach ($array as $key => $value) {
      $v .= ":{$key}";
      $args[":{$key}"] = $value;
      if ($key !== key($last)) $v .= ", ";
    }
    $sql = "INSERT INTO {$table} ({$k}) VALUES ({$v})";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->rowCount();
  }

  public static function update($table, $id, $array) {
    global $pdo;
    $kv = "";
    $args = [":id" => $id];
    $last = array_slice($array, -1);
    foreach ($array as $key => $value) {
      $kv .= "{$key} = :{$key}";
      $args[":{$key}"] = $value;
      if ($key !== key($last)) $kv .= ", ";
    }
    $sql = "UPDATE {$table} SET {$kv} WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->rowCount();
  }

  public static function lastInsertId() {
    global $pdo;
    return $pdo->lastInsertId();
  }
}


