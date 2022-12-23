<?php

namespace App\Repositories\TradeRepositories;

use App\Database;
use App\Services\CryptoCurrency\Trade\TradeCryptoServiceRequest;
use Carbon\Carbon;

class ClosingRepository
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

    public function checkIfEnoughMoney(): bool
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userBalance = $queryBuilder
            ->select('money')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchOne();

        return $userBalance >= $this->amount * $this->price;
    }

    public function checkIfEnoughCoins(): bool
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userCoins = $queryBuilder
            ->select('amount')
            ->from('coins')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, $this->symbol)
            ->fetchAllAssociative();

        $totalCoins = 0;
        foreach ($userCoins as $coin) {
            $totalCoins += $coin['amount'];
        }

        return -$totalCoins >= $this->amount;
    }

    public function removeShortAndAddTransaction(): void
    {
        $neededAmount = $this->amount;
        $totalOriginalPrice = 0;
        $queryBuilder = Database::getConnection()->createQueryBuilder();

        while($neededAmount)
        {
            $userShorts = $queryBuilder
                ->select('*')
                ->from('coins')
                ->where('user_id = ?')
                ->andWhere('symbol = ?')
                ->setParameter(0, $_SESSION['auth_id'])
                ->setParameter(1, $this->symbol)
                ->fetchAssociative();

            if (-$userShorts['amount'] <= $neededAmount) {
                $neededAmount += $userShorts['amount'];
                $totalOriginalPrice -= $userShorts['amount'] * $userShorts['price'];

                Database::getConnection()->delete('coins', ['id' => $userShorts['id']]);

            } else {
                $totalOriginalPrice -= $neededAmount * $userShorts['price'];
                $queryBuilder
                    ->update('coins')
                    ->set('amount', '?')
                    ->where('id = ?')
                    ->setParameter(0, $neededAmount + $userShorts['amount'])
                    ->setParameter(1, $userShorts['id'])
                    ->executeQuery();
                $neededAmount = 0;
            }
        }

        $profit = $this->amount * $this->price - $totalOriginalPrice;
        $this->addTransaction($profit);
    }

    public function updateBalance(): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();

        $userBalance = $queryBuilder
            ->select('money')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchOne();

        $queryBuilder
            ->update('users')
            ->set('money', '?')
            ->where('id = ?')
            ->setParameter(0, $userBalance - $this->amount * $this->price)
            ->setParameter(1, $_SESSION['auth_id'])
            ->executeQuery();
    }

    private function addTransaction(float $profit)
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('transactions')
            ->values([
                'user_id' => '?',
                'symbol' => '?',
                'amount' => '?',
                'price' => '?',
                'profit_loss' => '?',
                'action' => '?',
                'date' => '?',
            ])
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, $this->symbol)
            ->setParameter(2, $this->amount)
            ->setParameter(3, $this->price)
            ->setParameter(4, $profit)
            ->setParameter(5, 'close')
            ->setParameter(6, Carbon::now('Europe/Riga'))
            ->executeQuery();
    }
}