<?php 

class Fetch
{
  public static function User($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch();

    return new User($result["id"], $result["email"], $result["password"]);
  }

  public static function AllUsers() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $results = $stmt->fetchAll();
    function newUser($user) {
      return new User($user["id"], $user["email"], $user["password"]);
    }

    return array_map("newUser", $results);
  } 
}


