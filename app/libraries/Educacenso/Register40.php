<?php
class Register40
{
    public static function export($year)
    {
        $registers = [];

        array_push($registers, '40|'.Yii::app()->user->school.'|909999||1|2|1');

        return $registers;
    }
}