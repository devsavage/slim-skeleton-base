<?php

use Slim\Views\Twig;
use DI\ContainerBuilder;
use App\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        "settings" => [
            "base_path" => "",
            "logger" => [
                "path" => INC_ROOT . "/../logs",
                "level" => \Monolog\Logger::DEBUG,
            ],
            "view" => [
                "template_path" => INC_ROOT . "/../resources/views",
                "twig" => [
                    "debug" => env("APP_ENV") == "development" ? true : false,
                    "cache" => false,
                ],
            ],
        ],

        // Logger

        LoggerFactory::class => function(ContainerInterface $container) {
            return new LoggerFactory($container->get("settings")["logger"]);
        },

        "logger" => function($container) {
            return $container->get(LoggerFactory::class)->addFileHandler("error.log")->createLogger();
        },

        // Twig

        "twig" => function(ContainerInterface $container) {
            $settings = $container->get("settings");

            $twig = Twig::create($settings["view"]["template_path"], $settings["view"]["twig"]);

            return $twig;
        },

        // View - For customized twig environment

        "view" => function(ContainerInterface $container) {
            $twig = $container->get("twig");

            return $twig;
        },
    ]);
};