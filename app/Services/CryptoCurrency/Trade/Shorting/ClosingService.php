<?php

namespace App\Services\CryptoCurrency\Trade\Shorting;

use App\Repositories\CoinMarketCapCryptoCurrenciesRepository;
use App\Repositories\TradeRepositories\ClosingRepository;
use App\Services\CryptoCurrency\Trade\TradeCryptoServiceRequest;

class ClosingService
{
    public function execute(array $post): void
    {
        if (!$_SESSION['auth_id']) {
            $_SESSION['errors']['closing'] = 'Login first!';
            return;
        }

        $price = (new CoinMarketCapCryptoCurrenciesRepository)->fetchBySymbol($post['symbol'])->getPrice();
        $request = new TradeCryptoServiceRequest(
            $post['symbol'],
            $post['close'],
            $price
        );

        $repository = new ClosingRepository($request);

        if (!$repository->checkIfEnoughMoney()) {
            $_SESSION['errors']['closing'] = 'Not enough money to close!';
            return;
        }
        if (!$repository->checkIfEnoughCoins()) {
            $_SESSION['errors']['closing'] = 'Not enough coins to close!';
            return;
        }

        $repository->removeShortAndAddTransaction();
        $repository->updateBalance();
    }
}