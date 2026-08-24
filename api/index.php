<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup folder /tmp/storage yang writable di Vercel Serverless
$storagePath = '/tmp/storage';
$dirs = [
    $storagePath,
    $storagePath . '/app',
    $storagePath . '/app/public',
    $storagePath . '/framework',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache',
    '/tmp/views',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Register Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel
/** @var Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';

// Set storage path ke /tmp/storage
$app->useStoragePath($storagePath);

// Handle HTTP Request
$app->handleRequest(Request::capture());
