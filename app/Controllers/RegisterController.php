<?php

namespace App\Controllers;

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
        if (! empty($_SESSION['errors'])) {
            return new Redirect('/register');
        }

        $registerService = new RegisterService();
        $registerService->execute(
            new RegisterServiceRequest(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            )
        );

        return new Redirect('/login');
    }
}