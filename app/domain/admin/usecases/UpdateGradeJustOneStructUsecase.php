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
class UpdateGradeJustOneStructUsecase
{
    private const OP_CREATE = "create";
    private const OP_UPDATE = "update";
    private const OP_REMOVE = "remove";

    public function __construct($stage, $unities, $approvalMedia, $finalRecoverMedia)
    {
        $this->stage = $stage;
        $this->unities = $unities;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
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
            $this->finalRecoverMedia
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
                $isUnitySaved = $unityModel->save();

                if (!$isUnitySaved) {
                    throw new CantSaveGradeUnityException();
                }

                $this->buildAvaliationModalities($unityModel, $unity["modalities"]);
            } elseif ($unity["operation"] === self::OP_REMOVE) {
                GradeUnity::model()->deleteByPk($unity["id"]);
            }
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
}
