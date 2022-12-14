<?php

namespace App\Repositories\tradeRepositories;

use App\Services\CryptoCurrency\trade\TradeCryptoServiceRequest;

class BuyCryptoRepository
{
    private TradeCryptoServiceRequest $request;

    public function __construct(TradeCryptoServiceRequest $request)
    {
        $this->request = $request;
    }

    public function checkIfEnoughMoneyIsAvailable(): bool
    {

    }

    public function removeMoneyFromUser(): void
    {

    }

    public function addCoinsToUser(): void
    {

    }

    public function addTransaction(): void
    {

    }
}