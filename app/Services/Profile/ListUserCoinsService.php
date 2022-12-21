<?php

namespace App\Services\Profile;

use App\Models\Collections\UserCoinCollection;
use App\Repositories\Profile\MySqlUserCoinsRepository;

class ListUserCoinsService
{
    private MySqlUserCoinsRepository $mySqlUserCoinsRepository;

    public function __construct(MySqlUserCoinsRepository $mySqlUserCoinsRepository)
    {
        $this->mySqlUserCoinsRepository = $mySqlUserCoinsRepository;
    }

    public function execute(): UserCoinCollection
    {
        return $this->mySqlUserCoinsRepository->fetchAllUserCoins();
    }
}