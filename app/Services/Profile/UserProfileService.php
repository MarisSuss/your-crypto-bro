<?php

namespace App\Services\Profile;

class UserProfileService
{
    public function getUserProfile(int $id): array
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();

        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'money' => $user['money']
        ];
    }
}