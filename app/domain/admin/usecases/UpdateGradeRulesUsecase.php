<?php

/**
 * Caso de uso para atualização dos parametros para calculo de média
 *
 * @property int $stage
 * @property float $approvalMedia
 * @property float $finalRecoverMedia
 */
class UpdateGradeRulesUsecase
{
    public function __construct($stage, $approvalMedia, $finalRecoverMedia)
    {
        $this->stage = $stage;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
    }

    public function exec()
    {
        $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $this->stage]);
        if ($gradeRules == null) {
            $gradeRules = new GradeRules();
            $gradeRules->edcenso_stage_vs_modality_fk = $this->stage;
        }
        $gradeRules->approvation_media = $this->approvalMedia;
        $gradeRules->final_recover_media = $this->finalRecoverMedia;

        return $gradeRules->save();
    }
}


?>
