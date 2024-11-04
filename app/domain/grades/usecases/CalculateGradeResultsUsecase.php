<?php

/**
 * @property int $classroomId
 * @property int $discipline
 * @property int $stage
 */
class CalculateGradeResultsUsecase {
    public function __construct($classroom, $discipline, $stage) {
        $this->classroomId = $classroom;
        $this->discipline = $discipline;
        $this->stage = $stage;
    }

    public function exec() {
        $classroom = Classroom::model()->findByPk($this->classroomId);
        if(!isset($this->stage)) {
            $this->stage = $classroom->edcenso_stage_vs_modality_fk;
        }
        $gradeRules = GradeRules::model()->findByAttributes([
            "edcenso_stage_vs_modality_fk" => $this->stage
        ]);
        if($gradeRules->rule_type === "N") {
            $usercase = new CalculateNumericGradeUsecase($this->classroomId, $this->discipline, $this->stage);
            $usercase->exec();
            TLog::info("Notas numéricas calculadas com sucesso.", array(
                "Classroom" => $classroom->id,
                "GradeRules" => $gradeRules->id,
                "Rule" => $gradeRules->id
            ));
            return;
        } elseif ($gradeRules->rule_type === "C"){
            $usercase = new CalculateConceptGradeUsecase($this->classroomId, $this->discipline, $this->stage);
            $usercase->exec();
            TLog::info("Notas por conceito calculadas com sucesso.", array(
                "Classroom" => $classroom->id,
                "GradeRules" => $gradeRules->id,
                "Rule" => $gradeRules->rule_type
            ));
            return;
        }

        throw new UndefinedRuleTypeException();

    }
}
