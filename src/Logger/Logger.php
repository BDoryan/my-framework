<?php

namespace MyFramework\Logger;

class Logger
{
    public const COLORS = [
        'reset' => "\033[0m",
        'black' => "\033[0;30m",
        'red' => "\033[0;31m",
        'green' => "\033[0;32m",
        'yellow' => "\033[0;33m",
        'blue' => "\033[0;34m",
        'magenta' => "\033[0;35m",
        'cyan' => "\033[0;36m",
        'white' => "\033[0;37m",
        'bold' => "\033[1m",
    ];

    public const LEVELS = [
        'DEBUG' => 'cyan',
        'INFO' => 'green',
        'WARNING' => 'yellow',
        'ERROR' => 'red',
    ];

    private string $file_output;
    private bool $debug = false;
    private int $last_message_length = 0;
    private string $prefix = '';
    private bool $display_time = true;

    public function __construct(?string $file_output = null, $debug = false, $root_directory_permission = 0770, $root_file_permission = 0660)
    {
        if ($file_output === null) {
            throw new \InvalidArgumentException('Logger requires a writable file path.');
        }

        $this->debug = $debug;
        $dir = dirname($file_output);

        if (!is_dir($dir) && !@mkdir($dir, $root_directory_permission, true)) {
            throw new \RuntimeException(sprintf('Unable to create log directory "%s".', $dir));
        }

        if (!is_writable($dir) && !@chmod($dir, $root_directory_permission)) {
            throw new \RuntimeException(sprintf('Log directory "%s" is not writable.', $dir));
        }

        if (!file_exists($file_output)) {
            if (@touch($file_output) === false) {
                throw new \RuntimeException(sprintf('Unable to create log file "%s".', $file_output));
            }

            @chmod($file_output, $root_file_permission);
        }

        if (!is_writable($file_output)) {
            throw new \RuntimeException(sprintf('Log file "%s" is not writable.', $file_output));
        }

        $this->file_output = $file_output;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getPrefix(): string
    {
        $prefix = $this->prefix;
        if (!empty($prefix))
            return "{$prefix} ";
        return '';
    }

    private function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    public function log(string $level, string $message, bool $inline = false): void
    {
        $level = strtoupper($level);
        $log = '';
        if (array_key_exists($level, self::LEVELS)) {
            $color = self::COLORS[self::LEVELS[$level]] ?? self::COLORS['reset'];
            $reset = self::COLORS['reset'];

            $newline = $inline ? "\r" : PHP_EOL;

            $time = date('H:i:s');
            $output = "{$color}[{$level}] [{$time}] " . self::getPrefix() . "{$message}{$reset}";

            if ($inline) {
                $output = str_pad($output, $this->last_message_length, ' ', STR_PAD_RIGHT);
                $log = "{$output}{$newline}";
            } else {
                $clearLine = str_repeat(' ', $this->last_message_length) . "\r";
                $log = "{$clearLine}{$output}{$newline}";
            }

            $this->last_message_length = strlen($output);
        } else {
            $log = "[UNKNOWN LEVEL] " . $this->prefix . "{$message}" . PHP_EOL;
        }

        if ($this->isCli())
            echo $log;

        $this->logToFile($log);
    }

    private function logToFile(string $log): void
    {
        $log = str_replace(array_values(self::COLORS), '', $log);
        $log = str_replace(["\n"], '', $log);
        file_put_contents($this->file_output, $log, FILE_APPEND);
    }

    public function debug(string $message, bool $inline = false): void
    {
        if (!$this->debug) {
            return;
        }
        $this->log('DEBUG', $message, $inline);
    }

    public function info(string $message, bool $inline = false): void
    {
        $this->log('INFO', $message, $inline);
    }

    public function warning(string $message, bool $inline = false): void
    {
        $this->log('WARNING', $message, $inline);
    }

    public function error(string $message, bool $inline = false): void
    {
        $this->log('ERROR', $message, $inline);
    }

    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }
}
