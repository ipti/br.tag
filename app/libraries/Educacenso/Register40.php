<?php

class Register40
{
    public static function export($year)
    {
        $registers = [];

        $instructor = Yii::app()->db->createCommand(
            '
        SELECT ii.id, ii.inep_id from manager_identification mi
        JOIN school_identification si ON mi.school_inep_id_fk = si.inep_id
        JOIN classroom c on c.school_inep_fk = si.inep_id and c.school_year = :year
        JOIN instructor_teaching_data itd on itd.classroom_id_fk = c.id
        JOIN instructor_identification ii on ii.id = itd.instructor_fk
        JOIN instructor_documents_and_address idaa on idaa.id = ii.id
        WHERE si.inep_id = :inep_id and mi.cpf = idaa.cpf'
        )->bindParam(':inep_id', Yii::app()->user->school)
        ->bindParam(':year', Yii::app()->user->year)
        ->queryRow();

        $systemId = 'II90999';
        $inepId = '';
        if ($instructor != null) {
            $systemId = 'II' . $instructor['id'];
            $inepId = $instructor['inep_id'];
        }

        $managerIdentification = ManagerIdentification::model()->findByAttributes(['school_inep_id_fk' => Yii::app()->user->school]);
        $contractType = $managerIdentification === null ? '1' : (string) $managerIdentification['contract_type'];
        if (!in_array($contractType, ['1', '2', '3', '4'], true)) {
            $contractType = '1';
        }

        $register = [
            1 => '40',
            2 => Yii::app()->user->school,
            3 => $systemId,
            4 => $inepId,
            5 => '1',
            6 => '2',
            7 => $contractType,
        ];

        array_push($registers, EducacensoRegisterFormatter::format(40, $register, $year));

        return $registers;
    }
}
