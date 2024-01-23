<?php 

class Fetch
{
  public static function find($table, $id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE id = :id");
    $stmt->execute([":id" => $id]);
    return $stmt->fetch();
  }

  public static function user($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([":id" => $id]);
    return $stmt->fetch();
  }

  public static function userByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([":email" => $email]);
    return $stmt->fetch();
  }

  public static function allUsers() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public static function sendEmailsByUserId($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM send_emails WHERE user_id = :user_id");
    $stmt->execute([":user_id" => $user_id]);
    return $stmt->fetchAll();
  }

  public static function sendEmail($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM send_emails WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $sendEmail = $stmt->fetch();
    if ($sendEmail["user_id"] !== Auth::user()["id"]) abort(403);
    return $sendEmail;
  }

  public static function surveysByUserId($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM surveys WHERE user_id = :user_id");
    $stmt->execute([":user_id" => $user_id]);
    return $stmt->fetchAll();
  }

  public static function survey($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM surveys WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $survey = $stmt->fetch();
    if ($survey["user_id"] !== Auth::user()["id"]) abort(403);
    return $survey;
  }

  public static function faq($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $faq = $stmt->fetch();
    return $faq;
  }

  public static function faqsBySurveyId($survey_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE survey_id = :survey_id");
    $stmt->execute([":survey_id" => $survey_id]);
    return $stmt->fetchAll();
  }

  public static function optionsByFaqId($faq_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM options WHERE faq_id = :faq_id");
    $stmt->execute([":faq_id" => $faq_id]);
    return $stmt->fetchAll();
  }
}


