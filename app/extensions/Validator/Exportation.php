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
        $school_identification = self::$db->select($sql);

        //Registro 10
        $sql = "SELECT * FROM school_structure where school_inep_fk='$id' ORDER BY school_inep_id_fk";
        $school_structure = self::$db->select($sql);

        //Registro 20
        $sql = "SELECT * FROM classroom where school_inep_fk='$id'";
        $classroom = self::$db->select($sql);

        //Registro 30
        $sql = 'SELECT * FROM instructor_identification';
        $instructor_identification = self::$db->select($sql);

        //Registro 40
        $sql = 'SELECT * FROM instructor_documents_and_address';
        $instructor_documents_and_address = self::$db->select($sql);

        //Registro 50
        $sql = 'SELECT * FROM instructor_variable_data';
        $instructor_variable_data = self::$db->select($sql);

        //Registro 51
        $sql = 'SELECT * FROM instructor_teaching_data';
        $instructor_teaching_data = self::$db->select($sql);

        //Registro 60
        $sql = 'SELECT * FROM student_identification';
        $student_identification = self::$db->select($sql);

        //Registro 70
        $sql = 'SELECT * FROM student_documents_and_address';
        $student_documents_and_address = self::$db->select($sql);

        //Registro 80
        $sql = 'SELECT * FROM student_enrollment';
        $student_enrollment = self::$db->select($sql);

        return [$school_identification,
            $school_structure,
            $classroom,
            $instructor_identification,
            $instructor_documents_and_address,
            $instructor_variable_data,
            $instructor_teaching_data,
            $student_identification,
            $student_documents_and_address,
            $student_enrollment];
    }

    //Inep ids permitidos
    public function getAllowedInepIds($table)
    {
        $sql = "SELECT inep_id FROM $table;";
        $array = self::$db->select($sql);
        foreach ($array as $key => $value) {
            $inep_ids[] = $value['inep_id'];
        }

        return $inep_ids;
    }

    /*
        *Checa se há o determinado de grupo de pessoas nas modalidades disponíveis
        *uxilia campo 92 à 95 no registro 10
    */
    public function areThereByModalitie($sql)
    {
        $people_by_modalitie = self::$db->select($sql);
        $modalities_regular = false;
        $modalities_especial = false;
        $modalities_eja = false;
        $modalities_professional = false;
        foreach ($people_by_modalitie as $key => $item) {
            switch ($item['modalities']) {
                case '1':
                    if ($item['number_of'] > '0') {
                        $modalities_regular = true;
                    }
                    break;
                case '2':
                    if ($item['number_of'] > '0') {
                        $modalities_especial = true;
                    }
                    break;

                case '3':
                    if ($item['number_of'] > '0') {
                        $modalities_eja = true;
                    }
                    break;

                case '4':
                    if ($item['number_of'] > '0') {
                        $modalities_professional = true;
                    }
                    break;
            }
        }
        return ['modalities_regular' => $modalities_regular,
            'modalities_especial' => $modalities_especial,
            'modalities_eja' => $modalities_eja,
            'modalities_professional' => $modalities_professional];
    }
}
