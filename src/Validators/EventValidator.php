<?php

namespace App\Validators;

use App\Models\Event;
use App\Models\Account;
use Slim\Exception\HttpBadRequestException;

class EventValidator
{
    public static function validate($request, array $params): array
    {
        $types = [
            Event::TYPE_DEPOSIT,
            Event::TYPE_TRANSFER,
            Event::TYPE_WITHDRAW
        ];

        if (!in_array($params['type'], $types)) {
            throw new HttpBadRequestException($request, 'Invalid type.');
        }

        if (empty($params['destination']) && ($params['type'] == Event::TYPE_DEPOSIT || $params['type'] == Event::TYPE_TRANSFER)) {
            throw new HttpBadRequestException($request, 'Destination ID required.');
        }

        if (empty($params['origin']) && ($params['type'] == Event::TYPE_WITHDRAW || $params['type'] == Event::TYPE_TRANSFER)) {
            throw new HttpBadRequestException($request, 'Origin ID required.');
        }

        $destination = $origin = null;
        if (!empty($params['destination'])) {
            $destination = (new Account())->getById((int)$params['destination']);
            if (!$destination) {
                $destination = (new Account(['id' => (int)$params['destination'], 'balance' => 0]));
                $destination->insert();
            }
        }

        if (!empty($params['origin'])) {
            $origin = (new Account())->getById((int)$params['origin']);
            if (!$origin) {
                throw new HttpBadRequestException($request, 'Invalid origin ID.');
            }
        }

        return [
            'type' => $params['type'],
            'amount' => (float)$params['amount'],
            'origin' => (int)$params['origin'],
            'destination' => (int)$params['destination'],
            'originObj' => $origin,
            'destinationObj' => $destination
        ];
    }
}
