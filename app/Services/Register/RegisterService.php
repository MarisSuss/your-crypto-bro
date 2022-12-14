<?php

namespace App\Services\Register;

use App\Database;

class RegisterService
{
    public function execute(RegisterServiceRequest $request): void
    {
        Database::getConnection()->insert(
            'users', [
                'name' => $request->getName(),
                'email' => $request->getEmail(),
                'password' => password_hash($request->getPassword(), PASSWORD_DEFAULT),
            ]
        );
    }
}