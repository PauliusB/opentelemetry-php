<?php

declare(strict_types=1);

namespace OpenTelemetry\Tests\SDK\Unit\Trace\SpanExporter;

use OpenTelemetry\SDK\Trace\SpanExporter\LoggerDecorator;
use OpenTelemetry\SDK\Trace\SpanExporterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LogLevel;
use RuntimeException;

class LoggerDecoratorTest extends AbstractLoggerAwareTest
{
    /**
     * @var SpanExporterInterface|null
     */
    private ?SpanExporterInterface $decorated;

    public function testFromConnectionString(): void
    {
        $this->expectException(RuntimeException::class);

        LoggerDecorator::fromConnectionString('foo', 'bar', 'baz');
    }

    /**
     * @psalm-suppress PossiblyUndefinedMethod
     */
    public function testShutDown(): void
    {
        $this->getSpanExporterInterfaceMock()
            ->expects($this->once())
            ->method('shutdown')
            ->willReturn(true);

        $this->assertTrue(
            $this->createLoggerDecorator()->shutdown()
        );
    }

    /**
     * @psalm-suppress PossiblyUndefinedMethod
     */
    public function testForceFlush(): void
    {
        $this->getSpanExporterInterfaceMock()
            ->expects($this->once())
            ->method('forceFlush')
            ->willReturn(true);

        $this->assertTrue(
            $this->createLoggerDecorator()->forceFlush()
        );
    }

    /**
     * @psalm-suppress PossiblyUndefinedMethod
     * @psalm-suppress PossiblyInvalidArgument
     */
    public function testExportSuccess(): void
    {
        $this->getSpanExporterInterfaceMock()
            ->expects($this->once())
            ->method('export')
            ->willReturn(SpanExporterInterface::STATUS_SUCCESS);

        $this->getLoggerInterfaceMock()
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::INFO);

        $this->createLoggerDecorator()
            ->export(
                $this->createSpanMocks()
            );
    }

    /**
     * @psalm-suppress PossiblyUndefinedMethod
     * @psalm-suppress PossiblyInvalidArgument
     */
    public function testExportFailedRetryable(): void
    {
        $this->getSpanExporterInterfaceMock()
            ->expects($this->once())
            ->method('export')
            ->willReturn(SpanExporterInterface::STATUS_FAILED_RETRYABLE);

        $this->getLoggerInterfaceMock()
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::ERROR);

        $this->createLoggerDecorator()
            ->export(
                $this->createSpanMocks()
            );
    }

    /**
     * @psalm-suppress PossiblyUndefinedMethod
     * @psalm-suppress PossiblyInvalidArgument
     */
    public function testExportFailedNotRetryable(): void
    {
        $this->getSpanExporterInterfaceMock()
            ->expects($this->once())
            ->method('export')
            ->willReturn(SpanExporterInterface::STATUS_FAILED_NOT_RETRYABLE);

        $this->getLoggerInterfaceMock()
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::ALERT);

        $this->createLoggerDecorator()
            ->export(
                $this->createSpanMocks()
            );
    }

    /**
     * @return LoggerDecorator
     * @psalm-suppress PossiblyInvalidArgument
     */
    private function createLoggerDecorator(): LoggerDecorator
    {
        return new LoggerDecorator(
            $this->getSpanExporterInterfaceMock(),
            $this->getLoggerInterfaceMock(),
            $this->createSpanConverterInterfaceMock()
        );
    }

    /**
     * @return SpanExporterInterface|MockObject
     * @psalm-suppress MismatchingDocblockReturnType
     */
    private function getSpanExporterInterfaceMock(): SpanExporterInterface
    {
        // @phpstan-ignore-next-line
        return $this->decorated ?? $this->decorated = $this->createMock(SpanExporterInterface::class);
    }
}
