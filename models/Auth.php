<?php

require "./models/User.php";

class Auth
{
  public $currentUser;

  public function __construct() {
    if ($userId = Session::get("userId")) {
      $this->currentUser = Fetch::User($userId);
    } else {
      $this->currentUser = false;
    }
  }

  public static function login($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([":email" => $email]);
    $result = $stmt->fetch();
    $user = new User($result["id"], $result["email"], $result["password"]);
    if (password_verify($password, $user->password)) {
      session_regenerate_id(true);
      Session::set("userId", $user->id);
      Session::set("userEmail", $user->email);
      return true;
    } else {
      return false;
    }
  }

  public static function logout() {
    Session::delete("userId");
    Session::delete("userEmail");
  }
}

