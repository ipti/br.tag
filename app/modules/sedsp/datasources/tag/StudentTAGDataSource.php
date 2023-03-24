<?php 

class StudentTAGDataSource
{
    public function getStudent($id)
    {
        $student = StudentIdentification::model()->findByPk($id);
        return $student;
    }
    public function getAllStudentBySchool($school_id)
    {
        $student = StudentIdentification::model()->findAllByAttributes(["school_inep_id_fk" => $school_id]);
        return $student;
    } 
}

?>