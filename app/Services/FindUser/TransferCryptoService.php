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
            return;
        }
        if (!$repository->checkIfEnoughCoins()) {
            return;
        }
        $repository->transferCryptoAndSaveTransaction();
    }
}