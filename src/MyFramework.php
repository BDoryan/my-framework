<?php

namespace MyFramework;

use MyFramework\Logger\Logger;
use MyFramework\Router\Router;

class MyFramework
{

    public static Logger $logger;
    public static Router $router;

    public static function initialize(): void
    {
        self::loadEnvironmentVariables();
        self::initializeLogger();
    }

    private static function intializeRouter(): void
    {
        self::$router = new Router();
    }

    private static function initializeLogger(): void
    {
        $logger_directory = self::resolveLogDirectory();
        $file_output = $logger_directory . '/app.log';
        self::$logger = new Logger($file_output);
    }

    private static function resolveLogDirectory(): string
    {
        $paths = [];

        $env_path = getenv('LOG_DIRECTORY');
        if ($env_path) {
            $paths[] = $env_path;
        }

        $paths[] = __DIR__ . '/../logs';
        $paths[] = sys_get_temp_dir() . '/my-framework/logs';

        foreach ($paths as $path) {
            if (!$path) {
                continue;
            }

            $path = rtrim($path, DIRECTORY_SEPARATOR);

            if (!is_dir($path) && !@mkdir($path, 0777, true)) {
                continue;
            }

            if (!is_writable($path) && !@chmod($path, 0777)) {
                continue;
            }

            return $path;
        }

        throw new \RuntimeException('Unable to locate a writable directory for application logs.');
    }

    private static function loadEnvironmentVariables(): void
    {
        $env_file = __DIR__ . '/../.env';
        if ($env_file) {
            if (file_exists($env_file)) {
                $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) {
                        continue;
                    }
                    list($name, $value) = explode('=', $line, 2);
                    putenv(sprintf('%s=%s', trim($name), trim($value)));
                }
            }
        }
    }

    /**
     * Return true if the application is running in production mode.
     *
     * @return bool
     */
    public static function isProductionMode(): bool
    {
        return strtolower(getenv('MY_FRAMEWORK_ENVIRONMENT') ?? '') === 'production';
    }

    /**
     * Return true if the application is running in development mode.
     *
     * @return bool
     */
    public static function isDevelopmentMode(): bool
    {
        return strtolower(getenv('MY_FRAMEWORK_ENVIRONMENT') ?? '') === 'development';
    }
}
