<?php

use Slim\Exception\HttpBadRequestException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
// $app->setBasePath('/simple_balance_api');
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

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
    function (\Psr\Http\Message\ServerRequestInterface $request, Throwable $exception) {
        $response = new Response();
        $response->getBody()->write($exception->getMessage());
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
);

\Routes\ApiRouter::build($app);

$app->run();
