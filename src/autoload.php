<?php

// Composer autoload file
require __DIR__ . '/../vendor/autoload.php';

// Framework autoload file
require __DIR__ . '/../src/functions.php';

if (!is_dir(__DIR__))
    throw new Exception("Directory not found: " . __DIR__);

$php_files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $php_files[] = $file->getPathname();
    }
}

foreach ($php_files as $file)
    require_once $file;
