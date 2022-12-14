<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;

class LogoutController
{
    public function index(): View
    {
        return View::render('profileViews/logout.twig', []);
    }

    public function logout(): Redirect
    {
        unset($_SESSION['auth_id']);
        return new Redirect('/');
    }
}