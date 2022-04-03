<?php

use DI\ContainerBuilder;
use Slim\Views\TwigMiddleware;
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

/**
 * Module config
 */

$configModuleDir = INC_ROOT . "/../config/modules/";

if(file_exists($configModuleDir) && is_dir($configModuleDir)) {
    $files = [];

    $modulePath = realpath($configModuleDir);

    foreach(Finder::create()->files()->name("*.php")->in($modulePath) as $file) {
        $moduleConfig = require $file->getRealPath();
        $moduleConfig($builder);
    }
}

$container = $builder->build();

$settings = $container->get("settings");

$app = Bridge::create($container);
$app->setBasePath($settings["base_path"]);

$responseFactory = $app->getResponseFactory();
$container->set(\Psr\Http\Message\ResponseFactoryInterface::class, $responseFactory);

$routeParser = $app->getRouteCollector()->getRouteParser();
$container->set(Slim\Interfaces\RouteParserInterface::class, $routeParser);

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();

$app->add(TwigMiddleware::createFromContainer($app));

/**
 * Module bootstrap
 */

$moduleDir = INC_ROOT . "/modules/";

if(file_exists($moduleDir) && is_dir($moduleDir)) {
    $files = [];

    $modulePath = realpath($moduleDir);

    foreach(Finder::create()->files()->name("*.php")->in($modulePath) as $file) {
        require $file->getRealPath();
    }
}

$errorMiddleware = $app->addErrorMiddleware(true, true, true, $container->get("logger"));
$errorMiddleware->setDefaultErrorHandler(new ErrorHandler($container));

$webRoutes = require INC_ROOT . "/../routes/web.php";
$webRoutes($app);

$moduleRoutesDir = INC_ROOT . "/../routes/modules/";

if(file_exists($moduleRoutesDir) && is_dir($moduleRoutesDir)) {
    $files = [];

    $modulePath = realpath($moduleRoutesDir);

    foreach(Finder::create()->files()->name("*.php")->in($modulePath) as $file) {
        $moduleRoutes = require $file->getRealPath();
        $moduleRoutes($app);
    }
}