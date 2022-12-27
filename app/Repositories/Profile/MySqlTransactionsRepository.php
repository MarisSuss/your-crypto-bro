<?php

namespace App\Repositories\Profile;

use App\Database;
use App\Models\Collections\TransactionsCollection;
use App\Models\Transaction;

class MySqlTransactionsRepository
{
    public function fetchAllTransactions(): TransactionsCollection
    {
        $transactionCollection = [];
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userTransactions = $queryBuilder
            ->select('*')
            ->from('transactions')
            ->where('user_id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchAllAssociative();



        foreach ($userTransactions as $transaction) {
            $transactionCollection[] = new Transaction(
                $transaction['action'],
                $transaction['amount'],
                $transaction['date'],
                $transaction['symbol'],
                $transaction['price'],
                $transaction['profit_loss'] ?? 0
            );
        }

        $transactionsCollectionInReverseOrder = array_reverse($transactionCollection);

        return new TransactionsCollection($transactionsCollectionInReverseOrder);
    }

    public function checkIfSymbolHasTransactions(string $symbol): bool
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userTransactions = $queryBuilder
            ->select('*')
            ->from('transactions')
            ->where('user_id = ?')
            ->andWhere('symbol = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->setParameter(1, $symbol)
            ->fetchAllAssociative();
        return !empty($userTransactions);
    }
}