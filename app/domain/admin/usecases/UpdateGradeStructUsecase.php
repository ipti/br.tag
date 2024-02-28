<?php

/**
 * Caso de uso para salvavemto da estrutura de notas e avaliação
 *
 * @property string $reply
 * @property string $stage
 * @property [] $unities
 * @property mixed $approvalMedia
 * @property mixed $finalRecoverMedia
 * @property mixed $semiRecoverMedia
 * @property mixed $calculationFinalMedia
 * @property mixed $calcFinalMedia
 * @property bool $hasFinalRecovery
 * @property bool $hasSemianualRecovery
 * @property string $ruleType
 */
class UpdateGradeStructUsecase
{
    private const JUST_CURRENT_STAGE = "";
    private const ALL_STAGES = "A";
    private const STAGES_FROM_SAME_MODALITY = "S";

    public function __construct($reply, $stage, $unities, $approvalMedia, $finalRecoverMedia, $semiRecoverMedia, $calcFinalMedia, $hasFinalRecovery, $hasSemianualRecovery, $ruleType)
    {
        $this->reply = $reply;
        $this->stage = $stage;
        $this->unities = $unities;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
        $this->semiRecoverMedia = $semiRecoverMedia;
        $this->calculationFinalMedia = $calcFinalMedia;
        $this->hasFinalRecovery = $hasFinalRecovery;
        $this->hasSemianualRecovery = $hasSemianualRecovery;
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
                $this->semiRecoverMedia,
                $this->calculationFinalMedia,
                $this->hasFinalRecovery,
                $this->hasSemianualRecovery,
                $this->ruleType
            );
            $justOneUsecase->exec();
        } elseif ($this->reply === self::ALL_STAGES) {
            // A = Toda a Matriz Curricular
            $matrixes = $this->getAllMatrixes();
            $this->saveAndReplayForSimliarStages($matrixes, $this->unities, $this->approvalMedia, $this->finalRecoverMedia, $this->semiRecoverMedia, $this->calculationFinalMedia, $this->hasFinalRecovery, $this->ruleType);
        } elseif ($this->reply === self::STAGES_FROM_SAME_MODALITY) {
            // S = Todas as etapas de a modalidade selecionada.
            $stages = $this->getMatrixesForAllModalities($this->stage);
            $this->saveAndReplayForSimliarStages($stages, $this->unities, $this->approvalMedia, $this->finalRecoverMedia, $this->semiRecoverMedia, $this->calculationFinalMedia, $this->hasFinalRecovery, $this->ruleType);
        }
    }

    private function saveAndReplayForSimliarStages($stages, $unities, $approvalMedia, $finalRecoverMedia, $semiRecoverMedia, $calcFinalMedia, $hasFinalRecovery, $ruleType)
    {
        foreach ($stages as $stage) {
            $justOneUsecase = new UpdateGradeJustOneStructUsecase(
                $stage["id"],
                $unities,
                $approvalMedia,
                $finalRecoverMedia,
                $semiRecoverMedia,
                $calcFinalMedia,
                $hasFinalRecovery,
                $ruleType
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
