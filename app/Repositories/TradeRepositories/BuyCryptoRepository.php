<?php

namespace App\Repositories\TradeRepositories;

use App\Database;
use App\Services\CryptoCurrency\trade\TradeCryptoServiceRequest;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Carbon\Carbon;

class BuyCryptoRepository
{
    private TradeCryptoServiceRequest $request;
    private float $userMoney;
    private float $coinPrice;
    private float $coinAmount;
    private string $coinSymbol;
    private float $totalPrice;
    private Connection $connection;
    private QueryBuilder $builder;

    public function __construct(TradeCryptoServiceRequest $request)
    {
        $this->request = $request;

        $this->connection = Database::getConnection();
        $this->builder = $this->connection->createQueryBuilder();

        $this->userMoney = $this->builder
            ->select('money')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchOne();

        $this->coinPrice = $request->getCryptoPrice();

        $this->coinAmount = $request->getCryptoAmount();

        $this->coinSymbol = $request->getCryptoSymbol();

        $this->totalPrice = $this->coinPrice * $this->coinAmount;
    }

    public function checkIfEnoughMoneyIsAvailable(): bool
    {
        return $this->userMoney - $this->totalPrice > 0;
    }

    public function removeMoneyFromUser(): void
    {
        $this->builder
            ->update('users')
            ->set('money', '?')
            ->where('id = ?')
            ->setParameter(0, $this->userMoney - $this->totalPrice)
            ->setParameter(1, $_SESSION['auth_id'])
            ->executeQuery();
    }

    public function addCoinsToUser(): void
    {
        $this->builder
            ->insert('coins')
            ->values([
                'symbol' => '?',
                'price' => '?',
                'amount' => '?',
                'user_id' => '?',
            ])
            ->setParameter(0, $this->coinSymbol)
            ->setParameter(1, $this->coinPrice)
            ->setParameter(2, $this->coinAmount)
            ->setParameter(3, $_SESSION['auth_id'])
            ->executeQuery();
    }

    public function addTransaction(): void
    {
        $this->builder
            ->insert('transactions')
            ->values([
                'symbol' => '?',
                'date' => '?',
                'action' => '?',
                'amount' => '?',
                'price' => '?',
                'user_id' => '?',
            ])
            ->setParameter(0, $this->coinSymbol)
            ->setParameter(1, Carbon::now('Europe/Riga'))
            ->setParameter(2, 'buy')
            ->setParameter(3, $this->coinAmount)
            ->setParameter(4, $this->coinPrice)
            ->setParameter(5, $_SESSION['auth_id'])
            ->executeQuery();
    }
}