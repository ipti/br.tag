<?php

class TLog extends CApplicationComponent
{
    // Função auxiliar para gerar a mensagem de log
    private static function generateLogMessage($message, $data = null): string
    {
        $module = Yii::app()->controller->module ? Yii::app()->controller->module->id.'/' : '';

        $route = $module.Yii::app()->controller->id.'/'.Yii::app()->controller->action->id;
        $builderMessage = "[$route]: $message";

        if (isset($data)) {
            // Garante que dados sejam codificados como JSON
            $builderMessage .= ' | '.CJSON::encode($data);
        }

        return $builderMessage;
    }

    // Método para log de nível info
    public static function info($message, $data = null)
    {
        $builderMessage = self::generateLogMessage($message, $data);
        Yii::log($builderMessage, CLogger::LEVEL_INFO, 'application');
    }

    // Método para log de nível warning
    public static function warning($message, $data = null)
    {
        $builderMessage = self::generateLogMessage($message, $data);
        Yii::log($builderMessage, CLogger::LEVEL_WARNING, 'application');
    }

    // Método para log de nível error
    public static function error($message, $data = null)
    {
        $builderMessage = self::generateLogMessage($message, $data);
        Yii::log($builderMessage, CLogger::LEVEL_ERROR, 'application');
    }
}
