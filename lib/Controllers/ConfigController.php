<?php

namespace App\Controllers;

use Leaf\App;
use App\Services\ConfigService;

class ConfigController
{
  protected App $app;
  protected ConfigService $config;

  public function __construct(App $app, ConfigService $config)
  {
    $this->app = $app;
    $this->config = $config;
  }

  // GET /config
  public function getInfo()
  {
    $this->app->response()->json([
      "status" => "ok",
      "php" => phpversion(),
      "configured" => $this->config->hasUserConfig(),
      // "db.host" => $this->app->config("db.config")["host"],
      // "db.dbname" => $this->app->config("db.config")["dbname"],
    ]);
  }

  // POST /config
  public function postConfig()
  {
    $data = $this->app->request()->get(["host", "user", "password", "dbname"]);
    if (is_array($data) === false) {
      $this->app->response()->json([
        "error" => "Error: Invalid configuration data. Expected an object with host, user, password, dbname."
      ], 400);
      return;
    }

    if ($this->config->saveUserConfig($data) === false) {
      $this->app->response()->json([
        "error" => "Error: Changing an existing configuration is not allowed. Delete the existing configuration file and try again."
      ], 403);
      return;
    }

    $this->app->response()->json([
      "message" => "Configuration saved"
    ]);
  }
}
