<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests\Unit\Utils;

use FastPix\Sdk\Utils\Logger;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FastPix\Sdk\Utils\Logger
 */
class LoggerTest extends TestCase
{
    private string $tempLogFile;

    protected function setUp(): void
    {
        $this->tempLogFile = sys_get_temp_dir().'/fastpix-test.log';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempLogFile)) {
            unlink($this->tempLogFile);
        }
    }

    public function test_logger_can_be_created(): void
    {
        $logger = new Logger('INFO', $this->tempLogFile, true);

        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function test_logger_logs_info_message(): void
    {
        $logger = new Logger('INFO', $this->tempLogFile, false);
        $logger->info('Test message', ['key' => 'value']);

        $this->assertFileExists($this->tempLogFile);
        $content = file_get_contents($this->tempLogFile);
        $this->assertStringContainsString('Test message', $content);
        $this->assertStringContainsString('"key":"value"', $content);
    }

    public function test_logger_respects_log_level(): void
    {
        $logger = new Logger('ERROR', $this->tempLogFile, false);
        $logger->info('This should not be logged');
        $logger->error('This should be logged');

        $content = file_get_contents($this->tempLogFile);
        $this->assertStringNotContainsString('This should not be logged', $content);
        $this->assertStringContainsString('This should be logged', $content);
    }

    public function test_logger_can_set_context(): void
    {
        $logger = new Logger('INFO', $this->tempLogFile, false);
        $logger->setContext(['user_id' => '123']);
        $logger->info('Test message');

        $content = file_get_contents($this->tempLogFile);
        $this->assertStringContainsString('"user_id":"123"', $content);
    }

    public function test_logger_can_add_context(): void
    {
        $logger = new Logger('INFO', $this->tempLogFile, false);
        $logger->addContext('operation', 'test');
        $logger->info('Test message');

        $content = file_get_contents($this->tempLogFile);
        $this->assertStringContainsString('"operation":"test"', $content);
    }

    public function test_create_default_logger(): void
    {
        $logger = Logger::createDefault();

        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function test_create_development_logger(): void
    {
        $logger = Logger::createForDevelopment();

        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function test_create_production_logger(): void
    {
        $logger = Logger::createForProduction();

        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function test_logger_rejects_invalid_log_level(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid log level');

        new Logger('INVALID', $this->tempLogFile, false);
    }
}
