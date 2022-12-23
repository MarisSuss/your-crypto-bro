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

    public function checkIfSymbolHasTransactions(string $symbol): bool
    {
        return $this->mySqlTransactionsRepository->checkIfSymbolHasTransactions($symbol);
    }

    public function execute(): ?TransactionsCollection
    {
        return $this->mySqlTransactionsRepository->fetchAllTransactions();
    }
}