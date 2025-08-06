<?php

/**
 * Caso de uso para atualização dos parametros para calculo de média.
 *
 * @property int $gradeRules
 * @property [] $partialRecoveries
 */
class UpdateGradePartialRecoveryUsecase
{
    public function __construct($gradeRules, $partialRecoveries)
    {
        $this->gradeRules = $gradeRules;
        $this->partialRecoveries = $partialRecoveries;
    }

    public function exec()
    {
        foreach ($this->partialRecoveries as  $partialRecovery) {
            $modelPartialRecovery = GradePartialRecovery::model()->findByPk($partialRecovery['id']);

            if ($partialRecovery['operation'] === 'delete') {
                $this->deleteRecovery($modelPartialRecovery);
                echo json_encode(['valid' => true]);
                Yii::app()->end();
            }

            if ($modelPartialRecovery === null) {
                $modelPartialRecovery = new GradePartialRecovery();
            }

            $modelPartialRecovery->name = $partialRecovery['name'];
            $modelPartialRecovery->order_partial_recovery = $partialRecovery['order'];
            $modelPartialRecovery->grade_rules_fk = $this->gradeRules;
            $modelPartialRecovery->grade_calculation_fk = $partialRecovery['mediaCalculation'];
            $modelPartialRecovery->semester = $partialRecovery['semester'];

            if (!$modelPartialRecovery->validate()) {
                $validationMessage = Yii::app()->utils->stringfyValidationErrors($modelPartialRecovery);

                throw new CHttpException(400, "Não foi possivel salvar dados da recuperação parcial: \n".$validationMessage, 1);
            }

            if ($modelPartialRecovery->save()) {
                if ($partialRecovery['weights'] != null) {
                    $this->savePartialRecoveryWeights($partialRecovery['weights'], $modelPartialRecovery->id);
                }

                if ($modelPartialRecovery->gradeCalculationFk->name !== 'Peso') {
                    $this->deletePartialRecoveryWeights($modelPartialRecovery->id);
                }

                $this->savePartialRecoveryOnUnities($partialRecovery['unities'], $modelPartialRecovery->id);
            }
        }
    }

    private function deleteRecovery($modelPartialRecovery)
    {
        $this->removePartialRecoveryFromUnity($modelPartialRecovery->id);
        $this->deleteGradesPartialRecovery($modelPartialRecovery->id);
        $this->deletePartialRecoveryWeights($modelPartialRecovery->id);
        $modelPartialRecovery->delete();
    }

    private function removePartialRecoveryFromUnity($recoveryId)
    {
        $unities = GradeUnity::model()->findAllByAttributes(['parcial_recovery_fk' => $recoveryId]);
        foreach ($unities as $unity) {
            $unity->parcial_recovery_fk = null;
            $unity->save();
        }
    }

    private function deleteGradesPartialRecovery($recoveryId)
    {
        $grades = Grade::model()->findAllByAttributes(['grade_partial_recovery_fk' => $recoveryId]);
        foreach ($grades as $grade) {
            $grade->delete();
        }
    }

    private function deletePartialRecoveryWeights($recoveryId)
    {
        $recoveryWeights = GradePartialRecoveryWeights::model()->findAllByAttributes(['partial_recovery_fk' => $recoveryId]);
        foreach ($recoveryWeights as $recoveryWeight) {
            $recoveryWeight->delete();
        }
    }

    private function savePartialRecoveryWeights($weights, $partialRecoveryId)
    {
        foreach ($weights as $recoveryWeight) {
            $recoveryWeights = GradePartialRecoveryWeights::model()->findByPk($recoveryWeight->id);
            if ($recoveryWeights == null) {
                $recoveryWeights = new GradePartialRecoveryWeights();
            }
            $recoveryWeights->weight = $recoveryWeight['weight'];
            $recoveryWeights->partial_recovery_fk = $partialRecoveryId;
            $recoveryWeights->unity_fk = $recoveryWeight['unityId'];
            $recoveryWeights->save();
        }
    }

    private function savePartialRecoveryOnUnities($unities, $partialRecoveryId)
    {
        $this->removePartialRecoveryFromUnity($partialRecoveryId);
        foreach ($unities as $unity) {
            $modelUnity = GradeUnity::model()->findByPk($unity);
            $modelUnity->parcial_recovery_fk = $partialRecoveryId;

            $modelUnity->save();
        }
    }
}
