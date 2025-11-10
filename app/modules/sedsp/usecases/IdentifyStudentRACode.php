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
    private $studentTAGDataSource;
    private $studentSEDDataSource;

    public function __construct($studentTAGDataSource = null, $studentSEDDataSource = null)
    {
        $this->studentTAGDataSource = $studentTAGDataSource ?? new StudentTAGDataSource();
        $this->studentSEDDataSource = $studentSEDDataSource ?? new StudentSEDDataSource();
    }

    /**
     * Summary of exec
     * @param int $tagStudentId StudentIdentificantion Id from TAG
     * @param boolean $force Force search from TAG
     * @return DadosAluno|OutErro
     */
    public function exec($tagStudentId, $force = false)
    {
        // Get Student From TAG database
        $studentTag = $this->studentTAGDataSource->getStudent($tagStudentId);
        $nome = $studentTag->name;
        $dataNascimento = $studentTag->birthday;
        $nomeMae = $studentTag->filiation_1;
        return $this->studentSEDDataSource->getStudentRA($nome, $dataNascimento, $nomeMae, $force);

    }
}
