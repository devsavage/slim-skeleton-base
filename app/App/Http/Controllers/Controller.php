<?php

namespace App\Http\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    protected $_view, $_container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->_view = $container->get("view");
        $this->_container = $container;
    }

    public function __get($property) 
    {
        if ($this->_container->has($property)) {
            return $this->_container->get($property);
        }
    }
    
    public function render(Response $response, string $templatePath)
    {
        return $this->_view->render($response, $templatePath . ".twig");
    }
}