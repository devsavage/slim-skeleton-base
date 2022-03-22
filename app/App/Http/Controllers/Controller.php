<?php

namespace App\Http\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    protected $_view;
    
    public function __construct(ContainerInterface $container)
    {
        $this->_view = $container->get("view");
    }
    
    public function render(Response $response, string $templatePath)
    {
        return $this->_view->render($response, $templatePath . ".twig");
    }
}