<?php

namespace App\Controllers;

use App\DataTransferObjects\View;

class UserProfileController
{
    public function index(): View
    {
        return View::render('profileViews/userProfile.twig', []);
    }
}