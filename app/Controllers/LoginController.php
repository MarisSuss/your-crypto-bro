<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;

class LoginController
{
    public function index(): View
    {
        return View::render('profileViews/login.twig', []);
    }
    public function login(): Redirect
    {
        return new Redirect('/');
    }
}