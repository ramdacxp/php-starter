<?php

require __DIR__ . "/../vendor/autoload.php";

// -----------------------------------------------------------------------------
// INIT
// -----------------------------------------------------------------------------
$app = new Leaf\App;

// app configuration
$config = new App\Services\ConfigService;
$config->setAppConfig($app);

// database
$db = new Leaf\Db($app->config("db"));
$config->initDatabaseTables($db);

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

// -----------------------------------------------------------------------------
// RUN THE APP
// -----------------------------------------------------------------------------
$app->run();
