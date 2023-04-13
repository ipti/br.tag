<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');
Yii::import('application.modules.sedsp.mappers.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentSEDDataSource $studentSEDDataSource
 */
class AddStudentToSED
{
    private  $studentTAGDataSource;
    private  $studentSEDDataSource;

    public function __construct($studentTAGDataSource = null, $studentSEDDataSource = null) {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentSEDDataSource = $studentSEDDataSource ?? new StudentSEDDataSource();
    }
    public function exec($tag_student_id)
    {
        $student = $this->studentTAGDataSource->getStudent($tag_student_id);
        $student_sed = StudentMapper::parseToSEDAlunoFicha($student, $student->documentsFk);
        $response = $this->studentSEDDataSource->addStudent($student_sed);
        $content = $response->getBody()->getContents();
        //Acessando os dados do aluno
        return json_decode($content);
    }
}
