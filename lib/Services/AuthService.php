<?php

namespace App\Services;

use Leaf\App;
use Leaf\Db;

class AuthService
{
  protected Db $db;

  public function __construct(Db $db)
  {
    $this->db = $db;
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

  public function getUserProperty(string $login, string $column)
  {
    $user = $this->db
      ->select("users", $column)
      ->where("login", $login)
      ->first();

    return isset($user[$column]) ? $user[$column] : null;
  }

  public function checkCredentials(string $login, string $password): bool
  {
    $hashedPassword = $this->getUserProperty($login, "password");
    if ($hashedPassword != null) {
      if (password_verify($password, $hashedPassword)) {
        return true;
      }
    }

    return false;
  }

  public function existsUser(string $login): bool
  {
    $userId = $this->getUserProperty($login, "id");
    return $userId != null;
  }

  public function generateToken(): string
  {
    return bin2hex(openssl_random_pseudo_bytes(16));
  }

  public function createSession(string $login, string $userAgent): string|null
  {
    $userId = $this->getUserProperty($login, "id");
    if ($userId == null) return null;

    $token = $this->generateToken();

    $this->db
      ->insert("sessions")
      ->params([
        "refUser" => $userId,
        "token" => $token,
        "userAgent" => $userAgent,
        // "created" => date("Y-m-d H:i:s")
      ])
      ->execute();

    return $token;
  }

  public function registerMiddleware()
  {
    app()->registerMiddleware("auth", function () {
      // $method = request()->getMethod();
      // $url = request()->getUrl();
      // echo "[Middleware: $method @ $url]\n";

      app()->response()->json(["error" => "miep"], 401);
      exit();
    });
  }
}
