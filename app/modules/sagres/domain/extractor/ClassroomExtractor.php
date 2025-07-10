<?php


class ClassroomExtractor
{
    protected $inepId;
    protected $referenceYear;

    public function __construct($inepId, $referenceYear)
    {
        $this->inepId = $inepId;
        $this->referenceYear = $referenceYear;
    }

    private $query = "SELECT
                    c.initial_hour AS initialHour,
                    c.school_inep_fk AS schoolInepFk,
                    c.id AS classroomId,
                    c.name AS classroomName,
                    c.turn AS classroomTurn,
                    COALESCE(esvm.edcenso_associated_stage_id, c.edcenso_stage_vs_modality_fk) as stage,
                    c.period,
                    c.ignore_on_sagres
                FROM
                    classroom c
                    join edcenso_stage_vs_modality esvm on c.edcenso_stage_vs_modality_fk = esvm.id
                WHERE
                    c.school_inep_fk = :schoolInepFk
                    AND c.school_year = :referenceYear";

    /**
     * @return Classroom[]
     */
    public function execute()
    {

        $criteria = new CDbCriteria();
        $criteria->alias = 'c';

        $criteria->select = [
            'c.initial_hour AS initialHour',
            'c.school_inep_fk AS schoolInepFk',
            'c.id AS classroomId',
            'c.name AS classroomName',
            'c.turn AS classroomTurn',
            'COALESCE(esvm.edcenso_associated_stage_id, c.edcenso_stage_vs_modality_fk) AS stage',
            'c.period',
            'c.ignore_on_sagres',
        ];

        $criteria->join = 'JOIN edcenso_stage_vs_modality esvm ON c.edcenso_stage_vs_modality_fk = esvm.id';

        $criteria->condition = 'c.school_inep_fk = :schoolInepFk AND c.school_year = :referenceYear';
        $criteria->params = [
            ':schoolInepFk' => $this->inepId,
            ':referenceYear' => $this->referenceYear
        ];

        return Classroom::model()->findAll($criteria);


    }





}





?>