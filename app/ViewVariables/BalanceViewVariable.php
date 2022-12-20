<?php

namespace App\ViewVariables;

use App\Database;

class BalanceViewVariable implements ViewVariables
{
    public function getName(): string
    {
        return 'balance';
    }

    public function getValue(): array
    {
        if (! isset($_SESSION['auth_id'])) {
            return [];
        }

        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchAssociative();

        return [
            'balance' => $user['money']
        ];
    }
}