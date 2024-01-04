<?php
/**
 * @property int $stage
 * @property CDbCriteria $criteria;
 */
class GetGradeUnitiesByDisciplineUsecase
{

    public function __construct($stage)
    {
        $this->criteria = new CDbCriteria();
        $this->criteria->alias = "gu";
        $this->criteria->condition = "edcenso_stage_vs_modality_fk = :stage";
        $this->criteria->addInCondition("gu.type", [GradeUnity::TYPE_UNITY, GradeUnity::TYPE_UNITY_BY_CONCEPT, GradeUnity::TYPE_UNITY_WITH_RECOVERY]);
        $this->criteria->params = array_merge([":stage" => $stage], $this->criteria->params);
        $this->criteria->order = "gu.id";

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
