<?php

use OpenTelemetry\API\Trace\Span;
use OpenTelemetry\API\Trace\SpanInterface;
use OpenTelemetry\SDK\Trace\SpanProcessor\SpanProcessorInterface;

class TracerManager
{
    private static ?\OpenTelemetry\API\Trace\TracerInterface $tracer = null;
    private static $spanProcessor = null;

    public static function init($tracer, $processor = null): void
    {
        self::$tracer = $tracer;
        self::$spanProcessor = $processor;

        if ($processor) {
            register_shutdown_function(function () {
                self::shutdown();
            });
        }
    }

    public static function getTracer(): \OpenTelemetry\API\Trace\TracerInterface
    {
        if (!self::$tracer) {
            throw new \RuntimeException('Tracer not initialized');
        }
        return self::$tracer;
    }

    public static function runInSpan(string $name, callable $fn, array $attributes = [])
    {
        $span = self::$tracer->spanBuilder($name)->startSpan();
        foreach ($attributes as $k => $v) {
            $span->setAttribute($k, $v);
        }

        $scope = $span->activate();

        try {
            return $fn($span);
        } catch (\Throwable $e) {
            $span->recordException($e);
            throw $e;
        } finally {
            $scope->detach();
            $span->end();
        }
    }

    public static function getCurrentSpan(): ?SpanInterface
    {
        $span = Span::getCurrent();
        return $span instanceof SpanInterface ? $span : null;
    }

    public static function addEventToSpan(string $level, string $message, array $data = []): void
    {
        $span = self::getCurrentSpan();
        if ($span) {
            $span->addEvent($level, array_merge(['message' => $message], $data));
        }
    }

    public static function shutdown(): void
    {
        if (self::$spanProcessor) {
            try {
                self::$spanProcessor->shutdown();
            } catch (\Throwable $e) {
                error_log('Failed to shutdown OpenTelemetry span processor: ' . $e->getMessage());
            }
        }
    }
}
