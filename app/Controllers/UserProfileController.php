<?php

namespace App\Controllers;

use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Services\FindUser\FindUserService;
use App\Services\FindUser\TransferCryptoService;
use App\Services\Profile\ListTransactionsService;
use App\Services\Profile\ListUserCoinsService;

class UserProfileController
{
    private ListTransactionsService $listTransactionsService;
    private ListUserCoinsService $listUserCoinsService;
    private FindUserService $findUserService;
    private TransferCryptoService $transferCryptoService;

    public function __construct(
        ListTransactionsService $listTransactionsService,
        ListUserCoinsService $listUserCoinsService,
        FindUserService $findUserService,
        TransferCryptoService $transferCryptoService
    )
    {
        $this->listTransactionsService = $listTransactionsService;
        $this->listUserCoinsService = $listUserCoinsService;
        $this->findUserService = $findUserService;
        $this->transferCryptoService = $transferCryptoService;
    }

    public function index(): View
    {
        // $transactions = $this->listTransactionsService->execute();
        $userCoins = $this->listUserCoinsService->execute();
        return View::render('profileViews/userProfile.twig', ['userCoins' => $userCoins->all()]);
    }

    public function userWallet(): View
    {
        $userCoins = $this->listUserCoinsService->execute();
        return View::render('profileViews/userProfile.twig', ['userCoins' => $userCoins->all()]);
    }

    public function send(): Redirect
    {
        return new Redirect('/profile/' . $_POST['user']);
    }

    public function findUser($vars)
    {
        $findUser = $this->findUserService->execute($vars);
        if ($_SESSION['errors']['findUser']) {
            return new Redirect('/profile');
        }
        return View::render('profileViews/findUser.twig', ['findUser' => $findUser]);
    }

    public function transferCrypto($vars): Redirect
    {
        $this->transferCryptoService->execute($_POST);
        return new Redirect('/profile/' . $vars['user']);
    }
}