<?php

namespace App\Http\Handlers;

use Throwable;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Interfaces\ErrorHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpMethodNotAllowedException;

class ErrorHandler implements ErrorHandlerInterface
{
    protected $_view, $_response, $_logger;

    public function __construct(ContainerInterface $container)
    {
        $this->_view = $container->get("view");
        $this->_response = $container->get(\Psr\Http\Message\ResponseFactoryInterface::class);
        $this->_logger = $container->get("logger");
    }

    public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface
    {
        switch(true) {
            case $exception instanceof HttpNotFoundException:
            case $exception instanceof HttpMethodNotAllowedException:
                $code = $exception->getCode();
                $response = $this->_response->createResponse($code);

                return $this->_view->render($response, "error/" . $code . ".twig", [
                    "title" => $exception->getTitle(),
                    "description" => $exception->getDescription(),
                ]);
            default:
                $response = $this->_response->createResponse(500);
                
                return $this->_view->render($response, "error/500.twig", [
                    "title" => "{$exception->getCode()} {$exception->getMessage()}",
                    "description" => $exception->getMessage(),
                ]);
        }
    }
}