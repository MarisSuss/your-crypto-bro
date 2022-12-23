<?php

namespace App\Controllers;

use App\Database;
use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Services\Register\RegisterService;
use App\Services\Register\RegisterServiceRequest;

class RegisterController
{
    public function index(): View
    {
        return View::render('profileViews/register.twig', []);
    }

    public function register(): Redirect
    {
        $request = new RegisterServiceRequest(
            $_POST['name'],
            $_POST['email'],
            $_POST['password']
        );

        if ($_POST['password'] !== $_POST['password_confirmation']) {
            $_SESSION['errors']['register'] = 'Password does not match confirmation';
            return new Redirect('/register');
        }

       // To do: validate email

        (new RegisterService())->execute($request);

        return new Redirect('/login');
    }
}