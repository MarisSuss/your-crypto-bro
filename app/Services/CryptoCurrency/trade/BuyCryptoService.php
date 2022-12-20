<?php

namespace App\Services\CryptoCurrency\trade;

use App\Repositories\CoinMarketCapCryptoCurrenciesRepository;
use App\Repositories\tradeRepositories\BuyCryptoRepository;


class BuyCryptoService
{
    public function index(array $post): void
    {
        $price = (new CoinMarketCapCryptoCurrenciesRepository)->fetchBySymbol($post['symbol'])->getPrice();

        $request = new TradeCryptoServiceRequest(
            $post['symbol'],
            $post['buy'],
            $price
        );

       $repository = new BuyCryptoRepository($request);

        if (!$repository->checkIfEnoughMoneyIsAvailable()) {
            $_SESSION['errors']['buy'] = 'Not enough money';
            return;
        }

        $repository->removeMoneyFromUser();
        $repository->addCoinsToUser();
        $repository->addTransaction();
    }
}