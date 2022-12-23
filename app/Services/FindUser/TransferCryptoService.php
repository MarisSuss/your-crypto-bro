<?php

namespace App\Services\FindUser;

use App\Repositories\Profile\MySqlTransferCryptoRepository;

class TransferCryptoService
{
    public function execute(array $post): void
    {
        $request = new TransferCryptoRequest(
            $post['id'],
            $post['amount'],
            $post['symbol'],
            $post['password']
        );
        $repository = new MySqlTransferCryptoRepository($request);
        if (!$repository->confirmPassword()) {
            $_SESSION['errors']['transfer'] = 'Wrong password';
            return;
        }
        if (!$repository->checkIfEnoughCoins()) {
            $_SESSION['errors']['transfer'] = 'Not enough coins';
            return;
        }
        $repository->transferCryptoAndSaveTransaction();
    }
}