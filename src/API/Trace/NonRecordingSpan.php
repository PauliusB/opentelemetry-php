<?php

declare(strict_types=1);

namespace OpenTelemetry\API\Trace;

use Throwable;

/**
 * @see https://github.com/open-telemetry/opentelemetry-specification/blob/v1.6.1/specification/trace/api.md#wrapping-a-spancontext-in-a-span
 *
 * @internal
 * @psalm-internal OpenTelemetry
 */
final class NonRecordingSpan extends AbstractSpan
{
    private SpanContextInterface $context;

    public function __construct(
        SpanContextInterface $context
    ) {
        $this->context = $context;
    }

    /** @inheritDoc */
    public function getContext(): SpanContextInterface
    {
        return $this->context;
    }

    /** @inheritDoc */
    public function isRecording(): bool
    {
        return false;
    }

    /** @inheritDoc */
    public function setAttribute(string $key, $value): SpanInterface
    {
        return $this;
    }

    /** @inheritDoc */
    public function setAttributes(AttributesInterface $attributes): SpanInterface
    {
        return $this;
    }

    /** @inheritDoc */
    public function addEvent(string $name, ?AttributesInterface $attributes = null, int $timestamp = null): SpanInterface
    {
        return $this;
    }

    /** @inheritDoc */
    public function recordException(Throwable $exception, AttributesInterface $attributes = null): SpanInterface
    {
        return $this;
    }

    /** @inheritDoc */
    public function updateName(string $name): SpanInterface
    {
        return $this;
    }

    /** @inheritDoc */
    public function setStatus(string $code, string $description = null): SpanInterface
    {
        return $this;
    }

    /** @inheritDoc */
    public function end(int $endEpochNanos = null): void
    {
    }
}
