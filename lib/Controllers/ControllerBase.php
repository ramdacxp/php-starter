<?php

namespace App\Controllers;

use App\Services\AuthService;
use Leaf\App;
use Leaf\Db;

class ControllerBase
{
  protected App $app;
  protected Db $db;
  protected AuthService $auth;

  public function __construct()
  {
    $this->app = app();
    $this->db = db();
    $this->auth = $this->app->auth;
  }
}
