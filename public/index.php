<?php

require_once '../vendor/autoload.php';

use App\Controllers\CryptoCurrencyController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\RegisterController;
use App\Controllers\ShortingController;
use App\Controllers\UserProfileController;
use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Repositories\CryptoCurrenciesRepository;
use App\Session;
use App\ViewVariables\AuthViewVariables;
use App\ViewVariables\BalanceViewVariable;
use App\ViewVariables\ErrorsViewVariable;

Session::initialize();

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
    //  'cache' => '/path/to/compilation_cache',
]);

$container = new DI\Container();
$container->set(
    CryptoCurrenciesRepository::class,
    \Di\create(\App\Repositories\CoinMarketCapCryptoCurrenciesRepository::class)
);

$authVariables = [
    AuthViewVariables::class,
    ErrorsViewVariable::class,
    BalanceViewVariable::class,
];

foreach ($authVariables as $variable) {
    /** @var \App\ViewVariables\ViewVariables $variable */
    $variable = new $variable;
    $twig->addGlobal($variable->getName(), $variable->getValue());
}

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [CryptoCurrencyController::class, 'index']);
    $r->addRoute('GET', '/crypto', [CryptoCurrencyController::class, 'index']);

    $r->addRoute('POST', '/', [CryptoCurrencyController::class, 'send']);
    $r->addRoute('POST', '/crypto', [CryptoCurrencyController::class, 'send']);
    $r->addRoute('GET', '/crypto/{symbol}', [CryptoCurrencyController::class, 'show']);
    $r->addRoute('POST', '/crypto/{symbol}', [CryptoCurrencyController::class, 'trade']);


    $r->addRoute('POST', '/shorting', [ShortingController::class, 'shorting']);
    $r->addRoute('POST', '/closing', [ShortingController::class, 'closing']);

    $r->addRoute('GET', '/login', [LoginController::class, 'index']);
    $r->addRoute('POST', '/login', [LoginController::class, 'login']);

    $r->addRoute('GET', '/logout', [LogoutController::class, 'index']);
    $r->addRoute('POST', '/logout', [LogoutController::class, 'logout']);

    $r->addRoute('GET', '/register', [RegisterController::class, 'index']);
    $r->addRoute('POST', '/register', [RegisterController::class, 'register']);

    $r->addRoute('GET', '/profile', [UserProfileController::class, 'index']);
    $r->addRoute('GET', '/wallet', [UserProfileController::class, 'userWallet']);
    $r->addRoute('POST', '/profile', [UserProfileController::class, 'send']);

    $r->addRoute('GET', '/profile/{user}', [UserProfileController::class, 'findUser']);
    $r->addRoute('POST', '/profile/{user}', [UserProfileController::class, 'transferCrypto']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = $handler;

        $response = $container->get($controller)->$method($vars);

        if ($response instanceof View) {
            echo $twig->render($response->getPath(), $response->getData());
            unset($_SESSION['errors']);
        }
        if ($response instanceof Redirect) {
            header('Location: ' . $response->getUrl());
        }

        break;
}