<?php

namespace App\Services;

use App\Models\Account;

class Deposit
{
    public static function new(Account $destination, float $amount): array
    {
        $destination->setBalance($destination->getBalance() + $amount);
        $destination->update();

        return [
            'destination' => $destination->toArray()
        ];
    }
}
