<?php

namespace App\Services\CryptoCurrency\Trade;

use App\Repositories\CoinMarketCapCryptoCurrenciesRepository;
use App\Repositories\TradeRepositories\BuyCryptoRepository;


class BuyCryptoService
{
    public function index(array $post): void
    {
        if (!$_SESSION['auth_id']) {
            $_SESSION['errors']['buy'] = 'Login first!';
            return;
        }
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