<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\Services\CryptoCurrency\Trade\Shorting\ClosingService;
use App\Services\CryptoCurrency\Trade\Shorting\ShortingService;


class ShortingController
{
    public function shorting(): Redirect
    {
        (new ShortingService)->execute($_POST);
        return new Redirect('/crypto/' . $_POST['symbol']);
    }

    public function closing(): Redirect
    {
        (new ClosingService())->execute($_POST);
        return new Redirect('/crypto/' . $_POST['symbol']);
    }
}