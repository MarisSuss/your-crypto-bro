<?php

namespace App\Services\CryptoCurrency\trade;

use App\Repositories\tradeRepositories\BuyCryptoRepository;

class BuyCryptoService
{
    public function index(array $post): void
    {
        $request = new TradeCryptoServiceRequest(
            $post['Symbol'],
            $post['Price'],
            $post['Amount']
        );

        $repository = new BuyCryptoRepository($request);

        if (!$repository->checkIfEnoughMoneyIsAvailable()) {
            $_SESSION['errors']['login'] = 'Not enough money';
        }
        // validate that enough money is available
        // remove money from user
        // add to coins
        // add to transactions

        var_dump($post);
    }
}