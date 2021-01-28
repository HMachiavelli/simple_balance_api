<?php

namespace App\Controllers;

use App\Models\Account;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class BalanceController
{
    public static function get(Request $request, Response $response, $args)
    {
        $query = $request->getQueryParams();

        $balance = (new Account())->getById((int)($query['id'] ?? 0));
        if (!$balance) {
            throw new HttpNotFoundException($request);
        }

        $response->getBody()->write($balance->getBalance());
        return $response;
    }
}
