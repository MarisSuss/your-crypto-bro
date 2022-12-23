<?php

namespace App\Repositories\tradeRepositories;

use App\Database;
use App\Services\CryptoCurrency\trade\TradeCryptoServiceRequest;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class SellCryptoRepository
{
    private TradeCryptoServiceRequest $request;
    private float $totalSellPrice;
    private float $totalCoins;
    private string $symbol;
    private float $pricePerCoin;
    private Connection $connection;
    private QueryBuilder $builder;

    public function __construct(TradeCryptoServiceRequest $request)
    {
        $this->request = $request;

        $this->totalSellPrice = $this->request->getCryptoAmount() * $this->request->getCryptoPrice();
        $this->totalCoins = $this->request->getCryptoAmount();
        $this->symbol = $this->request->getCryptoSymbol();
        $this->pricePerCoin = $this->request->getCryptoPrice();

        $this->connection = Database::getConnection();
        $this->builder = $this->connection->createQueryBuilder();
    }

    public function checkIfEnoughCoinsAreAvailable(): bool
    {
        return $this->getUserCoinAmount() - $this->totalCoins >= 0;
    }

    public function addMoneyToUser(): void
    {
        $this->connection->update(
            'users',
            ['money' => $this->getUserMoney() + $this->totalSellPrice],
            ['id' => $_SESSION['auth_id']]
        );
    }

    public function removeCoinsAndAddTransaction(): void
    {
        $neededCoins = $this->totalCoins;
        $totalOriginalPrice = 0;

        while ($neededCoins) {
            $cryptoCoin = $this->builder
                ->select('*')
                ->from('coins')
                ->where('user_id = ?')
                ->andWhere('symbol = ?')
                ->setParameter(0, $_SESSION['auth_id'])
                ->setParameter(1, $this->symbol)
                ->fetchAssociative();

            if ($cryptoCoin['amount'] <= $neededCoins) {

                $neededCoins -= $cryptoCoin['amount'];
                $totalOriginalPrice += $cryptoCoin['amount'] * $cryptoCoin['price'];
                $this->connection->delete('coins', ['id' => $cryptoCoin['id']]);

            } else {
                $totalOriginalPrice = $neededCoins * $cryptoCoin['price'];
                $this->builder
                    ->update('coins')
                    ->set('amount', '?')
                    ->where('id = ?')
                    ->setParameter(0, $cryptoCoin['amount'] - $neededCoins)
                    ->setParameter(1, $cryptoCoin['id'])
                    ->executeQuery();

                $neededCoins = 0;
            }

        }
        $this->addTransaction($this->totalSellPrice, $totalOriginalPrice);
    }

    private function addTransaction(float $totalSellPrice, float $totalOriginalPrice): void
    {
        $profitLoss = $totalOriginalPrice - $totalSellPrice;

        $this->builder
            ->insert('transactions')
            ->values([
                'symbol' => '?',
                'date' => '?',
                'action' => '?',
                'amount' => '?',
                'price' => '?',
                'profit_loss' => '?',
                'user_id' => '?'
            ])
            ->setParameter(0, $this->symbol)
            ->setParameter(1, Carbon::now('Europe/Riga'))
            ->setParameter(2, 'sell')
            ->setParameter(3, $this->totalCoins)
            ->setParameter(4, $this->pricePerCoin)
            ->setParameter(5, $profitLoss)
            ->setParameter(6, $_SESSION['auth_id'])
            ->executeQuery();
    }

    private function getUserCoinAmount(): float
    {
        $allCoinAmount = $this->builder
            ->select('amount')
            ->from('coins')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, $this->symbol)
            ->fetchAllAssociative();

        $sum = 0;
        foreach ($allCoinAmount as $coin) {
            $sum += $coin['amount'];
        }
        return $sum;
    }

    private function getUserMoney(): float
    {
        return $this->connection->fetchAssociative(
            'SELECT money FROM users WHERE id = ?',
            [$_SESSION['auth_id']]
        )['money'];
    }
}