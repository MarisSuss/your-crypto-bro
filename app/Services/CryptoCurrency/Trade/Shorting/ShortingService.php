<?php

namespace App\Services\CryptoCurrency\Trade\Shorting;

use App\Repositories\CoinMarketCapCryptoCurrenciesRepository;
use App\Repositories\TradeRepositories\ShortingRepository;
use App\Services\CryptoCurrency\Trade\TradeCryptoServiceRequest;

class ShortingService
{
    public function execute(array $post): void
    {
        if (!$_SESSION['auth_id']) {
            $_SESSION['errors']['shorting'] = 'Login first!';
            return;
        }

        $price = (new CoinMarketCapCryptoCurrenciesRepository)->fetchBySymbol($post['symbol'])->getPrice();
        $request = new TradeCryptoServiceRequest(
            $post['symbol'],
            $post['short'],
            $price
        );

        $repository = new ShortingRepository($request);
        if ($repository->checkIfCoinAlreadyOwned()) {
            $_SESSION['errors']['shorting'] = 'Sell coins in your wallet first!';
            return;
        }
        $repository->addShortAndTransaction();
        $repository->updateUserBalance();
    }
}