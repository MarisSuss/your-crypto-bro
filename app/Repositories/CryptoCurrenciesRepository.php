<?php

namespace App\Repositories;

use App\Models\Collections\CryptoCurrenciesCollection;
use App\Models\CryptoCurrency;

interface CryptoCurrenciesRepository
{
    public function fetchAllBySymbols(array $symbols): CryptoCurrenciesCollection;
    public function fetchBySymbol(string $symbol): CryptoCurrency;
}