<?php

namespace App\Models;

class CryptoCurrency
{
    private string $symbol;
    private string $name;
    private string $price;
    private string $percentChange1h;
    private string $percentChange24h;
    private string $percentChange7d;

    public function __construct(
        string $symbol,
        string $name,
        string $price,
        string $percentChange1h,
        string $percentChange24h,
        string $percentChange7d)
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->price = $price;
        $this->percentChange1h = $percentChange1h;
        $this->percentChange24h = $percentChange24h;
        $this->percentChange7d = $percentChange7d;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPercentChange1h(): string
    {
        return $this->percentChange1h;
    }

    public function getPercentChange7d(): string
    {
        return $this->percentChange7d;
    }

    public function getPercentChange24h(): string
    {
        return $this->percentChange24h;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setPercentChange1h(string $percentChange1h): void
    {
        $this->percentChange1h = $percentChange1h;
    }

    public function setPercentChange7d(string $percentChange7d): void
    {
        $this->percentChange7d = $percentChange7d;
    }

    public function setPercentChange24h(string $percentChange24h): void
    {
        $this->percentChange24h = $percentChange24h;
    }
}