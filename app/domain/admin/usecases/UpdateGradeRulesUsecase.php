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
 * @property string  $ruleType
 */
class UpdateGradeRulesUsecase
{
    public function __construct($stage, $approvalMedia, $finalRecoverMedia, $calcFinalMedia, $hasFinalRecovery, $ruleType)
    {
        $this->stage = $stage;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
        $this->calcFinalMedia = $calcFinalMedia;
        $this->hasFinalRecovery = $hasFinalRecovery;
        $this->ruleType = $ruleType;
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
        $gradeRules->rule_type = $this->ruleType;

        if(!$gradeRules->validate()){
            Yii::log(TagUtils::stringfyValidationErrors($gradeRules), CLogger::LEVEL_ERROR);
            throw new CantSaveGradeRulesException();
        }

        return $gradeRules->save();
    }
}

