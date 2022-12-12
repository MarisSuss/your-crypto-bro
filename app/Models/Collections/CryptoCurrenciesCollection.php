<?php

namespace App\Models\Collections;

use App\Models\CryptoCurrency;

class CryptoCurrenciesCollection
{
    private array $cryptoCurrencies = [];

    public function __construct(array $cryptoCurrencies = [])
    {
        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $this->add($cryptoCurrency);
        }
    }

    public function add(CryptoCurrency $cryptoCurrency): void
    {
        $this->cryptoCurrencies[] = $cryptoCurrency;
    }

    public function all(): array
    {
        return $this->cryptoCurrencies;
    }
}