<?php

namespace App\Services\CryptoCurrency\trade;

class TradeCryptoServiceRequest
{
    private string $cryptoSymbol;
    private float $cryptoAmount;
    private float $cryptoPrice;

    public function __construct(string $cryptoSymbol, float $cryptoAmount, float $cryptoPrice)
    {
        $this->cryptoSymbol = $cryptoSymbol;
        $this->cryptoAmount = $cryptoAmount;
        $this->cryptoPrice = $cryptoPrice;
    }

    public function getCryptoSymbol(): string
    {
        return $this->cryptoSymbol;
    }

    public function getCryptoAmount(): float
    {
        return $this->cryptoAmount;
    }

    public function getCryptoPrice(): float
    {
        return $this->cryptoPrice;
    }
}