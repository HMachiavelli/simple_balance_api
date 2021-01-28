<?php

namespace Routes;

use App\Controllers\EventController;
use App\Controllers\ResetController;
use App\Controllers\BalanceController;

class ApiRouter
{
    public static function build(\Slim\App &$app)
    {
        $app->post('/reset', ResetController::class . ':reset');
        $app->get('/balance', BalanceController::class . ':get');
        $app->post('/event', EventController::class . ':create');
    }
}
