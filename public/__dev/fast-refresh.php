<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

// Load watch paths from configuration
$watch_paths = require_once __DIR__ . '/config/watch_path.php';

// Get the last modification time sent by the client
$client_last_mod_time = isset($_GET['client_last_mod_time']) ? (int)$_GET['client_last_mod_time'] : 0;
$last_mod_time = null;
foreach ($watch_paths as $path) {
    if (!is_dir($path)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile()) {
            $mod_time = $fileInfo->getMTime();
            if ($mod_time > $client_last_mod_time) {
                $last_mod_time = $mod_time;
            }
        }
    }
}

if(!empty($last_mod_time)){
    echo json_encode([
        'reload' => true,
        'last_mod_time' => $last_mod_time
    ]);
    exit;
}

// No changes detected
echo json_encode(['reload' => false, 'last_mod_time' => $client_last_mod_time]);
