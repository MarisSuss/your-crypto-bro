<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Services\CryptoCurrency\ListCryptoCurrenciesService;
use App\Services\CryptoCurrency\ShowCryptoCurrencyService;
use App\Services\CryptoCurrency\Trade\BuyCryptoService;
use App\Services\CryptoCurrency\Trade\SellCryptoService;
use App\Services\Profile\ListTransactionsService;
use App\Services\Validator;

class CryptoCurrencyController
{
    private ListCryptoCurrenciesService $listCryptoCurrenciesService;
    private ShowCryptoCurrencyService $showCryptoCurrencyService;
    private ListTransactionsService $listTransactionsService;
    private Validator $validator;

    public function __construct(
        ListCryptoCurrenciesService $listCryptoCurrenciesService,
        ShowCryptoCurrencyService   $showCryptoCurrencyService,
        ListTransactionsService $listTransactionsService,
        Validator $validator
    )
    {
        $this->listCryptoCurrenciesService = $listCryptoCurrenciesService;
        $this->showCryptoCurrencyService = $showCryptoCurrencyService;
        $this->listTransactionsService = $listTransactionsService;
        $this->validator = $validator;
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
        $this->showCryptoCurrencyService->execute($_POST['search']);
        if ($_SESSION['errors']['search']) {
            return new Redirect('/crypto');
        }
        return new Redirect('/crypto/' . $_POST['search']);
    }

    public function trade(array $vars): Redirect
    {
        if ($_POST['buy']) {
            $this->validator->validateTrade($_POST['buy']);
            if ($_SESSION['errors']['trade']) {
                return new Redirect('/crypto/' . $vars['symbol']);
            }

            (new BuyCryptoService)->index($_POST);
        } else {
            $this->validator->validateTrade($_POST['sell']);
            if ($_SESSION['errors']['trade']) {
                return new Redirect('/crypto/' . $vars['symbol']);
            }

            (new SellCryptoService)->index($_POST);
        }

        return new Redirect('/crypto/' . $vars['symbol']);
    }
}