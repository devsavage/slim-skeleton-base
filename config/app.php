<?php

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
            ]
        ],

        // Logger

        LoggerFactory::class => function(ContainerInterface $container) {
            return new LoggerFactory($container->get("settings")["logger"]);
        },

        "logger" => function($container) {
            return $container->get(LoggerFactory::class)->addFileHandler("error.log")->createLogger();
        },
    ]);
};