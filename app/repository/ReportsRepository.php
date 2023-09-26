<?php

class ReportsRepository {

    public $repository;

    public function __construct() {
       
    }

    /**
     * Total de Alunos Matriculados por Escola
     */
    public function getTotalNumberOfStudentsEnrolled() : array
    {
        $sql = "SELECT
                    si.name AS school_name,
                    COUNT(DISTINCT c.id) AS count_class,
                    COUNT(se.id) AS count_enrollments
                FROM
                    school_identification si
                LEFT JOIN
                    classroom c ON c.school_inep_fk = si.inep_id
                LEFT JOIN
                    student_enrollment se ON se.classroom_fk = c.id
                WHERE 
                    c.school_year = :school_year AND se.status = 1
                GROUP BY
                    si.inep_id, si.name;";

        $result = Yii::app()->db->createCommand($sql)
        ->bindParam(":school_year", Yii::app()->user->year)
        ->queryAll();

        $response = array("result" => $result);

        return $response;
    }

    /**
     * Alunos com CPF, RG e NIS de todas as Escolas
     */
    public function getStudentCpfRgNisAllSchools() : array
    {
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $sql = "SELECT si.name, si.birthday, sdaa.cpf, sdaa.rg_number,
                    sdaa.nis, si.responsable_name, si.responsable_telephone, si2.name as school_name
                FROM student_enrollment se
                JOIN student_identification si ON se.student_fk = si.id
                JOIN student_documents_and_address sdaa ON si.id = sdaa.id
                JOIN classroom c ON se.classroom_fk = c.id
                JOIN school_identification si2 ON c.school_inep_fk = si2.inep_id
                WHERE se.status = 1 OR se.status IS NULL
                GROUP BY si.name
                ORDER BY si.name;";

        $result = Yii::app()->db->createCommand($sql)
        ->queryAll();

        $allSchools = true;

        $title = "RELATÓRIO DE ALUNOS DE TODAS AS ESCOLAS (CPF, RG E NIS)<br>".$school->name;

        $response = array("result" => $result, "allSchools" => $allSchools, "title" => $title);

        return $response;
    }

}