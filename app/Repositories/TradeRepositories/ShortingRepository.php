<?php

namespace App\Repositories\TradeRepositories;

use App\Database;
use App\Services\CryptoCurrency\trade\TradeCryptoServiceRequest;
use Carbon\Carbon;

class ShortingRepository
{
    private TradeCryptoServiceRequest $request;
    private float $price;
    private string $symbol;
    private float $amount;


    public function __construct(TradeCryptoServiceRequest $request)
    {
        $this->request = $request;
        $this->price = $request->getCryptoPrice();
        $this->symbol = $request->getCryptoSymbol();
        $this->amount = $request->getCryptoAmount();
    }

    public function addShortAndTransaction(): void
    {
        $this->addShort();
        $this->addTransaction();
    }

    public function checkIfCoinAlreadyOwned(): bool
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userCoins = $queryBuilder
            ->select('amount')
            ->from('coins')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, $this->symbol)
            ->fetchOne();
        return $userCoins == true && $userCoins > 0;
    }

    public function updateUserBalance(): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userMoney = $queryBuilder
            ->select('money')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchOne();

        $queryBuilder
            ->update('users')
            ->set('money', '?')
            ->where('id = ?')
            ->setParameter(0, $userMoney + $this->amount * $this->price)
            ->setParameter(1, $_SESSION['auth_id'])
            ->executeQuery();
    }

    private function addShort(): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('coins')
            ->values([
                'user_id' => '?',
                'symbol' => '?',
                'amount' => '?',
                'price' => '?'
            ])
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, $this->symbol)
            ->setParameter(2, -$this->amount)
            ->setParameter(3, $this->price)
            ->executeQuery();
    }

    private function addTransaction(): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('transactions')
            ->values([
                'user_id' => '?',
                'symbol' => '?',
                'amount' => '?',
                'price' => '?',
                'action' => '?',
                'date' => '?'
            ])
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, $this->symbol)
            ->setParameter(2, $this->amount)
            ->setParameter(3, $this->price)
            ->setParameter(4, 'short')
            ->setParameter(5, Carbon::now('Europe/Riga'))
            ->executeQuery();
    }
}