<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Services\Login\LoginService;
use App\Services\Login\LoginServiceRequest;
use App\Validation;

class LoginController
{
    public function index(): View
    {
        return View::render('profileViews/login.twig', []);
    }

    public function login(): Redirect
    {
        (new LoginService())->execute(new LoginServiceRequest($_POST['email'], $_POST['password']));

        if (! empty($_SESSION['errors'])) {
            return new Redirect('/login');
        }
        return new Redirect('/');
    }

    public function logout(): Redirect
    {
        unset($_SESSION['auth_id']);
        return new Redirect('/');
    }
}