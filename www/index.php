<?php

require __DIR__ . "/../vendor/autoload.php";

$app = new Leaf\App;

$config = new App\Services\ConfigService;
$config->setAppConfig($app);

$db = new Leaf\Db($app->config("database"));

// Required by DevTools:
// set current working to parent as "vendor" is not a sub-folder of the app
chdir(__DIR__ . "/..");
\Leaf\DevTools::install("/devtools");

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

// done -> run the app
$app->run();
