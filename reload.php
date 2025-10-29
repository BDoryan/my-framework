<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

$project_root = __DIR__;

// Keep the watch list short to minimize filesystem work between polls.
$watch_paths = [
    "{$project_root}/index.php",
    "{$project_root}/Router.php",
    "{$project_root}/components",
    "{$project_root}/pages",
    "{$project_root}/resources",
    "{$project_root}/experiments",
];

// Skip folders that change often but are irrelevant for template reloads.
$ignored_directories = ['.git', '.idea', 'vendor', 'node_modules'];

/**
 * Returns the newest file modification timestamp across the given paths.
 */
function getLatestTimestamp(array $paths, array $ignored_directories): int
{
    $latest_timestamp = 0;

    foreach ($paths as $path) {
        if (!file_exists($path)) {
            continue;
        }

        if (is_file($path)) {
            $latest_timestamp = max($latest_timestamp, (int) filemtime($path));
            continue;
        }

        $directory_iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($directory_iterator as $fs_item) {
            $item_path = $fs_item->getPathname();

            if (shouldIgnorePath($item_path, $ignored_directories)) {
                continue;
            }

            $latest_timestamp = max($latest_timestamp, (int) $fs_item->getMTime());
        }
    }

    return $latest_timestamp;
}

/**
 * Determines whether the given path should be ignored.
 */
function shouldIgnorePath(string $path, array $ignored_directories): bool
{
    $normalized_path = str_replace('\\', '/', $path);

    foreach ($ignored_directories as $ignored_directory) {
        $needle = '/' . $ignored_directory;

        if (strpos($normalized_path, $needle . '/') !== false) {
            return true;
        }

        $needle_length = strlen($needle);
        if ($needle_length <= strlen($normalized_path) && substr($normalized_path, -$needle_length) === $needle) {
            return true;
        }
    }

    return false;
}

$last_modified = getLatestTimestamp($watch_paths, $ignored_directories);

echo json_encode([
    'lastModified' => $last_modified,
    'generatedAt' => time(),
]);
