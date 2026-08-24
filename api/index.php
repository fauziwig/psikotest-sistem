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

// Autoload Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    http_response_code(500);
    die("<h1>Server Error (500)</h1><p>Composer autoloader tidak ditemukan.</p>");
}

try {
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Pastikan storage path menggunakan /tmp/storage
    $app->useStoragePath($storagePath);

    // Proses request
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    error_log("LARAVEL VERCEL ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    
    // Tampilkan detail exception di browser jika terjadi error
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>500 Server Error</title>';
    echo '<style>body{font-family:system-ui,-apple-system,sans-serif;padding:30px;background:#f8fafc;color:#1e293b}';
    echo '.box{background:white;padding:24px;border-radius:12px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.1)}';
    echo 'h1{color:#ef4444;margin-top:0}pre{background:#f1f5f9;padding:12px;border-radius:8px;overflow-x:auto;font-size:12px}</style></head><body>';
    echo '<div class="box"><h1>500 Server Error (Diagnostik Vercel)</h1>';
    echo '<p><strong>Pesan Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ' (Baris ' . $e->getLine() . ')</p>';
    echo '<h3>Stack Trace:</h3><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre></div></body></html>';
}
