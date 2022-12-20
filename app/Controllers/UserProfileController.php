<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;

class UserProfileController
{
    public function index(): View
    {
        return View::render('profileViews/userProfile.twig', []);
    }

    public function send(): Redirect
    {
        return new Redirect('/profile/' . $_POST['user']);
    }

    public function findUser(): View
    {
        return View::render('profileViews/findUser.twig', []);
    }
}