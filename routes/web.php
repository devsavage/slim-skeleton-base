<?php

use Slim\App;

return function (App $app) {
    $app->get("[/]", [\App\Http\Controllers\HomeController::class, "get"])->setName("home");
};