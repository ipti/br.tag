<?php
/**
 * @property CDbCriteria $criteria;
 */
class GetGradeUnitiesByDisciplineUsecase
{
    private $criteria;

    public function __construct($classroomId, $stage)
    {
        $this->criteria = new CDbCriteria();
        $this->criteria->alias = 'gu';

        if (isset($stage) && $stage !== "") {
            $this->criteria->join = 'JOIN grade_rules gr ON gr.id = gu.grade_rules_fk';
            $this->criteria->join .= ' JOIN grade_rules_vs_edcenso_stage_vs_modality grvesvm ON gr.id = grvesvm.grade_rules_fk';
            $this->criteria->join .= ' JOIN classroom_vs_grade_rules cvgr ON cvgr.grade_rules_fk = gr.id';
            $this->criteria->condition = 'grvesvm.edcenso_stage_vs_modality_fk = :stage AND cvgr.classroom_fk = :classroomId';
            $this->criteria->params = [':classroomId' => $classroomId, ':stage' => $stage];
        } else {
            $this->criteria->join = 'INNER JOIN grade_rules gr ON gr.id = gu.grade_rules_fk';
            $this->criteria->join .= ' INNER JOIN classroom_vs_grade_rules cgr ON cgr.grade_rules_fk = gu.grade_rules_fk';
            $this->criteria->join .= ' INNER JOIN classroom c ON c.id = cgr.classroom_fk';
            $this->criteria->join .= ' INNER JOIN grade_rules_vs_edcenso_stage_vs_modality grvesvm ON grvesvm.edcenso_stage_vs_modality_fk = c.edcenso_stage_vs_modality_fk';
            $this->criteria->condition = 'cgr.classroom_fk = :classroomId';
            $this->criteria->params = [':classroomId' => $classroomId];
        }

        $this->criteria->addInCondition(
            'gu.type',
            [
                GradeUnity::TYPE_UNITY,
                GradeUnity::TYPE_UNITY_BY_CONCEPT,
                GradeUnity::TYPE_UNITY_WITH_RECOVERY
            ]
        );

        $this->criteria->group = 'gu.id';
    }

    /**
     * @return GradeUnity[]
     */
    public function exec()
    {
        return GradeUnity::model()->findAll($this->criteria);
    }

    /**
     * @return int
     */
    public function execCount()
    {
        $countCriteria = clone $this->criteria;
        $countCriteria->select = 'COUNT(DISTINCT gu.id) AS total';

        return GradeUnity::model()->count($countCriteria);
    }



}
