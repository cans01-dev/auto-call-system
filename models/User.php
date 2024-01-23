<?php 

class User
{
  public $id, $email, $password;

  public static function find($id) {
    
  }

  public function __construct()
  {
    //
  }

  public function create() {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
    $stmt->execute([
      ":email" => escape($this->email),
      ":password" =>password_hash($this->password, PASSWORD_DEFAULT)
    ]);
    return $pdo->lastInsertId();
  }

  public function save() {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET email = :email, password = :password WHERE id = :id");
    $stmt->execute([
      ":id" => $this->id,
      ":email" => escape($this->email),
      ":password" => password_hash($this->password, PASSWORD_DEFAULT)
    ]);
  }
}

