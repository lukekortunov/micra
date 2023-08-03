<?php

declare(strict_types=1);

use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Strategy\JsonStrategy;
use LukeKortunov\Micra\Contracts\ExtensionInterface;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$container = new League\Container\Container();
$container->delegate(new League\Container\ReflectionContainer(cacheResolutions: (bool) $_ENV['APP_DEBUG']));

/** @var array<ExtensionInterface> $extensions */
$extensions = require_once __DIR__ . '/../config/extensions.php';

foreach ($extensions as $extension) {
    (new $extension())($container);
}

$request = ServerRequestFactory::fromGlobals();

$responseFactory = new ResponseFactory();

$strategy = new JsonStrategy($responseFactory);
$strategy->setContainer($container);

$router = (new League\Route\Router);
//$router->middleware(new \App\Middleware\AuthenticationMiddleware);
$router->setStrategy($strategy);
$router->map('GET', '/', \App\Controller\RockPaperScissorsController::class);

$response = $router->dispatch($request);

(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
