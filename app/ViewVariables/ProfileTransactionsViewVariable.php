<?php

namespace App\ViewVariables;

use App\Database;

class ProfileTransactionsViewVariable implements ViewVariables
{
    public function getName(): string
    {
        return 'transactions';
    }

    public function getValue(): array
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $transactions = $queryBuilder
            ->select('*')
            ->from('transactions')
            ->where('user_id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchAllAssociative();

        return [
            'symbol' => $transactions['symbol'],
            'date' => $transactions['date'],
            'price' => $transactions['price'],
            'amount' => $transactions['amount'],
            'action' => $transactions['action'],
            'profit_loss' => $transactions['profit_loss']
        ];
    }
}