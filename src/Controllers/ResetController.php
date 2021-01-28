<?php

namespace App\Controllers;

use App\Models\ApplicationModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ResetController
{
    public static function reset(Request $request, Response $response, $args)
    {
        (new ApplicationModel())->resetDb();

        $response->getBody()->write("OK");
        return $response;
    }
}
