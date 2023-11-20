<?php

/**
 * Caso de uso para salvavemto da estrutura de notas e avaliação
 *
 * @property string $reply
 * @property string $stage
 * @property [] $unities
 * @property mixed $approvalMedia
 * @property mixed $finalRecoverMedia
 */
class UpdateGradeStructUsecase
{
    private const JUST_CURRENT_STAGE = "";
    private const ALL_STAGES = "A";
    private const STAGES_FROM_SAME_MODALITY = "S";

    private const OP_CREATE = "create";
    private const OP_UPDATE = "update";
    private const OP_REMOVE = "remove";

    public function __construct($reply, $stage, $unities, $approvalMedia, $finalRecoverMedia)
    {
        $this->reply = $reply;
        $this->stage = $stage;
        $this->unities = $unities;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
    }

    public function exec()
    {
        if ($this->reply === self::JUST_CURRENT_STAGE) {
            $this->saveStruntForOneStage($this->stage, $this->unities, $this->approvalMedia, $this->finalRecoverMedia);
        } elseif ($this->reply === self::ALL_STAGES) {
            // A = Toda a Matriz Curricular
            $matrixes = $this->getAllMatrixes();
            $this->saveAndReplayForSimliarStages($matrixes, $this->unities, $this->approvalMedia, $this->finalRecoverMedia);
        } elseif ($this->reply === self::STAGES_FROM_SAME_MODALITY) {
            // S = Todas as etapas de a modalidade selecionada.
            $matrixes = $this->getMatrixesForAllModalities($this->stage);
            $this->saveAndReplayForSimliarStages($matrixes, $this->unities, $this->approvalMedia, $this->finalRecoverMedia);
        }
    }

    private function saveStruntForOneStage($stage, $unities, $approvalMedia, $finalRecoverMedia)
    {
        $this->buildUnities($stage, $unities);

        $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $stage]);
        if ($gradeRules == null) {
            $gradeRules = new GradeRules();
            $gradeRules->edcenso_stage_vs_modality_fk = $stage;
        }

        $gradeRules->approvation_media = $approvalMedia;
        $gradeRules->final_recover_media = $finalRecoverMedia;
        $isSaved = $gradeRules->save();

        if (!$isSaved) {
            throw new CantSaveGradeRulesException();
        }

        $this->refreshResults($stage);
    }

    private function saveAndReplayForSimliarStages($curricularMatrixes, $unities, $approvalMedia, $finalRecoverMedia)
    {

        $stages = $this->getStages();

        foreach ($curricularMatrixes as $curricularMatrix) {
            $matrixStageFk = $curricularMatrix["stage_fk"];
            if (array_search($matrixStageFk, array_column($stages, "id")) === false) {
                $this->buildUnities($matrixStageFk, $unities);
            }
            $this->updateGradeRules($matrixStageFk, $approvalMedia, $finalRecoverMedia);
            $this->refreshResults($matrixStageFk);
        }
    }

    private function updateGradeRules($matrixStageFk, $approvalMedia, $finalRecoverMedia)
    {
        $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $matrixStageFk]);
        if ($gradeRules == null) {
            $gradeRules = new GradeRules();
            $gradeRules->edcenso_stage_vs_modality_fk = $matrixStageFk;
        }
        $gradeRules->approvation_media = $approvalMedia;
        $gradeRules->final_recover_media = $finalRecoverMedia;

        $isSaved = $isSaved = $gradeRules->save();

        if (!$isSaved) {
            throw new CantSaveGradeRulesException();
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
            select * from curricular_matrix cm
            join edcenso_stage_vs_modality esvm on esvm.id = cm.stage_fk
            where school_year = :year and esvm.stage = :stage
        ")
            ->bindParam(":year", Yii::app()->user->year)
            ->bindParam(":stage", $modality->stage)
            ->queryAll();
    }

    private function buildUnities($stage, $unities)
    {
        foreach ($unities as $unity) {
            $unityModel = GradeUnity::model()->find('id = :id', [":id" => $unity["id"]]);

            if ($unityModel == null) {
                $unityModel = new GradeUnity();
            }
            $unityModel->edcenso_stage_vs_modality_fk = $stage;
            $unityModel->name = $unity["name"];
            $unityModel->type = $unity["type"];
            $unityModel->grade_calculation_fk = $unity["formula"];
            $isUnitySaved = $unityModel->save();

            if (!$isUnitySaved) {
                throw new CantSaveGradeUnityException();
            }

            $this->buildAvaliationModalities($unityModel, $unity["modalities"]);
        }
    }

    private function buildAvaliationModalities($unity, $modalities)
    {
        foreach ($modalities as $m) {
            if ($m["operation"] === self::OP_CREATE || $m["operation"] === self::OP_UPDATE) {
                $modalityModel = GradeUnityModality::model()->find("id = :id", [":id" => $m["id"]]);
                if ($modalityModel == null) {
                    $modalityModel = new GradeUnityModality();
                }
                $modalityModel->name = $m["name"];
                $modalityModel->type = $m["type"];
                $modalityModel->weight = $m["weight"];
                $modalityModel->grade_unity_fk = $unity->id;
                $isUnitySaved = $modalityModel->save();
                if (!$isUnitySaved) {
                    throw new CantSaveGradeUnityModalityException();
                }
            } elseif ($m["operation"] === self::OP_REMOVE) {
                GradeUnityModality::model()->deleteByPk($m["id"]);
            }
        }
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
