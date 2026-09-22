<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

function url(string $path): string
{
    $base = defined('BASE_URL') ? BASE_URL : '';

    if ($path === '' || $path === '/') {
        return $base === '' ? '/' : $base . '/';
    }

    return $base . $path;
}

$detectedBase = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_URL', $detectedBase === '/' ? '' : $detectedBase);

$uri = $_SERVER['REQUEST_URI'];
if (BASE_URL !== '' && strpos($uri, BASE_URL) === 0) {
    $uri = substr($uri, strlen(BASE_URL));
}
if ($uri === '' || $uri[0] !== '/') {
    $uri = '/' . ltrim($uri, '/');
}

/** @var Router $router */
$router = require __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);