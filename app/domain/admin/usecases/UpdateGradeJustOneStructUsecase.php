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
 * @property bool $hasFinalRecovery
 * @property bool $hasSemianualRecovery
 * @property string $ruleType
 */
class UpdateGradeJustOneStructUsecase
{
    private const OP_CREATE = "create";
    private const OP_UPDATE = "update";
    private const OP_REMOVE = "remove";

    public function __construct($stage, $unities, $approvalMedia, $finalRecoverMedia, $semiRecoverMedia, $calculationFinalMedia, $hasFinalRecovery, $hasSemianualRecovery, $ruleType)
    {
        $this->stage = $stage;
        $this->unities = $unities;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
        $this->semiRecoverMedia = $semiRecoverMedia;
        $this->calculationFinalMedia = $calculationFinalMedia;
        $this->hasFinalRecovery = $hasFinalRecovery;
        $this->hasSemianualRecovery = $hasSemianualRecovery;
        $this->ruleType = $ruleType;
    }

    public function exec()
    {
        $this->buildUnities(
            $this->stage,
            $this->unities
        );

        $rulesUseCase = new UpdateGradeRulesUsecase(
            $this->stage,
            $this->approvalMedia,
            $this->finalRecoverMedia,
            $this->semiRecoverMedia,
            $this->calculationFinalMedia,
            $this->hasFinalRecovery,
            $this->hasSemianualRecovery,
            $this->ruleType
        );
        $rulesUseCase->exec();
    }



    private function buildUnities($stage, $unities)
    {
        foreach ($unities as $unity) {
            if ($unity["operation"] === self::OP_CREATE || $unity["operation"] === self::OP_UPDATE) {
                $unityModel = GradeUnity::model()->find('id = :id', [":id" => $unity["id"]]);

                if ($unityModel == null) {
                    $unityModel = new GradeUnity();
                }
                $unityModel->edcenso_stage_vs_modality_fk = $stage;
                $unityModel->name = $unity["name"];
                $unityModel->type = $unity["type"];
                $unityModel->grade_calculation_fk = $unity["formula"];

                if (!$unityModel->validate()) {
                    throw new CantSaveGradeUnityException($unityModel);
                }

                $unityModel->save();

                $this->buildAvaliationModalities($unityModel, $unity["modalities"]);
            } elseif ($unity["operation"] === self::OP_REMOVE) {
                GradeUnity::model()->deleteByPk($unity["id"]);
            }
        }
    }

    private function buildAvaliationModalities($unity, $modalities)
    {
        foreach ($modalities as $m) {

            if ($unity != GradeUnity::TYPE_UNITY_WITH_RECOVERY && $m->type == GradeUnityModality::TYPE_RECOVERY) {
                $m["operation"] = self::OP_REMOVE;
            }

            if ($m["operation"] === self::OP_CREATE || $m["operation"] === self::OP_UPDATE) {
                $modalityModel = GradeUnityModality::model()->find("id = :id", [":id" => $m["id"]]);
                if ($modalityModel == null) {
                    $modalityModel = new GradeUnityModality();
                }
                $modalityModel->name = $m["name"];
                $modalityModel->type = $m["type"];
                $modalityModel->weight = $m["weight"];
                $modalityModel->grade_unity_fk = $unity->id;

                if (!$modalityModel->validate()) {
                    throw new CantSaveGradeUnityModalityException($modalityModel);
                }

                $modalityModel->save();
            } elseif ($m["operation"] === self::OP_REMOVE) {
                GradeUnityModality::model()->deleteByPk($m["id"]);
            }
        }
    }
}
