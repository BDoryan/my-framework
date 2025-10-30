<?php

namespace MyFramework\Error;

use MyFramework\MyFramework;
use MyFramework\Router\Router;
use MyFramework\Template\Template;

class ErrorHandler
{
    private static bool $registered = false;
    private static bool $handling = false;

    /**
     * Register the framework error, exception and shutdown handlers.
     */
    public static function register(): void
    {
        if (self::$registered) {
            return;
        }

        ini_set('display_errors', '0');

        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);

        self::$registered = true;
    }

    /**
     * Convert PHP errors into ErrorException so they can be handled uniformly.
     */
    public static function handleError(int $severity, string $message, string $file = '', int $line = 0): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        self::handleException(new \ErrorException($message, 0, $severity, $file, $line));

        return true;
    }

    /**
     * Handle uncaught exceptions and render the custom error template.
     */
    public static function handleException(\Throwable $exception): void
    {
        if (self::$handling) {
            self::fallbackRender($exception);
            return;
        }

        self::$handling = true;

        self::logException($exception);
        self::cleanOutputBuffers();

        $status_code = http_response_code();
        if ($status_code < 400) {
            $status_code = 500;
            http_response_code($status_code);
        }

        $is_dev = MyFramework::isDevelopmentMode();
        $request_path = Router::getRequestPath();
        $exception_chain = self::normalizeException($exception);

        $display_message = $is_dev
            ? $exception->getMessage()
            : 'Une erreur est survenue lors du traitement de votre requête.';

        $status_label = self::statusLabel($status_code);

        try {
            if (!headers_sent()) {
                header('Content-Type: text/html; charset=UTF-8');
            }

            $content = Template::loadTemplate('/partials/error', [
                'status_code' => $status_code,
                'status_label' => $status_label,
                'message' => $display_message,
                'request_path' => $request_path,
                'is_dev' => $is_dev,
                'exceptions' => $exception_chain,
            ]);

            Template::printTemplate('page', [
                'title' => ($status_code >= 500 ? 'Erreur serveur' : 'Erreur') . ' - ' . $status_code,
                'description' => 'Vue d\'erreur personnalisée de MyFramework.',
                'content' => $content,
                'lang' => 'fr',
            ]);
        } catch (\Throwable $renderException) {
            self::fallbackRender($exception, $renderException);
        }

        self::$handling = false;
        exit(1);
    }

    /**
     * Handle fatal errors that are only visible during shutdown.
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error === null) {
            return;
        }

        $fatal_errors = [
            E_ERROR,
            E_PARSE,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_USER_ERROR,
        ];

        if (!in_array($error['type'], $fatal_errors, true)) {
            return;
        }

        self::handleException(new \ErrorException(
            $error['message'] ?? 'Fatal error',
            0,
            $error['type'],
            $error['file'] ?? 'unknown file',
            $error['line'] ?? 0
        ));
    }

    /**
     * Normalize the exception data so it can easily be rendered in templates.
     */
    private static function normalizeException(\Throwable $exception): array
    {
        $chain = [];
        $current = $exception;

        while ($current !== null) {
            $chain[] = [
                'class' => get_class($current),
                'message' => $current->getMessage(),
                'file' => $current->getFile(),
                'line' => $current->getLine(),
                'trace' => self::formatTrace($current->getTrace()),
            ];

            $current = $current->getPrevious();
        }

        return $chain;
    }

    /**
     * Format stack trace frames for display.
     */
    private static function formatTrace(array $trace): array
    {
        $formatted = [];

        foreach ($trace as $index => $frame) {
            $callable = '';
            if (isset($frame['class'])) {
                $callable .= $frame['class'];
            }
            if (isset($frame['type'])) {
                $callable .= $frame['type'];
            }
            $callable .= $frame['function'] ?? '[closure]';

            $location = $frame['file'] ?? '[internal]';
            $line = $frame['line'] ?? 'n/a';
            $location .= ':' . $line;

            $formatted[] = [
                'index' => $index,
                'call' => $callable,
                'location' => $location,
            ];
        }

        return $formatted;
    }

    /**
     * Log the exception stack to the framework logger when available.
     */
    private static function logException(\Throwable $exception): void
    {
        $logger = MyFramework::$logger ?? null;
        if ($logger === null) {
            return;
        }

        $current = $exception;
        while ($current !== null) {
            $logger->error(sprintf(
                '%s: %s in %s:%d',
                get_class($current),
                $current->getMessage(),
                $current->getFile(),
                $current->getLine()
            ));

            $current = $current->getPrevious();
        }
    }

    /**
     * Clear any active output buffers to avoid mixing partial output with the error page.
     */
    private static function cleanOutputBuffers(): void
    {
        while (ob_get_level() > 0) {
            if (!@ob_end_clean()) {
                break;
            }
        }
    }

    /**
     * Render a minimal fallback when the templating engine fails.
     */
    private static function fallbackRender(\Throwable $exception, \Throwable $renderException = null): void
    {
        $message = "Une erreur critique est survenue.";

        $details = sprintf(
            "Exception initiale: %s: %s in %s:%d",
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );

        if ($renderException !== null) {
            $details .= sprintf(
                "\nErreur lors du rendu de la vue: %s: %s in %s:%d",
                get_class($renderException),
                $renderException->getMessage(),
                $renderException->getFile(),
                $renderException->getLine()
            );
        }

        if (!headers_sent()) {
            header('Content-Type: text/plain; charset=UTF-8');
        }

        http_response_code(500);

        echo $message . "\n\n" . $details;

        self::$handling = false;
        exit(1);
    }

    /**
     * Provide a friendly label for common HTTP status codes.
     */
    private static function statusLabel(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Requête invalide',
            401 => 'Authentification requise',
            403 => 'Accès refusé',
            404 => 'Page introuvable',
            405 => 'Méthode HTTP non autorisée',
            418 => 'Je suis une théière',
            422 => 'Entité non traitable',
            500 => 'Erreur interne du serveur',
            503 => 'Service indisponible',
            default => 'Erreur',
        };
    }
}

