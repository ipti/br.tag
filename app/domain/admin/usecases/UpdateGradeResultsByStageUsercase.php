<?php
/**
 * @property int $stage
 * @property int $year
 */
class UpdateGradeResultsByStageUsercase
{
    public function __construct($stage, $year) {
        $this->stage = $stage;
        $this->year = $year;
    }
    public function exec(){
        try {
            $classrooms = Classroom::model()->findAll(
                "edcenso_stage_vs_modality_fk = :stage and school_year = :year",
                [
                    "stage" => $this->stage,
                    "year" => $this->year
                ]
            );

            $curricularMatrixes = CurricularMatrix::model()->findAll(
                "stage_fk = :stage",
                [
                    "stage" => $this->stage
                ]
            );

            foreach ($classrooms as $classroom) {
                foreach ($curricularMatrixes as $curricularMatrix) {
                    // EnrollmentController::saveGradeResults($classroom->id, $curricularMatrix->discipline_fk);
                }
            }
        } catch (\Throwable $e) {
            throw new CantSaveGradeResultsException();
        }

    }
}



?>
