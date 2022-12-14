<?php

namespace App\Services\CryptoCurrency\trade;

class TradeCryptoServiceRequest
{
    private string $cryptoSymbol;
    private float $cryptoPrice;
    private int $cryptoAmount;

    public function __construct(string $cryptoSymbol, float $cryptoPrice, int $cryptoAmount)
    {
        $this->cryptoSymbol = $cryptoSymbol;
        $this->cryptoPrice = $cryptoPrice;
        $this->cryptoAmount = $cryptoAmount;
    }

    public function getCryptoSymbol(): string
    {
        return $this->cryptoSymbol;
    }

    public function getCryptoPrice(): float
    {
        return $this->cryptoPrice;
    }

    public function getCryptoAmount(): int
    {
        return $this->cryptoAmount;
    }
}