<?php 

class Fetch
{
  public static function find($table, $id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE id = :id");
    $stmt->execute([":id" => $id]);
    return $stmt->fetch();
  }

  public static function userByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([":email" => $email]);
    return $stmt->fetch();
  }

  public static function sendEmailsByUserId($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM send_emails WHERE user_id = :user_id");
    $stmt->execute([":user_id" => $user_id]);
    return $stmt->fetchAll();
  }

  public static function surveysByUserId($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM surveys WHERE user_id = :user_id");
    $stmt->execute([":user_id" => $user_id]);
    return $stmt->fetchAll();
  }

  public static function faqsBySurveyId($survey_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE survey_id = :survey_id");
    $stmt->execute([":survey_id" => $survey_id]);
    return $stmt->fetchAll();
  }

  public static function optionsByFaqId($faq_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM options WHERE faq_id = :faq_id ORDER BY dial");
    $stmt->execute([":faq_id" => $faq_id]);
    return $stmt->fetchAll();
  }

  public static function maxDialInFaqId($faq_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT MAX(dial) FROM options WHERE faq_id = :faq_id");
    $stmt->execute([":faq_id" => $faq_id]);
    return $stmt->fetchColumn();
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

  public static function allAreas() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM areas");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public static function areaByTitle($title) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM areas WHERE title = :title");
    $stmt->execute([
      ":title" => $title
    ]);
    return $stmt->fetch();
  }
}


