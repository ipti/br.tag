<?php

class Register40
{
    public static function export()
    {
        $registers = [];

        $instructor = Yii::app()->db->createCommand("
          SELECT ii.inep_id from manager_identification mi
          JOIN school_identification si ON mi.school_inep_id_fk = si.inep_id
          JOIN classroom c on c.school_inep_fk = si.inep_id
          JOIN instructor_teaching_data itd on itd.classroom_id_fk = c.id
          JOIN instructor_identification ii on ii.id = itd.instructor_fk
          JOIN instructor_documents_and_address idaa on idaa.id = ii.id
          WHERE si.inep_id = :inep_id and mi.cpf = idaa.cpf"
        )->bindParam(":inep_id", Yii::app()->user->school)->queryRow();
        $inepId = "II90999";
        if ($instructor != null) {
            $inepId = $instructor["inep_id"];
        }
        array_push($registers, '40|' . Yii::app()->user->school . '|' . $inepId . '||1|2|1');

        return $registers;
    }
}