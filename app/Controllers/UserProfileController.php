<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Services\Profile\ListTransactionsService;

class UserProfileController
{
    private ListTransactionsService $listTransactionsService;

    public function __construct(ListTransactionsService $listTransactionsService)
    {
        $this->listTransactionsService = $listTransactionsService;
    }

    public function index(): View
    {
        $transactions = $this->listTransactionsService->execute();
        return View::render('profileViews/userProfile.twig', ['transactions' => $transactions->all()]);
    }

    public function userWallet(): View
    {
        return View::render('profileViews/userWallet.twig', []);
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