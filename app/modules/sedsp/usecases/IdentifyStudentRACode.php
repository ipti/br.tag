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

    public function __construct($studentTAGDataSource = null, $studentSEDDataSource = null) {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentSEDDataSource = $studentSEDDataSource ?? new StudentSEDDataSource();
    }

    /**
     * Summary of exec
     * @param int $tag_student_id StudentIdentificantion Id from TAG
     * @param boolean $force Force search from TAG
     * @return DadosAluno|OutErro
     */
    public function exec($tag_student_id, $force = false)
    {
        // Get Student From TAG database
        $student_tag = $this->studentTAGDataSource->getStudent($tag_student_id);
        $nome = $student_tag->name;
        $data_nascimento = $student_tag->birthday;
        $nome_mae = $student_tag->filiation_1;
        $aluno_sed = $this->studentSEDDataSource->getStudentRA($nome,$data_nascimento, $nome_mae,$force);
        
        return $aluno_sed;
    }
}
