<?php

namespace App;

class Session
{
    private static array $session = [];

    public static function initialize(): void
    {
        session_start();
        self::$session = $_SESSION;
    }

    public static function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return isset(self::$session[$key]);
    }
}