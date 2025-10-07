<?php

declare(strict_types=1);

namespace FastPix\Sdk\Utils;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Comprehensive logging utility for the FastPix SDK
 */
class Logger
{
    private const LOG_LEVELS = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
        'CRITICAL' => 4,
    ];

    private string $logLevel;
    private ?string $logFile;
    private bool $enableConsoleLogging;
    private array $context = [];

    public function __construct(
        string $logLevel = 'INFO',
        ?string $logFile = null,
        bool $enableConsoleLogging = false
    ) {
        $this->logLevel = strtoupper($logLevel);
        $this->logFile = $logFile;
        $this->enableConsoleLogging = $enableConsoleLogging;

        if (! isset(self::LOG_LEVELS[$this->logLevel])) {
            throw new \InvalidArgumentException('Invalid log level: '.$logLevel);
        }
    }

    /**
     * Set logging context
     */
    public function setContext(array $context): void
    {
        $this->context = array_merge($this->context, $context);
    }

    /**
     * Add to logging context
     */
    public function addContext(string $key, mixed $value): void
    {
        $this->context[$key] = $value;
    }

    /**
     * Log debug message
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log('DEBUG', $message, $context);
    }

    /**
     * Log info message
     */
    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    /**
     * Log warning message
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    /**
     * Log error message
     */
    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    /**
     * Log critical message
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log('CRITICAL', $message, $context);
    }

    /**
     * Log API request
     */
    public function logRequest(RequestInterface $request, array $context = []): void
    {
        $requestContext = [
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'headers' => $this->sanitizeHeaders($request->getHeaders()),
            'body_size' => $request->getBody()->getSize(),
        ];

        $this->info('API Request', array_merge($requestContext, $context));
    }

    /**
     * Log API response
     */
    public function logResponse(ResponseInterface $response, array $context = []): void
    {
        $responseContext = [
            'status_code' => $response->getStatusCode(),
            'reason_phrase' => $response->getReasonPhrase(),
            'headers' => $this->sanitizeHeaders($response->getHeaders()),
            'body_size' => $response->getBody()->getSize(),
        ];

        $level = $response->getStatusCode() >= 400 ? 'ERROR' : 'INFO';
        $this->log($level, 'API Response', array_merge($responseContext, $context));
    }

    /**
     * Log API error
     */
    public function logError(\Throwable $exception, array $context = []): void
    {
        $errorContext = [
            'exception_class' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];

        $this->error('API Error', array_merge($errorContext, $context));
    }

    /**
     * Log performance metrics
     */
    public function logPerformance(string $operation, float $duration, array $context = []): void
    {
        $performanceContext = [
            'operation' => $operation,
            'duration_ms' => round($duration * 1000, 2),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
        ];

        $level = $duration > 5.0 ? 'WARNING' : 'INFO';
        $this->log($level, 'Performance', array_merge($performanceContext, $context));
    }

    /**
     * Log retry attempt
     */
    public function logRetry(int $attempt, string $reason, array $context = []): void
    {
        $retryContext = [
            'attempt' => $attempt,
            'reason' => $reason,
        ];

        $this->warning('Retry Attempt', array_merge($retryContext, $context));
    }

    /**
     * Core logging method
     */
    private function log(string $level, string $message, array $context = []): void
    {
        if (self::LOG_LEVELS[$level] < self::LOG_LEVELS[$this->logLevel]) {
            return;
        }

        $logEntry = $this->formatLogEntry($level, $message, $context);

        if ($this->enableConsoleLogging) {
            echo $logEntry.PHP_EOL;
        }

        if ($this->logFile !== null) {
            file_put_contents($this->logFile, $logEntry.PHP_EOL, FILE_APPEND | LOCK_EX);
        }
    }

    /**
     * Format log entry
     */
    private function formatLogEntry(string $level, string $message, array $context): string
    {
        $timestamp = date('Y-m-d H:i:s.u');
        $pid = getmypid();

        $formattedContext = array_merge($this->context, $context);
        $contextJson = ! empty($formattedContext) ? ' '.json_encode($formattedContext, JSON_UNESCAPED_SLASHES) : '';

        return sprintf(
            '[%s] %s [PID:%d] %s%s',
            $timestamp,
            $level,
            $pid,
            $message,
            $contextJson
        );
    }

    /**
     * Sanitize headers for logging (remove sensitive data)
     */
    private function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = ['authorization', 'x-api-key', 'cookie', 'set-cookie'];
        $sanitized = [];

        foreach ($headers as $name => $values) {
            $lowerName = strtolower($name);

            if (in_array($lowerName, $sensitiveHeaders, true)) {
                $sanitized[$name] = ['***REDACTED***'];
            } else {
                $sanitized[$name] = $values;
            }
        }

        return $sanitized;
    }

    /**
     * Create a logger instance with default configuration
     */
    public static function createDefault(): self
    {
        $logLevel = $_ENV['FASTPIX_LOG_LEVEL'] ?? 'INFO';
        $logFile = $_ENV['FASTPIX_LOG_FILE'] ?? null;
        $enableConsole = filter_var($_ENV['FASTPIX_CONSOLE_LOGGING'] ?? 'false', FILTER_VALIDATE_BOOLEAN);

        return new self($logLevel, $logFile, $enableConsole);
    }

    /**
     * Create a logger for development
     */
    public static function createForDevelopment(): self
    {
        return new self('DEBUG', null, true);
    }

    /**
     * Create a logger for production
     */
    public static function createForProduction(): self
    {
        $logFile = $_ENV['FASTPIX_LOG_FILE'] ?? '/var/log/fastpix-sdk.log';

        return new self('WARNING', $logFile, false);
    }
}
