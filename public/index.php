<?php

require_once '../vendor/autoload.php';

use App\Controllers\CryptoCurrencyController;
use App\Controllers\LoginController;
use App\Controllers\RegisterController;
use App\DataTransferObjects\Redirect;
use App\DataTransferObjects\View;
use App\Session;

Session::initialize();

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
  //  'cache' => '/path/to/compilation_cache',
]);

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', [CryptoCurrencyController::class, 'index']);

    $r->addRoute('GET', '/login', [LoginController::class, 'index']);
    $r->addRoute('POST', '/login', [LoginController::class, 'login']);

    $r->addRoute('GET', '/register', [RegisterController::class, 'index']);
    $r->addRoute('POST', '/register', [RegisterController::class, 'register']);
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

        $response = (new $controller)->$method($vars);

        if($response instanceof View) {
            echo $twig->render($response->getPath(), $response->getData());
        }
        if ($response instanceof Redirect) {
            header('Location: ' . $response->getUrl());
        }

        break;
}