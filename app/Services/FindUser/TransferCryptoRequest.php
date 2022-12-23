<?php

namespace App\Services\FindUser;

class TransferCryptoRequest
{
    private string $id;
    private string $amount;
    private string $symbol;
    private string $password;

    public function __construct(
        string $id,
        string $amount,
        string $symbol,
        string $password
    )
    {

        $this->id = $id;
        $this->amount = $amount;
        $this->symbol = $symbol;
        $this->password = $password;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}