<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

use SlimSkeleton\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function get(Response $response): Response
    {
        return $this->render($response, "home");
    }
}
