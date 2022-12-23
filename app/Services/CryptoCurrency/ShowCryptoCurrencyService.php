<?php

namespace App\Services\CryptoCurrency;

use App\Models\CryptoCurrency;
use App\Repositories\CryptoCurrenciesRepository;

class ShowCryptoCurrencyService
{
    private CryptoCurrenciesRepository $repository;

    public function __construct(CryptoCurrenciesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $symbol): ?CryptoCurrency
    {
        return $this->repository->fetchBySymbol($symbol);
    }
}