<?php

class Register40
{
    public static function export()
    {
        $registers = [];

        $instructor = Yii::app()->db->createCommand("      
        SELECT ii.id, ii.inep_id from manager_identification mi
        JOIN school_identification si ON mi.school_inep_id_fk = si.inep_id
        JOIN classroom c on c.school_inep_fk = si.inep_id and c.school_year = :year
        JOIN instructor_teaching_data itd on itd.classroom_id_fk = c.id
        JOIN instructor_identification ii on ii.id = itd.instructor_fk
        JOIN instructor_documents_and_address idaa on idaa.id = ii.id
        WHERE si.inep_id = :inep_id and mi.cpf = idaa.cpf"
        )->bindParam(":inep_id", Yii::app()->user->school)
        ->bindParam(":year", Yii::app()->user->year)
        ->queryRow();
        
        $system_id = "II90999";
        $inepId = '';
        if ($instructor != null) {
            $system_id = "II" . $instructor['id'];
            $inepId = $instructor["inep_id"];
        }

        array_push($registers, '40|' . Yii::app()->user->school . '|' . $system_id . '|' . $inepId . '|1|2|1');

        return $registers;
    }
}