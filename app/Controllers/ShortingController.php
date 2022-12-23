<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\Services\CryptoCurrency\Trade\Shorting\ClosingService;
use App\Services\CryptoCurrency\Trade\Shorting\ShortingService;
use App\Services\Validator;


class ShortingController
{
    public function shorting(): Redirect
    {
        (new Validator)->validateTrade($_POST['short']);
        if ($_SESSION['errors']['trade']) {
            return new Redirect('/crypto/' . $_POST['symbol']);
        }

        (new ShortingService)->execute($_POST);
        return new Redirect('/crypto/' . $_POST['symbol']);
    }

    public function closing(): Redirect
    {
        (new Validator)->validateTrade($_POST['close']);
        if ($_SESSION['errors']['trade']) {
            return new Redirect('/crypto/' . $_POST['symbol']);
        }

        (new ClosingService())->execute($_POST);
        return new Redirect('/crypto/' . $_POST['symbol']);
    }
}