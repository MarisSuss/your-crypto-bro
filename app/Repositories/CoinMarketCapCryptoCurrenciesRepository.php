<?php

namespace App\Repositories;

use App\Models\Collections\CryptoCurrenciesCollection;
use App\Models\CryptoCurrency;
use GuzzleHttp\Client;

class CoinMarketCapCryptoCurrenciesRepository implements CryptoCurrenciesRepository
{
    private const API_URL = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/';

    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client(['base_uri' => self::API_URL]);
    }

    public function fetchAllBySymbols(array $symbols): CryptoCurrenciesCollection
    {
        $response = $this->httpClient->request('GET', 'quotes/latest', [
            'query' => [
                'symbol' => implode(',', $symbols),
                'convert' => 'USD'
            ],
            'headers' => [
                'Accepts' => 'application/json',
                'X-CMC_PRO_API_KEY' => $_ENV['COIN_MARKET_CAP_API_KEY']
            ]
        ]);

        $response = json_decode($response->getBody()->getContents());

        $cryptoCurrencies = new CryptoCurrenciesCollection();

        foreach ($response->data as $currency) {
            $cryptoCurrencies->add(
                new CryptoCurrency(
                    $currency->symbol,
                    $currency->name,
                    $currency->quote->USD->price,
                    $currency->quote->USD->percent_change_1h,
                    $currency->quote->USD->percent_change_24h,
                    $currency->quote->USD->percent_change_7d
                )
            );
        }

        return $cryptoCurrencies;
    }
}