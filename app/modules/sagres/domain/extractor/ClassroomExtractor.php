<?php


class ClassroomExtractor
{
    protected $inepId;
    protected $referenceYear;
    protected $finalClass;
    protected $withoutCpf;

    public function __construct($inepId, $referenceYear, $finalClass, $withoutCpf)
    {
        $this->inepId = $inepId;
        $this->referenceYear = $referenceYear;
        $this->finalClass = $finalClass;
        $this->withoutCpf = $withoutCpf;
    }


    /**
     * @return Classroom[]
     */
    public function execute()
    {

        $criteria = new CDbCriteria();
        $criteria->alias = 'c';

        $criteria->select = [
            'c.initial_hour',
            'c.school_inep_fk',
            'c.id',
            'c.name',
            'c.turn',
            'esvm.edcenso_associated_stage_id',
            'c.edcenso_stage_vs_modality_fk',
            'c.period',
            'c.ignore_on_sagres',
        ];

        $criteria->join = 'JOIN edcenso_stage_vs_modality esvm ON c.edcenso_stage_vs_modality_fk = esvm.id';

        $criteria->condition = 'c.school_inep_fk = :schoolInepFk AND c.school_year = :referenceYear';
        $criteria->params = [
            ':schoolInepFk' => $this->inepId,
            ':referenceYear' => $this->referenceYear,
        ];

        $turmas = Classroom::model()->findAll($criteria);
        return $turmas;


    }

    public function getFinalClass()
    {
        return $this->finalClass;
    }
    public function getWithoutCpf()
    {
        return $this->withoutCpf;
    }





}





?>