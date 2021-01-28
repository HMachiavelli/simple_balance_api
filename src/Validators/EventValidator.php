<?php

namespace App\Validators;

use App\Models\Event;
use App\Models\Account;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;

class EventValidator
{
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_TRANSFER = 'transfer';
    const TYPE_WITHDRAW = 'withdraw';

    public static function validate($request, array $params): array
    {
        $types = [
            self::TYPE_DEPOSIT,
            self::TYPE_TRANSFER,
            self::TYPE_WITHDRAW
        ];

        if (!in_array($params['type'], $types)) {
            throw new HttpBadRequestException($request, 'Invalid type.');
        }

        if (empty($params['destination']) && ($params['type'] == self::TYPE_DEPOSIT || $params['type'] == self::TYPE_TRANSFER)) {
            throw new HttpBadRequestException($request, 'Destination ID required.');
        }

        if (empty($params['origin']) && ($params['type'] == self::TYPE_WITHDRAW || $params['type'] == self::TYPE_TRANSFER)) {
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
                throw new HttpNotFoundException($request);
            }
        }

        return [
            'type' => $params['type'],
            'amount' => (float)$params['amount'],
            'origin' => (int)($params['origin'] ?? null),
            'destination' => (int)($params['destination'] ?? null),
            'originObj' => $origin,
            'destinationObj' => $destination
        ];
    }
}
