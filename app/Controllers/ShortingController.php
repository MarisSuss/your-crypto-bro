<?php

namespace App\Controllers;

use App\DataTransferObjects\View;

class ShortingController
{
    public function index(): View
    {
        return View::render('CryptoCurrencies/shorting.twig', []);
    }
}