<?php

namespace App\Services\Profile;

use App\Models\Collections\TransactionsCollection;
use App\Repositories\Profile\MySqlTransactionsRepository;

class ListTransactionsService
{
    private MySqlTransactionsRepository $mySqlTransactionsRepository;

    public function __construct(MySqlTransactionsRepository $mySqlTransactionsRepository)
    {
        $this->mySqlTransactionsRepository = $mySqlTransactionsRepository;
    }

    public function execute(): TransactionsCollection
    {
        return $this->mySqlTransactionsRepository->fetchAllTransactions();
    }
}