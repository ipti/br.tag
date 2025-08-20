<?php

use OpenTelemetry\API\Trace\SpanInterface;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Context\Context;

class TLog extends CApplicationComponent
{
    /** @var \OpenTelemetry\API\Trace\TracerInterface|null */
    public static $tracer = null;

    private static function generateLogMessage($message, $data = null): string
    {
        $module = Yii::app()->controller->module ? Yii::app()->controller->module->id . '/' : '';
        $route = $module . Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;
        $builderMessage = "[$route]: $message";

        if (isset($data)) {
            $builderMessage .= ' | ' . CJSON::encode($data);
        }

        return $builderMessage;
    }

    private static function addEventToSpan($level, $message, $data = null)
    {
        TracerManager::addEventToSpan("log.$level", $message, $data ?? []);
    }

    public static function info($message, $data = null)
    {
        $builderMessage = self::generateLogMessage($message, $data);
        Yii::log($builderMessage, CLogger::LEVEL_INFO, 'application');
        self::addEventToSpan('info', $message, $data);
    }

    public static function warning($message, $data = null)
    {
        $builderMessage = self::generateLogMessage($message, $data);
        Yii::log($builderMessage, CLogger::LEVEL_WARNING, 'application');
        self::addEventToSpan('warning', $message, $data);
    }

    public static function error($message, $data = null)
    {
        $builderMessage = self::generateLogMessage($message, $data);
        Yii::log($builderMessage, CLogger::LEVEL_ERROR, 'application');
        self::addEventToSpan('error', $message, $data);
    }
}
