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
 * @property string  $ruleType
 */
class UpdateGradeRulesUsecase
{
    public function __construct($stage, $approvalMedia, $finalRecoverMedia, $calcFinalMedia, $hasFinalRecovery, $ruleType,
    $hasPartialRecovery,
    $partialRecoveries)
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
        $gradeRules->rule_type = $this->ruleType;

        if($this->hasPartialRecovery) {
            $gradeRules->has_partial_recovery = (int) $this->hasPartialRecovery;
            foreach ($this->partialRecoveries as $partialRecovery) {
                $gradePartialRules = new GradePartialRecovery();
                $gradePartialRules->partial_recover_media = $partialRecovery["partialRecoverMmedia"];
                $gradePartialRules->order_partial_recovery = $partialRecovery["orderPartialRecovery"];
                $gradePartialRules->grade_rules_fk =  $gradeRules->id;
            }
        }

        if(!$gradeRules->validate()){
            Yii::log(TagUtils::stringfyValidationErrors($gradeRules), CLogger::LEVEL_ERROR);
            throw new CantSaveGradeRulesException();
        }

        return $gradeRules->save();
    }
}

