<?php

// Get the root path of the project
$rootPath = dirname(__DIR__, 3);

// Define paths to watch for changes
return [
    $rootPath . '/src',
    $rootPath . '/templates',
    $rootPath . '/public/resources',
];
