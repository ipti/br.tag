<?php
/**
 * @property CDbCriteria $criteria;
 */
class GetGradeUnitiesByDisciplineUsecase
{
    private $criteria;

    public function __construct($classroomId)
    {
        $this->criteria = new CDbCriteria();
        $this->criteria->alias = 'gu';
        $this->criteria->select = [
            'gu.id AS grade_unity_id',
            'gu.type',
            'gr.id AS grade_rules_id',
            'grvesvm.id AS edcenso_stage_id'
        ];
        $this->criteria->join = 'join grade_rules gr on gr.id = gu.grade_rules_fk';
        $this->criteria->join .= ' join grade_rules_vs_edcenso_stage_vs_modality grvesvm on gr.id = grvesvm.grade_rules_fk';
        $this->criteria->join .= ' join classroom_vs_grade_rules cvgr on cvgr.grade_rules_fk = gr.id';
        $this->criteria->condition = 'cvgr.classroom_fk = :classroomId';
        $this->criteria->params = [':classroomId' => $classroomId];
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
     *
     * @return GradeUnity[]
     */
    public function exec()
    {
        return GradeUnity::model()->findAll($this->criteria);
    }
    /**
     *
     * @return int
     */
    public function execCount()
    {
        return GradeUnity::model()->count($this->criteria);
    }


}
