<?php

namespace App\Services\CryptoCurrency;

use App\Models\Collections\CryptoCurrenciesCollection;
use App\Repositories\CoinMarketCapCryptoCurrenciesRepository;
use App\Repositories\CryptoCurrenciesRepository;

class ListCryptoCurrenciesService
{
    private CryptoCurrenciesRepository $cryptoCurrenciesRepository;
    public function __construct()
    {
        $this->cryptoCurrenciesRepository = new CoinMarketCapCryptoCurrenciesRepository();
    }

    public function execute(array $symbols): CryptoCurrenciesCollection
    {
        return $this->cryptoCurrenciesRepository->fetchAllBySymbols($symbols);
    }

}