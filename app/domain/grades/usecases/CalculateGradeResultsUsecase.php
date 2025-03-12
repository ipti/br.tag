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
        if($this->stage === "") {
            $this->stage = $classroom->edcenso_stage_vs_modality_fk;
        }
        $criteria = new CDbCriteria();
        $criteria->alias = 'gr';
        $criteria->select = 'gr.*';
        $criteria->join = 'INNER JOIN grade_rules_vs_edcenso_stage_vs_modality grvesvm ON grvesvm.grade_rules_fk = gr.id ';
        $criteria->join .= 'INNER JOIN classroom_vs_grade_rules cvgr ON cvgr.grade_rules_fk = gr.id';
        $criteria->condition = 'cvgr.classroom_fk = :classroomId and grvesvm.edcenso_stage_vs_modality_fk = :stageId';
        $criteria->params = array(':classroomId' => $this->classroomId, ':stageId' => $this->stage);

        $gradeRules = GradeRules::model()->find($criteria);

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
