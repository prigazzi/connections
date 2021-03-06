<?php
declare(strict_types=1);

use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;

require_once __DIR__.'/../../../vendor/autoload.php';
$container = include_once __DIR__.'/../etc/container.php';
$middlewareQueue = include_once __DIR__.'/../etc/middleware.php';

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

$emitter = new SapiEmitter();
$emitter->emit($response);
