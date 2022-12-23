<?php

namespace App\Services\Login;

use App\Database;

class LoginService
{
    public function execute(LoginServiceRequest $request): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $request->getEmail())
            ->fetchAssociative();

        if (!$user) {
            $_SESSION['errors']['login'] = 'Login failed';
        }
        if ($user && password_verify($request->getPassword(), $user['password'])) {
            $_SESSION['auth_id'] = $user['id'];
        } else {
            $_SESSION['errors']['login'] = 'Login failed';
        }
    }
}