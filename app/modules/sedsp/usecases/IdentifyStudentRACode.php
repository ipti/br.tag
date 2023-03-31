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

    /**
     * Summary of exec
     * @param int $tag_student_id StudentIdentificantion Id from TAG
     * @return DadosAluno
     */
    public function exec($tag_student_id)
    {
        // Get Student From TAG database
        $student_tag = $this->studentTAGDataSource->getStudent($tag_student_id);
        $nome = $student_tag->name;
        $data_nascimento = $student_tag->birthday;
        $nome_mae = $student_tag->filiation_1;

        // Search Student on SED API 
        $aluno_sed = $this->studentSEDDataSource->getStudentRA($nome, $data_nascimento, $nome_mae);
        return $aluno_sed;
    }
}
