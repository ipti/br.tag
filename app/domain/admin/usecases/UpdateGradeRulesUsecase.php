<?php

declare(strict_types=1);

/**
 * Caso de uso para atualização dos parametros para calculo de média
 *
 * @property int $stage
 * @property float $approvalMedia
 * @property float $finalRecoverMedia
 * @property int $calcFinalMedia
 * @property bool  $hasFinalRecovery
 * @property bool  $hasPartialRecovery
 * @property []  $partialRecoveries
 * @property string  $ruleType
 */
class UpdateGradeRulesUsecase
{
    public function __construct($stage, $approvalMedia, $finalRecoverMedia, $calcFinalMedia, $hasFinalRecovery, $ruleType,
    $hasPartialRecovery, $partialRecoveries)
    {
        $this->stage = $stage;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
        $this->calcFinalMedia = $calcFinalMedia;
        $this->hasFinalRecovery = $hasFinalRecovery;
        $this->ruleType = $ruleType;
        $this->hasPartialRecovery = $hasPartialRecovery;
        $this->partialRecoveries = $partialRecoveries;
    }

    public function exec()
    {
        /**
         * @var GradeRules $gradeRules
         * */
        $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $this->stage]);
        if ($gradeRules == null) {
            $gradeRules = new GradeRules();
            $gradeRules->edcenso_stage_vs_modality_fk = $this->stage;
        }

        $gradeRules->approvation_media = $this->approvalMedia;
        $gradeRules->final_recover_media = $this->finalRecoverMedia;
        $gradeRules->grade_calculation_fk = $this->calcFinalMedia;
        $gradeRules->has_final_recovery = (int) $this->hasFinalRecovery;
        $gradeRules->has_partial_recovery = (int) $this->hasPartialRecovery;
        $gradeRules->rule_type = $this->ruleType;

        if(!$gradeRules->validate()){
            Yii::log(TagUtils::stringfyValidationErrors($gradeRules), CLogger::LEVEL_ERROR);
            throw new CantSaveGradeRulesException();
        }
       $result = $gradeRules->save();

        if($this->hasPartialRecovery === true) {

            foreach ($this->partialRecoveries as  $partialRecovery) {
            $modelPartialRecovery = GradePartialRecovery::model()->findByPk($partialRecovery['id']);

                if ($partialRecovery["operation"] === "delete") {
                    $modelPartialRecovery->delete();
                    echo json_encode(["valid" => true]);
                    Yii::app()->end();
                }

                if ($modelPartialRecovery === null) {
                    $modelPartialRecovery = new GradePartialRecovery();
                }

                $modelPartialRecovery->name = $partialRecovery["name"];
                $modelPartialRecovery->order_partial_recovery = $partialRecovery["order"];
                $modelPartialRecovery->grade_rules_fk = $gradeRules->id;
                $modelPartialRecovery->grade_calculation_fk = $partialRecovery["mediaCalculation"];

                if (!$modelPartialRecovery->validate()) {
                    $validationMessage = Yii::app()->utils->stringfyValidationErrors($modelPartialRecovery);
                    throw new CHttpException(400, "Não foi possivel salvar dados da recuperação parcial: \n" . $validationMessage, 1);
                }

                $modelPartialRecovery->save();
                foreach ($partialRecovery["unities"] as $unity) {
                    $modelUnity = GradeUnity::model()->findByPk($unity);
                    $modelUnity->parcial_recovery_fk = $modelPartialRecovery->id;

                    $modelUnity->save();
                }

            }
        }
        return $result;
    }
}

