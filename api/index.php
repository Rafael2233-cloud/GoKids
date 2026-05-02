<?php
// 1. Bikin folder temporary (karena Vercel itu Read-Only)
$tmpDirs = ['/tmp/views', '/tmp/cache', '/tmp/sessions', '/tmp/logs'];
foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 2. Terusin jalan ke mesin utama Laravel
require __DIR__ . '/../public/index.php';