<?php

require __DIR__ . "/../vendor/autoload.php";

// -----------------------------------------------------------------------------
// INIT
// -----------------------------------------------------------------------------
$app = new Leaf\App;

// app configuration
$config = new App\Services\ConfigService;
// $app->config("db", $config->getConfig());
$dbConfig = $config->getConfig();

// try to create database, if it does not exist
// needs to be executed BEFORE creating the Leaf\Db instance
$config->initDatabase($dbConfig);

// database
$db = new Leaf\Db($dbConfig);
$config->initDatabaseTables($db);

// register the db instance as app()->database
// $app->register("db", $db);

// auth
$auth = new App\Services\AuthService($db);
$auth->registerMiddleware();
$app->register("auth", function () use ($auth) {
  return $auth;
});

// devtools (only if running in PHP dev server!)
if (strpos($_SERVER["SERVER_SOFTWARE"], "Development Server") !== false) {
  // important: set cwd to parent as "vendor" is not a sub-folder of the app
  chdir(__DIR__ . DIRECTORY_SEPARATOR . "..");
  \Leaf\DevTools::install("/devtools");
}

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

// auth routes (public)
$authController = new App\Controllers\AuthController($app, $auth);
$app->post("/auth/register", [$authController, "postRegister"]);
$app->post("/auth/login", [$authController, "postLogin"]);


$app->setNamespace("App\\Controllers");

// auth routes (logged in)
$app->post(
  "/auth/logout",
  [
    "middleware" => "auth",
    // "namespace" => "App\\Controllers",
    "ApiController@index"
  ]
);

// -----------------------------------------------------------------------------
// RUN THE APP
// -----------------------------------------------------------------------------
$app->run();
