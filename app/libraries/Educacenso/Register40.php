<?php

class Register40
{
    public static function export()
    {
        $registers = [];

        switch (Yii::app()->user->school) {
            case '28026055':
                array_push($registers, '40|' . Yii::app()->user->school . '|902943||1|2|1');
                break;
            case '28026012':
                array_push($registers, '40|' . Yii::app()->user->school . '|902873||1|2|1');
                break;
            case '28026128':
                array_push($registers, '40|' . Yii::app()->user->school . '|902967||1|2|1');
                break;
            default:
                array_push($registers, '40|' . Yii::app()->user->school . '|909999||1|2|1');
                break;
        }

        return $registers;
    }
}
