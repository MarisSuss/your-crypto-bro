<?php

namespace App\Models;

class UserCoin
{
    private string $symbol;
    private float $amount;
    private float $originalPriceAverage;

    public function __construct(string $symbol, float $amount, float $originalPriceAverage)
    {
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->originalPriceAverage = $originalPriceAverage;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getOriginalPriceAverage(): float
    {
        return $this->originalPriceAverage;
    }
}