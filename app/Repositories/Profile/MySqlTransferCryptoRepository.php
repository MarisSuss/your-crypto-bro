<?php

namespace App\Repositories\Profile;

use App\Database;
use App\Services\FindUser\TransferCryptoRequest;
use Carbon\Carbon;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class MySqlTransferCryptoRepository
{
    private TransferCryptoRequest $request;
    private Connection $connection;
    private QueryBuilder $queryBuilder;


    public function __construct(TransferCryptoRequest $request)
    {
        $this->request = $request;

        $this->connection = Database::getConnection();
        $this->queryBuilder = $this->connection->createQueryBuilder();
        // this query builder is acting strange and for now I don't know why
    }

    public function transferCryptoAndSaveTransaction()
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $connection = Database::getConnection();

        $neededCoins = $this->request->getAmount();
        $totalOriginalPrice = 0;

        while($neededCoins) {
            $cryptoCoin = $queryBuilder
                ->select('*')
                ->from('coins')
                ->where('user_id = ?')
                ->andWhere('symbol = ?')
                ->setParameter(0, $_SESSION['auth_id'])
                ->setParameter(1, $this->request->getSymbol())
                ->fetchAssociative();

            if ($cryptoCoin['amount'] <= $neededCoins) {
                $neededCoins -= $cryptoCoin['amount'];
                $totalOriginalPrice += $cryptoCoin['amount'] * $cryptoCoin['price'];

                $this->addCoinToOtherUser(
                    $cryptoCoin['symbol'],
                    $cryptoCoin['price'],
                    $cryptoCoin['amount'],
                    $this->request->getId()
                );

                $connection->delete('coins', ['id' => $cryptoCoin['id']]);

            } else {
                $totalOriginalPrice += $neededCoins * $cryptoCoin['price'];
                $queryBuilder
                    ->update('coins')
                    ->set('amount', '?')
                    ->where('id = ?')
                    ->setParameter(0, $cryptoCoin['amount'] - $neededCoins)
                    ->setParameter(1, $cryptoCoin['id'])
                    ->executeQuery();

                $this->addCoinToOtherUser(
                    $cryptoCoin['symbol'],
                    $cryptoCoin['price'],
                    $neededCoins,
                    $this->request->getId()
                );

                $neededCoins = 0;
            }
        }
        $pricePerCoin = $totalOriginalPrice / $this->request->getAmount();
        $this->saveTransaction($pricePerCoin, $_SESSION['auth_id'], 'sent');
        $this->saveTransaction($pricePerCoin, $this->request->getId(), 'received');
    }

    public function checkIfEnoughCoins(): bool
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $coins = $queryBuilder
            ->select('*')
            ->from('coins')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, $this->request->getSymbol())
            ->fetchAllAssociative();

        $coinAmount = 0;
        foreach ($coins as $coin) {
            $coinAmount += $coin['amount'];
        }

        return $coinAmount >= $this->request->getAmount();
    }

    public function confirmPassword(): bool
    {
        $user = $this->queryBuilder
            ->select('password')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchAssociative();
        return password_verify($this->request->getPassword(), $user['password']);
    }

    private function addCoinToOtherUser(string $symbol, float $price, float $amount, int $userId): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('coins')
            ->values([
                'user_id' => '?',
                'symbol' => '?',
                'price' => '?',
                'amount' => '?',
            ])
            ->setParameter(0, $userId)
            ->setParameter(1, $symbol)
            ->setParameter(2, $price)
            ->setParameter(3, $amount)
            ->executeQuery();
    }

    private function saveTransaction(float $pricePerCoin, int $userID, string $action): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('transactions')
            ->values([
                'symbol' => '?',
                'date' => '?',
                'action' => '?',
                'amount' => '?',
                'price' => '?',
                'user_id' => '?',
            ])
            ->setParameter(0, $this->request->getSymbol())
            ->setParameter(1, Carbon::now('Europe/Riga'))
            ->setParameter(2, $action)
            ->setParameter(3, $this->request->getAmount())
            ->setParameter(4, $pricePerCoin)
            ->setParameter(5, $userID)
            ->executeQuery();
    }
}