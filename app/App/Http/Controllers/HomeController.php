<?php

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function get(Response $response): Response
    {
        return $this->render($response, "home");
    }
}
