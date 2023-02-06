<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Strategy\JsonStrategy;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$container = new League\Container\Container();
$container->delegate(new League\Container\ReflectionContainer(cacheResolutions: $_ENV['APP_DEBUG']));

$request = ServerRequestFactory::fromGlobals();

$responseFactory = new ResponseFactory();

$strategy = new JsonStrategy($responseFactory);
$strategy->setContainer($container);

$router = (new League\Route\Router)->setStrategy($strategy);
$router->map('GET', '/', \App\Controller\IndexController::class);

$response = $router->dispatch($request);

(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

