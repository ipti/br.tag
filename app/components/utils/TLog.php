<?php

class TLog extends CApplicationComponent {

    public static function info($message, $data = null){
        $route = Yii::app()->controller->id . "/". Yii::app()->controller->action->id;
        $builderMessage = "[$route]: $message" ;

        if(isset($data)){
            $builderMessage .= PHP_EOL . CVarDumper::dumpAsString($data);
        }
        

        Yii::log($builderMessage, CLogger::LEVEL_INFO, 'application');

    }

    public static function warning($message, $data = null){
        $route = Yii::app()->controller->id . "/". Yii::app()->controller->action->id;
        $builderMessage = "[$route]: $message" ;

        if(isset($data)){
            $builderMessage .= PHP_EOL . CVarDumper::dumpAsString($data);
        }

        Yii::log($builderMessage, CLogger::LEVEL_WARNING, 'application');
    }

    public static function error($message, $data = null){
        $route = Yii::app()->controller->id . "/". Yii::app()->controller->action->id;
        $builderMessage = "[$route]: $message" ;

        if(isset($data)){
            $builderMessage .= PHP_EOL . CVarDumper::dumpAsString($data);
        }

        Yii::log($builderMessage, CLogger::LEVEL_ERROR, 'application');
    }

}

?>
