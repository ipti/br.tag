<?php

/**
 * @property int $classroomId
 * @property int $discipline
 */
class CalculateGradeResultsUsecase {
    public function __construct($classroom, $discipline) {
        $this->classroomId = $classroom;
        $this->discipline = $discipline;
    }

    public function exec() {
        $gradeUnities = $this->getUnitiesByClassroom($this->classroomId);

        if($gradeUnities[0]->type != GradeUnity::TYPE_UNITY_BY_CONCEPT) {
            $usercase = new CalculateNumericGradeUsecase($this->classroomId, $this->discipline);
            $usercase->exec();
        }
    }

    private function getUnitiesByClassroom($classroom) {

        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->join = "join edcenso_stage_vs_modality esvm on gu.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->join .= " join classroom c on c.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->condition = "c.id = :classroom";
        $criteria->params = array(":classroom" => $classroom);

        return GradeUnity::model()->findAll($criteria);
    }
}
