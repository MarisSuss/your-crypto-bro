<?php

namespace App\Services\CryptoCurrency\Trade;

use App\Repositories\CoinMarketCapCryptoCurrenciesRepository;
use App\Repositories\TradeRepositories\SellCryptoRepository;

class SellCryptoService
{
    public function index(array $post): void
    {
        if (!$_SESSION['auth_id']) {
            $_SESSION['errors']['sell'] = 'Login first!';
            return;
        }
        $price = (new CoinMarketCapCryptoCurrenciesRepository)->fetchBySymbol($post['symbol'])->getPrice();

        $post['sell'] = (float)$post['sell'];

        $request = new TradeCryptoServiceRequest(
            $post['symbol'],
            $post['sell'] ?? 0,
            $price
        );

        $repository = new SellCryptoRepository($request);

        if (!$repository->checkIfEnoughCoinsAreAvailable()) {
            $_SESSION['errors']['sell'] = 'Not enough coins';
            return;
        }

        $repository->removeCoinsAndAddTransaction();
        $repository->addMoneyToUser();
    }
}