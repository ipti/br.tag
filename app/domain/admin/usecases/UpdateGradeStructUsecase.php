<?php

/**
 * Caso de uso para salvavemto da estrutura de notas e avaliação
 *
 * @property string $reply
 * @property string $stage
 * @property [] $unities
 * @property mixed $approvalMedia
 * @property mixed $finalRecoverMedia
 * @property mixed $calculationFinalMedia
 * @property bool $hasFinalRecovery
 * @property string $ruleType
 */
class UpdateGradeStructUsecase
{
    private const JUST_CURRENT_STAGE = "";
    private const ALL_STAGES = "A";
    private const STAGES_FROM_SAME_MODALITY = "S";

    public function __construct($reply, $stage, $unities, $approvalMedia, $finalRecoverMedia, $calculationFinalMedia, $hasFinalRecovery, $ruleType)
    {
        $this->reply = $reply;
        $this->stage = $stage;
        $this->unities = $unities;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
        $this->calculationFinalMedia = $calculationFinalMedia;
        $this->hasFinalRecovery = $hasFinalRecovery;
        $this->ruleType = $ruleType;
    }

    public function exec()
    {
        if ($this->reply === self::JUST_CURRENT_STAGE) {
            $justOneUsecase = new UpdateGradeJustOneStructUsecase(
                $this->stage,
                $this->unities,
                $this->approvalMedia,
                $this->finalRecoverMedia,
                $this->calculationFinalMedia,
                $this->hasFinalRecovery,
                $this->ruleType
            );
            $justOneUsecase->exec();
        } elseif ($this->reply === self::ALL_STAGES) {
            // A = Toda a Matriz Curricular
            $matrixes = $this->getAllMatrixes();
            $this->saveAndReplayForSimliarStages($matrixes, $this->unities, $this->approvalMedia, $this->finalRecoverMedia);
        } elseif ($this->reply === self::STAGES_FROM_SAME_MODALITY) {
            // S = Todas as etapas de a modalidade selecionada.
            $stages = $this->getMatrixesForAllModalities($this->stage);
            $this->saveAndReplayForSimliarStages($stages, $this->unities, $this->approvalMedia, $this->finalRecoverMedia);
        }
    }

    private function saveAndReplayForSimliarStages($stages, $unities, $approvalMedia, $finalRecoverMedia)
    {
        foreach ($stages as $stage) {
            $justOneUsecase = new UpdateGradeJustOneStructUsecase(
                $stage["id"],
                $unities,
                $approvalMedia,
                $finalRecoverMedia
            );
            $justOneUsecase->exec();
        }

    }

    private function getAllMatrixes()
    {
        return Yii::app()->db->createCommand("
                select * from curricular_matrix cm
                where school_year = :year
            ")
            ->bindParam(":year", Yii::app()->user->year)->queryAll();
    }

    private function getMatrixesForAllModalities($stage)
    {
        $modality = EdcensoStageVsModality::model()->find("id = :id", [":id" => $stage]);
        return Yii::app()->db->createCommand("
            select esvm.id from curricular_matrix cm
            join edcenso_stage_vs_modality esvm on esvm.id = cm.stage_fk
            where school_year = :year and esvm.stage = :stage
            group by esvm.id
        ")
            ->bindParam(":year", Yii::app()->user->year)
            ->bindParam(":stage", $modality->stage)
            ->select("id")
            ->queryAll();
    }

    private function getStages()
    {
        return Yii::app()->db->createCommand("
            select
                esvm.id
            from grade g
                join grade_unity_modality gum on g.grade_unity_modality_fk = gum.id
                join grade_unity gu on gu.id = gum.grade_unity_fk
                join edcenso_stage_vs_modality esvm on esvm.id = gu.edcenso_stage_vs_modality_fk
            GROUP BY esvm.id
            HAVING COUNT(1) > 0"
        )
            ->queryAll();
    }

    private function refreshResults($stage)
    {
        try {
            $classrooms = Classroom::model()->findAll(
                "edcenso_stage_vs_modality_fk = :stage and school_year = :year",
                [
                    "stage" => $stage,
                    "year" => Yii::app()->user->year
                ]
            );

            $curricularMatrixes = CurricularMatrix::model()->findAll(
                "stage_fk = :stage",
                [
                    "stage" => $stage
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
