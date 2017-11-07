<?php

declare(strict_types=1);

require '../vendor/autoload.php';

use PozytywneInicjatywy\Dashboard\Kernel;
use Symfony\Component\HttpFoundation\Request;

$kernel = new Kernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
