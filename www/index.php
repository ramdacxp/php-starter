<?php

require __DIR__ . "/../vendor/autoload.php";

$app = new Leaf\App;

// patch current working dir as "vendor" is not a sub-folder of the app
chdir(__DIR__ . "/..");
\Leaf\DevTools::install("/devtools");

$app->get("/", function () use ($app) {
  $app->response()->json(["message" => "Hello World!"]);
});

$app->run();
