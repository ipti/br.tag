<?php
/**
 * Caso de uso para atualização dos parametros para calculo de média
 *
 * @property int $gradeRules
 * @property [] $partialRecoveries
 */
class UpdateGradePartialRecovery
{
    public function __construct($gradeRules, $partialRecoveries)
    {
        $this->gradeRules = $gradeRules;
        $this->partialRecoveries = $partialRecoveries;
    }
    public function exec()
    {
        $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $this->stage]);
    foreach ($this->partialRecoveries as  $partialRecovery) {
       $modelPartialRecovery = GradePartialRecovery::model()->find($partialRecovery['id']);

       if ($partialRecovery["operation"] === "delete") {
            $modelPartialRecovery->delete();
            echo json_encode(["valid" => true]);
            Yii::app()->end();
        }

        if ($modelPartialRecovery === null) {
            $modelPartialRecovery = new GradePartialRecovery();
        }

        $modelPartialRecovery->name = $partialRecovery["name"];
        $modelPartialRecovery->partial_recover_media = $partialRecovery["media"];
        $modelPartialRecovery->order_partial_recovery = $partialRecovery["order"];
        $modelPartialRecovery->grade_rules_fk = $gradeRules->id;
        $modelPartialRecovery->grade_caculation_fk = $partialRecovery["mediaCalculation"];

        if (!$modelPartialRecovery->validate()) {
            $validationMessage = Yii::app()->utils->stringfyValidationErrors($modelPartialRecovery);
            throw new CHttpException(400, "Não foi possivel salvar dados da recuperação parcial: \n" . $validationMessage, 1);
        }

        foreach ($partialRecovery["unities"] as $unity) {
            $modelUnity = GradeUnity::model()->findByPk($unity);
            $modelUnity->parcial_recovery_fk = $modelPartialRecovery->id;

            $modelUnity->save();
        }

        $modelPartialRecovery->save();
    }
    }
}
