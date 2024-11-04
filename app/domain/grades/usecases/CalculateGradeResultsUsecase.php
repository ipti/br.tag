<?php

/**
 * @property int $classroomId
 * @property int $discipline
 */
class CalculateGradeResultsUsecase {
    private $classroomId;
    private $discipline;
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
            TLog::info("Notas numéricas calculadas com sucesso.", array(
                "Classroom" => $classroom->id,
                "GradeRules" => $gradeRules->id,
                "Rule" => $gradeRules->id
            ));
            return;
        } elseif ($gradeRules->rule_type === "C"){
            $usercase = new CalculateConceptGradeUsecase($this->classroomId, $this->discipline);
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
