<?php

namespace App\Repositories\Profile;

use App\Database;
use App\Models\Collections\UserCoinCollection;
use App\Models\UserCoin;

class MySqlUserCoinsRepository
{
    public function fetchAllUserCoins(): UserCoinCollection
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userCoins = $queryBuilder
            ->select('*')
            ->from('coins')
            ->where('user_id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchAllAssociative();

        $userCoinsGathered = [];

        foreach ($userCoins as $userCoin) {
            if ( in_array($userCoin['symbol'], $userCoinsGathered) ) {
                continue;
            } else {
                $userCoinsGathered[$userCoin['symbol']]['symbol'] = $userCoin['symbol'];
                $userCoinsGathered[$userCoin['symbol']]['amount'] += $userCoin['amount'];
                $userCoinsGathered[$userCoin['symbol']]['price'] += $userCoin['price'] * $userCoin['amount'];
            }
        }

        $userCoinsCollection = [];

        foreach ($userCoinsGathered as $coinBySymbol) {
            $userCoinsCollection[] = new UserCoin(
                $coinBySymbol['symbol'],
                $coinBySymbol['amount'],
                $coinBySymbol['price'] / $coinBySymbol['amount']
            );
        }

        return new UserCoinCollection($userCoinsCollection);
    }
}