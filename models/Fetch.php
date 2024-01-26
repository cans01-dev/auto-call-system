<?php 

class Fetch
{
  public static function find($table, $where_value, $where_key="id") {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$where_key} = :value");
    $stmt->execute([":value" => $where_value]);
    return $stmt->fetch();
  }

  public static function get($table, $where_value, $where_key, $order_by="id") {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$where_key} = :value ORDER BY {$order_by}");
    $stmt->execute([
      ":value" => $where_value
    ]);
    return $stmt->fetchAll();
  }

  public static function all($table) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM {$table}");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public static function optionByDialAndFaq($dial, $faq_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM options WHERE dial = :dial AND faq_id = :faq_id");
    $stmt->execute([
      ":dial" => $dial,
      ":faq_id" => $faq_id
    ]);
    return $stmt->fetch();
  }

  public static function reservesBySurveyIdAndYearMonth($survey_id, $month, $year) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM reserves WHERE survey_id = :survey_id AND MONTH(date) = :month AND YEAR(date) = :year");
    $stmt->execute([
      ":survey_id" => $survey_id,
      ":month" => $month,
      ":year" => $year
    ]);
    return $stmt->fetchAll();
  }

  public static function areasByReserveId($reserve_id) {
    global $pdo;
    $sql = "SELECT *, ra.id as ra_id FROM reserves_areas as ra JOIN areas as a ON ra.area_id = a.id WHERE ra.reserve_id = :reserve_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":reserve_id" => $reserve_id
    ]);
    return $stmt->fetchAll();
  }

  public static function areasByFavoriteId($favorite_id) {
    global $pdo;
    $sql = "SELECT *, fa.id as fa_id FROM favorites_areas as fa JOIN areas as a ON fa.area_id = a.id WHERE fa.favorite_id = :favorite_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":favorite_id" => $favorite_id
    ]);
    return $stmt->fetchAll();
  }
}


