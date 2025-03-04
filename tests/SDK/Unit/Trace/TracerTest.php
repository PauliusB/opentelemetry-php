<?php

declare(strict_types=1);

namespace OpenTelemetry\Tests\SDK\Unit\Trace;

use OpenTelemetry\API\Trace as API;
use OpenTelemetry\SDK\Trace\ReadableSpanInterface;
use OpenTelemetry\SDK\Trace\SpanBuilder;
use OpenTelemetry\SDK\Trace\TracerProvider;
use PHPUnit\Framework\TestCase;

class TracerTest extends TestCase
{
    private API\TracerInterface $tracer;

    protected function setUp(): void
    {
        $this->tracer = (new TracerProvider())
            ->getTracer('name', 'version');
    }

    public function test_spanBuilder_default(): void
    {
        $this->assertInstanceOf(
            SpanBuilder::class,
            $this->tracer->spanBuilder('name')
        );
    }

    public function test_spanBuilder_propagatesInstrumentationLibraryInfoToSpan(): void
    {
        /** @var ReadableSpanInterface $span */
        $span = $this->tracer->spanBuilder('span')->startSpan();

        $this->assertSame('name', $span->getInstrumentationLibrary()->getName());
        $this->assertSame('version', $span->getInstrumentationLibrary()->getVersion());
    }

    public function test_spanBuilder_fallbackSpanName(): void
    {
        /** @var ReadableSpanInterface $span */
        $span = $this->tracer->spanBuilder('  ')->startSpan();

        $this->assertSame(
            'empty',
            $span->getName()
        );
    }
}
