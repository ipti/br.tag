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
        $classroom = Classroom::model()->findByPk($this->classroomId);
        $gradeRules = GradeRules::model()->findByAttributes([
            "edcenso_stage_vs_modality_fk" => $classroom->edcenso_stage_vs_modality_fk
        ]);
        if($gradeRules->rule_type === "N") {
            $usercase = new CalculateNumericGradeUsecase($this->classroomId, $this->discipline);
            $usercase->exec();
            return;
        } else if ($gradeRules->rule_type === "C"){
            $usercase = new CalculateConceptGradeUsecase($this->classroomId, $this->discipline);
            $usercase->exec();
            return;
        }

        throw new Exception("Modelo de regras não definido para realização do calculo de notas", 1);

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
