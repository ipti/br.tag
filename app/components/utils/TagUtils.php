<?php

class TagUtils extends CApplicationComponent {


    public static function isInstructor(){
        return (bool)Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id);
    }

    public static function isStageMinorEducation($stage){
        $refMinorStages = [
            '1', '2', '3', '4', '5', '6', '7', '8', '14', '15', '16', '17', '18'
        ];
        $stages = new CList($refMinorStages, true);
        return $stages->contains($stage);
    }

    public static function convertDateFormat($date) {
         // Remove espaços em branco do início e do fim da string
        $date = trim($date);

        // Verifica se a date é vazia ou nula
        if (empty($date) || is_null($date)) {
            return $date;
        }

        // Verifica se a date está no formato dd/mm/yyyy
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            return $date;
        }

        // Verifica se a date está no formato yyyy-mm-dd
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $dateParts = explode('-', $date);
            $dia = $dateParts[2];
            $mes = $dateParts[1];
            $ano = $dateParts[0];
            return "$dia/$mes/$ano";
        }

        // Retorna a date original se não corresponder a nenhum formato conhecido
        return $date;
    }

    public static function isInstance($instance){

        if(is_array($instance)){
            $instances = array_map(function ($element){
                return strtoupper($element);
            }, $instance);

            return in_array(strtoupper(INSTANCE), $instances);
        }

        return strtoupper(INSTANCE) === strtoupper($instance);
    }
}

?>
