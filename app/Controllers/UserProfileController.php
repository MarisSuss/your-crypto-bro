<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Services\Profile\ListTransactionsService;
use App\Services\Profile\ListUserCoinsService;

class UserProfileController
{
    private ListTransactionsService $listTransactionsService;
    private ListUserCoinsService $listUserCoinsService;

    public function __construct(
        ListTransactionsService $listTransactionsService,
        ListUserCoinsService $listUserCoinsService
    )
    {
        $this->listTransactionsService = $listTransactionsService;
        $this->listUserCoinsService = $listUserCoinsService;
    }

    public function index(): View
    {
        $transactions = $this->listTransactionsService->execute();
        return View::render('profileViews/userProfile.twig', ['transactions' => $transactions->all()]);
    }

    public function userWallet(): View
    {
        $userCoins = $this->listUserCoinsService->execute();
        return View::render('profileViews/userWallet.twig', ['userCoins' => $userCoins->all()]);
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