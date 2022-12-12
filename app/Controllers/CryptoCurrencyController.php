<?php

namespace App\Controllers;

use App\DataTransferObjects\View;
use App\Services\CryptoCurrency\ListCryptoCurrenciesService;

class CryptoCurrencyController
{
    public function index(): View
    {
        $service = new ListCryptoCurrenciesService();
        $cryptoCurrencies = $service->execute(['BTC', 'ETH', 'XRP', 'DOT', 'DOGE', 'LTC', 'BCH', 'ADA', 'BNB', 'SRM']);

        return View::render('CryptoCurrencies/index.twig', [
            'cryptoCurrencies' => $cryptoCurrencies->all()
        ]);
    }
}