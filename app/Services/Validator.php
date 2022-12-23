<?php

namespace App\Services;

class Validator
{
    public static function validateTrade($tradeAmount): void
    {
        if ($tradeAmount < 0) {
            $_SESSION['errors']['trade'] = 'Trade amount must be greater than 0';
        }
        if (!is_numeric($tradeAmount)) {
            $_SESSION['errors']['trade'] = 'Trade amount must be a number';
        }
    }
}