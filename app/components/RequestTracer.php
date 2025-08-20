<?php

use OpenTelemetry\API\Globals;
use OpenTelemetry\SDK\Trace\Span;
use OpenTelemetry\Context\Context;

class RequestTracer extends CApplicationComponent
{
    private $span;
    private $scope;

    public function startRequestSpan($route, $attributes = [])
    {
        $tracer = TracerManager::getTracer();
        $this->span = $tracer->spanBuilder("HTTP $route")->startSpan();


        foreach ($attributes as $k => $v) {
            $this->span->setAttribute($k, $v);
        }

        $this->scope = $this->span->activate();
    }

    public function endRequestSpan()
    {

        if ($this->scope) {
            $this->scope->detach();
        }

        if ($this->span) {
            $this->span->end();
        }

        TracerManager::shutdown();
    }
}
