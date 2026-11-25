<?php

// Vercel Serverless Entry Point for Laravel
putenv('VERCEL=1');
$_ENV['VERCEL'] = '1';
$_SERVER['VERCEL'] = '1';

// Setup directories in /tmp for Vercel's read-only filesystem
$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Copy pre-seeded SQLite database to /tmp if it doesn't already exist
$sourceDb = __DIR__ . '/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (file_exists($sourceDb) && !file_exists($targetDb)) {
    @copy($sourceDb, $targetDb);
} elseif (!file_exists($targetDb)) {
    @touch($targetDb);
}

putenv("DB_CONNECTION=sqlite");
putenv("DB_DATABASE={$targetDb}");
putenv("VIEW_COMPILED_PATH=/tmp/storage/framework/views");
putenv("CACHE_STORE=array");
putenv("SESSION_DRIVER=cookie");
putenv("LOG_CHANNEL=stderr");

// Forward incoming request to Laravel's public front controller
require __DIR__ . '/../public/index.php';
