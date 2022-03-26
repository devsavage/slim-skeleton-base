<?php

use DI\ContainerBuilder;
use App\Http\Handlers\ErrorHandler;
use SavageDev\DI\Bridge\Slim\Bridge;
use Symfony\Component\Finder\Finder;

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

$responseFactory = $app->getResponseFactory();
$container->set(\Psr\Http\Message\ResponseFactoryInterface::class, $responseFactory);

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true, $container->get("logger"));
$errorMiddleware->setDefaultErrorHandler(new ErrorHandler($container));

$webRoutes = require INC_ROOT . "/../routes/web.php";
$webRoutes($app);

// Ensure we have our module directory ready for any modules to use

$moduleDir = INC_ROOT . "/modules/";

if(file_exists($moduleDir) && is_dir($moduleDir)) {
    $files = [];

    $modulePath = realpath($moduleDir);

    foreach(Finder::create()->files()->name("*.php")->in($modulePath) as $file) {
        require $file->getRealPath();
    }
} else {
    mkdir($moduleDir);
}