<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property StudentSEDDataSource $studentSEDDataSource
 */
class IdentifyStudentRACode
{
    private  $studentTAGDataSource;
    private  $studentSEDDataSource;

    function __construct($studentTAGDataSource = null, $studentSEDDataSource = null) {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentSEDDataSource = $studentSEDDataSource ?? new StudentSEDDataSource();
    }
    public function exec($tag_student_id)
    {
        $students = $this->studentTAGDataSource->getAllStudentBySchool($tag_student_id);
        // $response = $this->studentSEDDataSource->getStudentRA($student->name, $student->birthday, $student->filiation_1);
        $response = $this->studentSEDDataSource->getAllStudentsRA($students);
        // $response = $this->studentSEDDataSource->getStudentRA("ADALBERTO AMORIM CARDOSO DOS SANTOS", "24/05/2018", "VICTORIA APARECIDA DOS SANTOS");
        // $body = $response->getBody()->getContents();

        $dadosAluno = new DadosAluno($body);
        var_dump($dadosAluno);

        //Acessando os dados do aluno
        return $dadosAluno->outAluno->outNumRA;
    }
}
