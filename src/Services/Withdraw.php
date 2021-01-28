<?php

namespace App\Services;

use App\Models\Account;

class Withdraw
{
    public static function new(Account $origin, float $amount)
    {
        $origin->setBalance($origin->getBalance() - $amount);
        $origin->update();
    }
}
