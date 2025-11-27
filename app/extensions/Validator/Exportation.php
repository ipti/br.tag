<?php

class Exportation
{
    protected static $db;

    public function __construct()
    {
        self::$db = new Db();
    }

    public function getTables()
    {
        //Registro 00
        $sql = "SELECT * FROM school_identification where inep_id='$id' ORDER BY inep_id";
        $schoolIdentification = self::$db->select($sql);

        //Registro 10
        $sql = "SELECT * FROM school_structure where school_inep_fk='$id' ORDER BY school_inep_id_fk";
        $schoolStructure = self::$db->select($sql);

        //Registro 20
        $sql = "SELECT * FROM classroom where school_inep_fk='$id'";
        $classroom = self::$db->select($sql);

        //Registro 30
        $sql = 'SELECT * FROM instructor_identification';
        $instructorIdentification = self::$db->select($sql);

        //Registro 40
        $sql = 'SELECT * FROM instructor_documents_and_address';
        $instructorDocumentsAndAddress = self::$db->select($sql);

        //Registro 50
        $sql = 'SELECT * FROM instructor_variable_data';
        $instructorVariableData = self::$db->select($sql);

        //Registro 51
        $sql = 'SELECT * FROM instructor_teaching_data';
        $instructorTeachingData = self::$db->select($sql);

        //Registro 60
        $sql = 'SELECT * FROM student_identification';
        $studentIdentification = self::$db->select($sql);

        //Registro 70
        $sql = 'SELECT * FROM student_documents_and_address';
        $studentDocumentsAndAddress = self::$db->select($sql);

        //Registro 80
        $sql = 'SELECT * FROM student_enrollment';
        $studentEnrollment = self::$db->select($sql);

        return [$schoolIdentification,
            $schoolStructure,
            $classroom,
            $instructorIdentification,
            $instructorDocumentsAndAddress,
            $instructorVariableData,
            $instructorTeachingData,
            $studentIdentification,
            $studentDocumentsAndAddress,
            $studentEnrollment];
    }

    //Inep ids permitidos
    public function getAllowedInepIds($table)
    {
        $sql = "SELECT inep_id FROM $table;";
        $array = self::$db->select($sql);
        foreach ($array as $value) {
            $inepIds[] = $value['inep_id'];
        }

        return $inepIds;
    }

    /*
        *Checa se há o determinado de grupo de pessoas nas modalidades disponíveis
        *uxilia campo 92 à 95 no registro 10
    */
    public function areThereByModalitie($sql)
    {
        $peopleByModalitie = self::$db->select($sql);
        $modalitiesRegular = false;
        $modalitiesEspecial = false;
        $modalitiesEja = false;
        $modalitiesProfessional = false;
        foreach ($peopleByModalitie as $item) {
            switch ($item['modalities']) {
                case '1':
                    if ($item['number_of'] > '0') {
                        $modalitiesRegular = true;
                    }
                    break;
                case '2':
                    if ($item['number_of'] > '0') {
                        $modalitiesEspecial = true;
                    }
                    break;

                case '3':
                    if ($item['number_of'] > '0') {
                        $modalitiesEja = true;
                    }
                    break;

                case '4':
                    if ($item['number_of'] > '0') {
                        $modalitiesProfessional = true;
                    }
                    break;
                default:
                    break;
            }
        }
        return ['modalities_regular' => $modalitiesRegular,
            'modalities_especial' => $modalitiesEspecial,
            'modalities_eja' => $modalitiesEja,
            'modalities_professional' => $modalitiesProfessional];
    }
}
