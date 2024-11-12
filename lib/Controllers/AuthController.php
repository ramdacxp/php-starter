<?php

namespace App\Controllers;

use App\Services\AuthService;
use Leaf\App;

class AuthController
{
  protected App $app;
  protected AuthService $auth;

  public function __construct(App $app, AuthService $auth)
  {
    $this->app = $app;
    $this->auth = $auth;
  }

  // POST /auth/register
  public function postRegister()
  {
    $login = $this->app->request()->get("login");
    $password = $this->app->request()->get("password");
    $name = $this->app->request()->get("name");

    $error = $this->auth->registerUser($login, $password, $name);
    if ($error === null) {
      $this->app->response()->json([
        "message" => "New user '$login' registered."
      ]);
    } else {
      $this->app->response()->json([
        "error" => "Error: $error"
      ], 400);
    }
  }

  // POST /auth/login
  public function postLogin()
  {
    $login = $this->app->request()->get("login");
    $password = $this->app->request()->get("password");

    $userId = $this->auth->checkLogin($login, $password);
    if ($userId > 0) {
      $this->app->response()->json([
        "message" => "User '$login' logged in.",
        "userId" => $userId
      ]);
    } else {
      $this->app->response()->json([
        "error" => "Invalid login or password."
      ], 401);
    }
  }
}
