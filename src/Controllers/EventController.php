<?php

namespace App\Controllers;

use App\Models\Account;
use App\Models\Event;
use App\Services\Deposit;
use App\Services\Transfer;
use App\Services\Withdraw;
use App\Validators\EventValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EventController
{
    public static function create(Request $request, Response $response, $args)
    {
        $params = (array)$request->getParsedBody();
        $params = EventValidator::validate($request, $params);

        switch ($params['type']) {
            case EventValidator::TYPE_DEPOSIT:
                $return = Deposit::new($params['destinationObj'], $params['amount']);
                break;
            case EventValidator::TYPE_TRANSFER:
                $return = Transfer::new($params['originObj'], $params['destinationObj'], $params['amount']);
                break;
            case EventValidator::TYPE_WITHDRAW:
                $return = Withdraw::new($params['originObj'], $params['amount']);
                break;
            default:
                throw new \Exception('Invalid type.');
                break;
        }

        $response->getBody()->write(json_encode($return));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
