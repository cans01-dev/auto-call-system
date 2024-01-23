<?php

require "./models/User.php";

class Auth
{
  public static function check() {
    return Session::get("userId");
  }

  public static function user() {
    return Fetch::find("users", Session::get("userId"));
  }

  public static function attempt($email, $password) {
    $user = Fetch::userByEmail($email);
    if (password_verify($password, $user["password"])) {
      Session::set("userId", $user["id"]);
      Session::set("userEmail", $user["email"]);
      return true;
    }
    return false;
  }

  public static function logout() {
    Session::delete("userId");
    Session::delete("userEmail");
  }
}

