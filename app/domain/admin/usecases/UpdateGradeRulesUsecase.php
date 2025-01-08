<?php

declare(strict_types=1);

/**
 * Caso de uso para atualização dos parametros para calculo de média
 *
 * @property int $gradeRulesId
 * @property string $gradeRulesName
 * @property [] $stages
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
    public function __construct($gradeRulesId, $gradeRulesName, $stages, $approvalMedia, $finalRecoverMedia, $calcFinalMedia, $hasFinalRecovery, $ruleType,
    $hasPartialRecovery, $partialRecoveries)
    {
        $this->gradeRulesId = $gradeRulesId;
        $this->gradeRulesName = $gradeRulesName;
        $this->stages = $stages;
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
        $gradeRules = GradeRules::model()->findByPk($this->gradeRulesId);
        if ($gradeRules == null) {
            $gradeRules = new GradeRules();
        }


        // $gradeRules->edcenso_stage_vs_modality_fk = $this->stages;
        $gradeRules->approvation_media = $this->approvalMedia;
        $gradeRules->name = $this->gradeRulesName;
        $gradeRules->final_recover_media = $this->finalRecoverMedia;
        $gradeRules->grade_calculation_fk = $this->calcFinalMedia;
        $gradeRules->has_final_recovery = (int) $this->hasFinalRecovery;
        $gradeRules->has_partial_recovery = (int) $this->hasPartialRecovery;
        $gradeRules->rule_type = $this->ruleType;

        if(!$gradeRules->validate()) {
            Yii::log(TagUtils::stringfyValidationErrors($gradeRules), CLogger::LEVEL_ERROR);
            throw new CantSaveGradeRulesException();
        }
       $result = $gradeRules->save();

       $gradeRulesVsStage = GradeRulesVsEdcensoStageVsModality::model()->findAllByAttributes(["grade_rules_fk" => $gradeRules->id]);

       $this->deleteGradeRulesVsStage($gradeRulesVsStage);

       foreach ($this->stages as $stage) {
            $gradeRulesVsStage = new GradeRulesVsEdcensoStageVsModality();
            $gradeRulesVsStage->edcenso_stage_vs_modality_fk = $stage;
            $gradeRulesVsStage->grade_rules_fk = $gradeRules->id;
            $gradeRulesVsStage->save();
       }

        if($this->hasPartialRecovery === true) {
            $pRecoveryUseCase = new UpdateGradePartialRecoveryUsecase($gradeRules->id, $this->partialRecoveries);
            $pRecoveryUseCase->exec();
        }
        return $gradeRules;
    }
       public function deleteGradeRulesVsStage($gradeRulesVsStages) {
        foreach($gradeRulesVsStages as $gradeRulesVsStage) {

            $gradeRulesVsStage->delete();
        }
    }
}

