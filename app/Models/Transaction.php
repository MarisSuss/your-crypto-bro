<?php

namespace App\Models;

class Transaction
{
    private string $action;
    private float $amount;
    private string $date;
    private string $symbol;
    private float $price;
    private float $profit;

    public function __construct(
         string $action,
         float  $amount,
         string $date,
         string $symbol,
         float $price,
         float $profit
    )
    {
        $this->action = $action;
        $this->amount = $amount;
        $this->date = $date;
        $this->symbol = $symbol;
        $this->price = $price;
        $this->profit = $profit;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getProfit(): float
    {
        return $this->profit;
    }
}