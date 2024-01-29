<?php 

class DB
{
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

  public static function delete($table, $id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM {$table} WHERE id = :id");
    $stmt->execute([":id" => $id]);
    return $stmt->rowCount();
  }

  public static function lastInsertId() {
    global $pdo;
    return $pdo->lastInsertId();
  }

  public static function exchangeColumn($table, $from, $to, $column) {
    global $pdo;
    $pdo->beginTransaction();
    DB::update($table, $from["id"], [
      $column => $to[$column]
    ]);
    DB::update($table, $to["id"], [
      $column => $from[$column]
    ]);
    $pdo->commit();
  }
}


