<?php

require __DIR__ . "/../vendor/autoload.php";

$app = new Leaf\App;

// Required by DevTools:
// set current working to parent as "vendor" is not a sub-folder of the app
chdir(__DIR__ . "/..");
\Leaf\DevTools::install("/devtools");

$app->get("/", function () use ($app) {
  $app->response()->json(["message" => "Hello World!"]);
});

$app->get("/hello/{name}", function ($name) use ($app) {
  $app->response()->json(["greeting" => "Hello " . ucfirst($name) . "!"]);
});

$app->run();
