<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentSEDDataSource $studentSEDDataSource
 */
class IdentifyClassroomRACode
{
    private  $studentTAGDataSource;
    private   $studentSEDDataSource;

    /**
     * Summary of __construct
     * @param StudentTAGDataSource $studentTAGDataSource
     * @param StudentSEDDataSource $studentSEDDataSource
     */
    public function __construct($studentTAGDataSource = null, $studentSEDDataSource = null) {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentSEDDataSource = $studentSEDDataSource ?? new StudentSEDDataSource();
    }
    public function exec($tag_student_id)
    {
        $students = $this->studentTAGDataSource->getAllStudentBySchool($tag_student_id);
        $response = $this->studentSEDDataSource->getAllStudentsRA($students);

        //Acessando os dados do aluno
        return $response;
    }
}
