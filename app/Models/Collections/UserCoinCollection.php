<?php

namespace App\Models\Collections;

use App\Models\userCoin;

class UserCoinCollection
{
    private array $userCoins = [];

    public function __construct(array $userCoins = [])
    {
        foreach ($userCoins as $userCoin) {
            $this->userCoins[] = $userCoin;
        }
    }

    public function addUserCoin(userCoin $userCoin): void
    {
        $this->userCoins[] = $userCoin;
    }

    public function all(): array
    {
        return $this->userCoins;
    }
}