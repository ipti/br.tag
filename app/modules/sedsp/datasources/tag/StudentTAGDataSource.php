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
    public function getAllStudentWithoutRAbySchool($school_id)
    {
        return  StudentIdentification::model()->findAllByAttributes(["school_inep_id_fk" => $school_id],"gov_id IS NULL");
    }

    public function getAllStudentsEnrollmentsbySchool($school_id)
    {
        return StudentEnrollment::model()->findAllByAttributes(["school_inep_id_fk" => $school_id]);
    }
}