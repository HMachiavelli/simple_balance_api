<?php

namespace App\Models;

class Account extends ApplicationModel
{
    protected $balance;

    public function __construct(?array $params = [])
    {
        parent::__construct();
        $this->fill('account', $params);
    }

    /**
     * Get the value of balance
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set the value of balance
     *
     * @return  self
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }
}
