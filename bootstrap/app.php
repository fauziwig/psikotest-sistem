<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Deteksi environment serverless (Vercel / AWS Lambda) via getenv, $_SERVER, $_ENV
$isServerless = getenv('VERCEL') !== false
    || getenv('VIEW_COMPILED_PATH') !== false
    || getenv('AWS_LAMBDA_FUNCTION_NAME') !== false
    || isset($_SERVER['VERCEL'])
    || isset($_ENV['VERCEL']);

if ($isServerless) {
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
}

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/admin/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

if ($isServerless) {
    $app->useStoragePath('/tmp/storage');
}

return $app;
