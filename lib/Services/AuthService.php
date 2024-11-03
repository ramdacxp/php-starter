<?php

namespace App\Services;

use Leaf\Db;


class AuthService
{
  protected Db $db;

  public function __construct(Db $db)
  {
    $this->db = $db;
  }

  public function existsUser(string $login): bool
  {
    $user = $this->db->select("users", "login")->where("login", $login)->first();
    return isset($user["login"]);
  }

  public function registerUser(string $login, string $password, ?string $name = null): ?string
  {
    if (!$this->isValid($login)) return "Invalid or missing value for 'login'.";
    if (!$this->isValid($password, 6)) return "Invalid or missing value for 'password'.";

    if ($this->existsUser($login)) return "User with login '$login' already exists.";

    $hash = password_hash($password, PASSWORD_BCRYPT);
    // if (password_verify('rasmuslerdorf', $hash)) {

    $this->db
      ->insert("users")
      ->params([
        "login" => $login,
        "password" => $hash,
        "name" => $name
      ])
      ->execute();

    // no error
    return null;
  }

  private function isValid(?string $value, int $minLength = 1): bool
  {
    if ($value === null) return false;
    if (strlen($value) < $minLength) return false;
    return true;
  }
}
