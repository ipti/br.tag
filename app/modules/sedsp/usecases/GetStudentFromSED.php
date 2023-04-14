<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');
Yii::import('application.modules.sedsp.mappers.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentSEDDataSource $studentSEDDataSource
 */
class GetStudentFromSED
{
    private  $studentTAGDataSource;
    private  $studentSEDDataSource;

    public function __construct($studentTAGDataSource = null, $studentSEDDataSource = null)
    {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentSEDDataSource = $studentSEDDataSource ?? new StudentSEDDataSource();
    }
    public function exec($RA)
    {
        $response = $this->studentSEDDataSource->getStudentWithRA($RA);
        $content = $response->getBody()->getContents();
        $student_tag = StudentMapper::parseToTAGAlunoFicha($content);
        //Acessando os dados do aluno
        return $student_tag;
    }
}
