<?php

require __DIR__ . '/../../vendor/autoload.php';

use OpenTelemetry\SDK\Trace\TracerProvider;
use OpenTelemetry\SDK\Common\Attribute\Attributes;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;
use OpenTelemetry\Contrib\Otlp\SpanExporter;

$transport = (new OtlpHttpTransportFactory())->create('http://otel-collector:4318/v1/traces', 'application/json');
$exporter = new SpanExporter($transport);

// TracerProvider com SimpleSpanProcessor
$tracerProvider = new TracerProvider(new SimpleSpanProcessor($exporter));

$tracer = $tracerProvider->getTracer('yii-app-test');

$span = $tracer->spanBuilder('teste-span')->startSpan();
$span->addEvent('iniciando teste');
sleep(1);
$span->addEvent('finalizando teste');
$span->end();

echo "Span enviado para OTEL Collector!\n";
