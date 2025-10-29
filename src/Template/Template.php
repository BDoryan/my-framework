<?php

namespace MyFramework\Template;

class Template
{

    private static ?string $root_path;

    /**
     * Load a PHP template file and return its output as a string.
     *
     * @param string $path
     * @param array $data
     * @return string
     */
    public static function loadTemplate(string $path, array $data = []): string
    {
        $path = ltrim($path, DIRECTORY_SEPARATOR);
        extract($data);

        // Add extension if not present
        if (pathinfo($path, PATHINFO_EXTENSION) === '')
            $path .= '.php';

        ob_start();
        $file_path = self::$root_path . $path;
        if(file_exists($file_path)) {
            include $file_path;
        } else {
            throw new \RuntimeException("Template file not found: " . $file_path);
        }
        return ob_get_clean();
    }

    /**
     * Load a PHP template file and print its output directly.
     *
     * @param string $path
     * @param array $data
     * @return void
     */
    public static function printTemplate(string $path, array $data = []): void
    {
        echo self::loadTemplate($path, $data);
    }

    /**
     * Set the root path for template files.
     *
     * @param string $path
     * @return void
     */
    public static function setRootPath(string $path): void
    {
        self::$root_path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
}