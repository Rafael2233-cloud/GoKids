<?php

// 1. Paksa semua jalur cache internal Laravel pindah ke /tmp
$tmp_paths = [
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_CONFIG_CACHE'   => '/tmp/config.php',
    'APP_ROUTES_CACHE'   => '/tmp/routes.php',
    'APP_EVENTS_CACHE'   => '/tmp/events.php',
    'VIEW_COMPILED_PATH' => '/tmp/views',
];

foreach ($tmp_paths as $key => $path) {
    $_ENV[$key] = $path;
    putenv("{$key}={$path}");
}

// 2. Bikin foldernya biar Vercel nggak kaget
$dirs = ['/tmp/views', '/tmp/cache', '/tmp/sessions', '/tmp/logs'];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 3. Lanjutin jalan ke mesin utama Laravel
require __DIR__ . '/../public/index.php';