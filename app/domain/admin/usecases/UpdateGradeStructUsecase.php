<?php

/**
 * Caso de uso para salvavemto da estrutura de notas e avaliação
 *
 * @property string $gradeRulesId
 * @property string $gradeRulesName
 * @property string $reply
 * @property [] $stages
 * @property [] $unities
 * @property mixed $approvalMedia
 * @property mixed $finalRecoverMedia
 * @property mixed $calcFinalMedia
 * @property bool $hasFinalRecoverys
 * @property string $ruleType
 * @property bool $hasPartialRecovery
 * @property [] $partialRecoveries
 */
class UpdateGradeStructUsecase
{
    private const JUST_CURRENT_STAGE = "";
    private const ALL_STAGES = "A";
    private const STAGES_FROM_SAME_MODALITY = "S";

    public function __construct($gradeRulesId, $gradeRulesName,$reply, $stages, $unities, $approvalMedia,
        $finalRecoverMedia, $calcFinalMedia, $hasFinalRecovery, $ruleType, $hasPartialRecovery, $partialRecoveries)
    {
        $this->gradeRulesId = $gradeRulesId;
        $this->gradeRulesName = $gradeRulesName;
        $this->reply = $reply;
        $this->stages = $stages;
        $this->unities = $unities;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
        $this->calculationFinalMedia = $calcFinalMedia;
        $this->hasFinalRecovery = $hasFinalRecovery;
        $this->ruleType = $ruleType;
        $this->hasPartialRecovery = $hasPartialRecovery;
        $this->partialRecoveries = $partialRecoveries;
    }

    public function exec()
    {
        if ($this->reply === self::JUST_CURRENT_STAGE) {
            $justOneUsecase = new UpdateGradeJustOneStructUsecase(
                $this->gradeRulesId,
                $this->gradeRulesName,
                $this->stages,
                $this->unities,
                $this->approvalMedia,
                $this->finalRecoverMedia,
                $this->calculationFinalMedia,
                $this->hasFinalRecovery,
                $this->ruleType,
                $this->hasPartialRecovery,
                $this->partialRecoveries
            );
            return $justOneUsecase->exec();
        } elseif ($this->reply === self::ALL_STAGES) {
            // A = Toda a Matriz Curricular
            $matrixes = $this->getAllMatrixes();
            $this->saveAndReplayForSimliarStages($matrixes, $this->unities, $this->approvalMedia, $this->finalRecoverMedia, $this->calculationFinalMedia, $this->hasFinalRecovery, $this->ruleType, $this->partialRecoveries);
        } elseif ($this->reply === self::STAGES_FROM_SAME_MODALITY) {
            // S = Todas as etapas de a modalidade selecionada.
            $stages = $this->getMatrixesForAllModalities($this->stages);
            $this->saveAndReplayForSimliarStages($stages, $this->unities, $this->approvalMedia, $this->finalRecoverMedia, $this->calculationFinalMedia, $this->hasFinalRecovery, $this->ruleType, $this->partialRecoveries);
        }
    }

    private function saveAndReplayForSimliarStages($stages, $unities, $approvalMedia, $finalRecoverMedia, $calcFinalMedia, $hasFinalRecovery, $ruleType, $partialRecoveries)
    {
        foreach ($stages as $stage) {
            $justOneUsecase = new UpdateGradeJustOneStructUsecase(
                $this->gradeRulesId,
                $this->gradeRulesName,
                $stage["id"],
                $unities,
                $approvalMedia,
                $finalRecoverMedia,
                $calcFinalMedia,
                $hasFinalRecovery,
                $ruleType,
                $partialRecoveries
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
}
