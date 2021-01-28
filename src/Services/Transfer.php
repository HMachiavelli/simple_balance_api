<?php

namespace App\Services;

use App\Models\Account;

class Transfer
{
    public static function new(Account $origin, Account $destination, float $amount): array
    {
        $origin->setBalance($origin->getBalance() - $amount);
        $origin->update();

        $destination->setBalance($destination->getBalance() + $amount);
        $destination->update();

        return [
            'origin' => $origin->toArray(),
            'destination' => $destination->toArray()
        ];
    }
}
