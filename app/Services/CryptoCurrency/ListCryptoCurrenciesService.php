<?php

namespace App\Services\CryptoCurrency;

use App\Models\Collections\CryptoCurrenciesCollection;
use App\Repositories\CryptoCurrenciesRepository;

class ListCryptoCurrenciesService
{
    private CryptoCurrenciesRepository $repository;

    public function __construct(CryptoCurrenciesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(array $symbols): CryptoCurrenciesCollection
    {
        return $this->repository->fetchAllBySymbols($symbols);
    }

}