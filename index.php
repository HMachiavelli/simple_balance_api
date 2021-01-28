<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(false, true, true);

\App\Middleware\CustomErrorMiddleware::add($errorMiddleware);
\Routes\ApiRouter::build($app);

$app->run();
