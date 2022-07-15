<?php
class Register40
{
    public static function export()
    {
        $registers = [];

        switch (Yii::app()->user->school) {
            case "28026055":
                array_push($registers, '40|'.Yii::app()->user->school.'|902943||1|2|1');
                break;
            default:
                array_push($registers, '40|'.Yii::app()->user->school.'|909999||1|2|1');
                break;
        }

        return $registers;
    }
}