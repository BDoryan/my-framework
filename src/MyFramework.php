<?php

namespace MyFramework;

use MyFramework\Component\Component;
use MyFramework\Error\ErrorHandler;
use MyFramework\Logger\Logger;
use MyFramework\Router\Router;
use MyFramework\Template\Template;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class MyFramework
{

    public static Logger $logger;
    public static Router $router;

    public static function initialize(): void
    {
        self::$router = new Router();
        Template::setRootPath(__DIR__ . '/../templates');

        Component::registerComponents(__DIR__ . '/../templates/components');

        self::initializeLogger();
        self::loadErrorHandlers();
        self::loadEnvironmentVariables();
        self::loadControllersRoutes();

        self::$router->dispatch();
    }

    private static function loadErrorHandlers(): void
    {
        ErrorHandler::register();
    }

    private static function loadControllersRoutes(): void
    {
        $root_dirs = [
            __DIR__,
        ];

        foreach (get_declared_classes() as $className) {
            if (is_subclass_of($className, 'MyFramework\Controller\Controller')) {
                $ref = new ReflectionClass($className);
                if ($ref->isAbstract()) continue;

                if ($ref->hasMethod('routes')) {
                    $method = $ref->getMethod('routes');
                    if ($method->isStatic() && $method->isPublic()) {
                        $className::routes(self::$router);
                    }
                }
            }
        }
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
