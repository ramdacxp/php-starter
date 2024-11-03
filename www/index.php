<?php

require __DIR__ . "/../vendor/autoload.php";

// -----------------------------------------------------------------------------
// INIT
// -----------------------------------------------------------------------------
$app = new Leaf\App;

// app configuration
$config = new App\Services\ConfigService;
// $app->config("db", $config->getConfig());

// database
$db = new Leaf\Db($config->getConfig());
// $db->connect($app->config("db"));
$config->initDatabaseTables($db);

// DB config is auto pushed to app config with key "db.config"

// auth
$auth = new App\Services\AuthService($db);

// devtools
// important: set cwd to parent as "vendor" is not a sub-folder of the app
chdir(__DIR__ . DIRECTORY_SEPARATOR . "..");
\Leaf\DevTools::install("/devtools");

// -----------------------------------------------------------------------------
// ROUTES
// -----------------------------------------------------------------------------

// example routes
$app->get("/", function () use ($app) {
  $app->response()->json(["message" => "Hello World!"]);
});

$app->get("/hello/{name}", function ($name) use ($app) {
  $app->response()->json(["greeting" => "Hello " . ucfirst($name) . "!"]);
});

// config routes
$configController = new App\Controllers\ConfigController($app, $config);
$app->get("/config", [$configController, "getInfo"]);
$app->post("/config", [$configController, "postConfig"]);

// auth routes
$authController = new App\Controllers\AuthController($app, $auth);
$app->post("/auth/register", [$authController, "postRegister"]);
$app->post("/auth/login", [$authController, "postLogin"]);
$app->post("/auth/logout", [$authController, "postLogout"]);

// -----------------------------------------------------------------------------
// RUN THE APP
// -----------------------------------------------------------------------------
$app->run();
