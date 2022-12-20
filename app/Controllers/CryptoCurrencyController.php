<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Services\CryptoCurrency\ListCryptoCurrenciesService;
use App\Services\CryptoCurrency\ShowCryptoCurrencyService;
use App\Services\CryptoCurrency\trade\BuyCryptoService;
use App\Services\CryptoCurrency\trade\SellCryptoService;

class CryptoCurrencyController
{
    private ListCryptoCurrenciesService $listCryptoCurrenciesService;
    private ShowCryptoCurrencyService $showCryptoCurrencyService;

    public function __construct(
        ListCryptoCurrenciesService $listCryptoCurrenciesService,
        ShowCryptoCurrencyService   $showCryptoCurrencyService
    )
    {
        $this->listCryptoCurrenciesService = $listCryptoCurrenciesService;
        $this->showCryptoCurrencyService = $showCryptoCurrencyService;
    }

    public function index(): View
    {
        $cryptoCurrencies = $this->listCryptoCurrenciesService->execute(
            ['BTC', 'ETH', 'XRP', 'DOT', 'DOGE', 'LTC', 'BCH', 'ADA', 'BNB', 'SRM']
        );

        return View::render('CryptoCurrencies/index.twig', [
            'cryptoCurrencies' => $cryptoCurrencies->all()
        ]);
    }

    public function show(array $vars): View
    {
        return View::render('CryptoCurrencies/show.twig', [
            'cryptoCurrency' => $this->showCryptoCurrencyService->execute($vars['symbol'])
        ]);
    }

    public function send(): View
    {
        return View::render('CryptoCurrencies/show.twig', [
            'cryptoCurrency' => $this->showCryptoCurrencyService->execute($_POST['search'])
        ]);
    }

    public function trade(array $vars): Redirect
    {

        if ($_POST['buy']) {
            (new BuyCryptoService)->index($_POST);
        } else {
            (new SellCryptoService)->index($_POST);
        }

        return new Redirect('/crypto/' . $vars['symbol']);
    }
}