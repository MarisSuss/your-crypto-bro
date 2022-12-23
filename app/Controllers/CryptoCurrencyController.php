<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Services\CryptoCurrency\ListCryptoCurrenciesService;
use App\Services\CryptoCurrency\ShowCryptoCurrencyService;
use App\Services\CryptoCurrency\Trade\BuyCryptoService;
use App\Services\CryptoCurrency\Trade\SellCryptoService;
use App\Services\Profile\ListTransactionsService;

class CryptoCurrencyController
{
    private ListCryptoCurrenciesService $listCryptoCurrenciesService;
    private ShowCryptoCurrencyService $showCryptoCurrencyService;
    private ListTransactionsService $listTransactionsService;

    public function __construct(
        ListCryptoCurrenciesService $listCryptoCurrenciesService,
        ShowCryptoCurrencyService   $showCryptoCurrencyService,
        ListTransactionsService $listTransactionsService
    )
    {
        $this->listCryptoCurrenciesService = $listCryptoCurrenciesService;
        $this->showCryptoCurrencyService = $showCryptoCurrencyService;
        $this->listTransactionsService = $listTransactionsService;
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
        $validator = $this->listTransactionsService->checkIfSymbolHasTransactions($vars['symbol']);

        if ($validator) {
            return View::render('CryptoCurrencies/show.twig', [
                'cryptoCurrency' => $this->showCryptoCurrencyService->execute($vars['symbol']),
                'transactions' => $this->listTransactionsService->execute()->all()
            ]);
        }

        return View::render('CryptoCurrencies/show.twig', [
            'cryptoCurrency' => $this->showCryptoCurrencyService->execute($vars['symbol']),
            'transactions' => $validator
        ]);
    }

    public function send(): Redirect
    {
        return new Redirect('/crypto/' . $_POST['search']);
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