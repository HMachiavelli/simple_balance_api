<?php

namespace App\Middleware;

use Slim\Psr7\Response;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;

class CustomErrorMiddleware
{
    public static function add(\Slim\Middleware\ErrorMiddleware &$errorMiddleware): void
    {
        $errorMiddleware->setErrorHandler(
            HttpMethodNotAllowedException::class,
            function () {
                $response = new Response();
                return $response->withStatus(405);
            }
        );

        $errorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function () {
                $response = new Response();
                $response->getBody()->write('0');
                return $response->withStatus(404);
            }
        );

        $errorMiddleware->setErrorHandler(
            HttpBadRequestException::class,
            function (\Psr\Http\Message\ServerRequestInterface $request, \Throwable $exception) {
                $response = new Response();
                $response->getBody()->write($exception->getMessage());
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        );
    }
}
