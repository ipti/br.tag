<?php

class StudentTAGDataSource
{
    public function getStudent($id)
    {
        return StudentIdentification::model()->findByPk($id);
    }

    public function getAllStudentBySchool($schoolId)
    {
        return StudentIdentification::model()->findAllByAttributes(['school_inep_id_fk' => $schoolId]);
    }

    public function getAllStudentWithoutRAbySchool($schoolId)
    {
        return  StudentIdentification::model()->findAllByAttributes(['school_inep_id_fk' => $schoolId], 'gov_id IS NULL');
    }

    public function getAllStudentsEnrollmentsbySchool($schoolId)
    {
        return StudentEnrollment::model()->findAllByAttributes(['school_inep_id_fk' => $schoolId]);
    }
}
