<?php

require_once __DIR__ . "/../bootstrap/app.php";

try {
    $app->run();
} catch (\Exception $e) {
    $container->get("logger")->error($e->getMessage());
    throw new Exception("Unable to boot Slim.");
}