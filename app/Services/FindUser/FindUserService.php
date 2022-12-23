<?php

namespace App\Services\FindUser;

use App\Database;
use App\Models\OtherUser;

class FindUserService
{
    public function execute(string $name): ?OtherUser
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $userInfo = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('name = ?')
            ->setParameter(0, $name)
            ->fetchAssociative();

        if (!$userInfo) {
            $_SESSION['errors']['findUser'] = 'User not found';
            return null;
        }

        return new OtherUser($userInfo['name'], (int) $userInfo['id']);
    }
}