<?php

namespace App\Repositories;

use App\Models\Collections\CryptoCurrenciesCollection;

interface CryptoCurrenciesRepository
{
    public function fetchAllBySymbols(array $symbols): CryptoCurrenciesCollection;
}