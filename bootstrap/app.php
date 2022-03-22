<?php

use App\DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;

define("INC_ROOT", __DIR__);

require INC_ROOT . "/../vendor/autoload.php";

if(file_exists(INC_ROOT . "/../.env")) {
    $dotenv = \Dotenv\Dotenv::createImmutable(INC_ROOT . "/../");
    $dotenv->load();
}

$builder = new ContainerBuilder();

$appConfig = require INC_ROOT . "/../config/app.php";
$appConfig($builder);

$container = $builder->build();

$settings = $container->get("settings");

$app = Bridge::create($container);
$app->setBasePath($settings["base_path"]);

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$app->addErrorMiddleware(true, true, true, $container->get("logger"));

$webRoutes = require INC_ROOT . "/../routes/web.php";
$webRoutes($app);