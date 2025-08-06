<?php

/**
 * Caso de uso para salvavemto da estrutura de notas e avaliação.
 *
 * @property string $gradeRulesId
 * @property string $gradeRulesName
 * @property string $reply
 * @property [] $stages
 * @property [] $unities
 * @property mixed $approvalMedia
 * @property mixed $finalRecoverMedia
 * @property mixed $calculationFinalMedia
 * @property bool $hasFinalRecovery
 * @property string $ruleType
 * @property bool $hasPartialRecovery
 * @property [] $partialRecoveries
 */
class UpdateGradeJustOneStructUsecase
{
    private const OP_CREATE = 'create';
    private const OP_UPDATE = 'update';
    private const OP_REMOVE = 'remove';

    public function __construct(
        $gradeRulesId,
        $gradeRulesName,
        $stages,
        $unities,
        $approvalMedia,
        $finalRecoverMedia,
        $calculationFinalMedia,
        $hasFinalRecovery,
        $ruleType,
        $hasPartialRecovery,
        $partialRecoveries
    ) {
        $this->gradeRulesId = $gradeRulesId;
        $this->gradeRulesName = $gradeRulesName;
        $this->stages = $stages;
        $this->unities = $unities;
        $this->approvalMedia = $approvalMedia;
        $this->finalRecoverMedia = $finalRecoverMedia;
        $this->calculationFinalMedia = $calculationFinalMedia;
        $this->hasFinalRecovery = $hasFinalRecovery;
        $this->ruleType = $ruleType;
        $this->hasPartialRecovery = $hasPartialRecovery;
        $this->partialRecoveries = $partialRecoveries;
    }

    public function exec()
    {
        $rulesUseCase = new UpdateGradeRulesUsecase(
            $this->gradeRulesId,
            $this->gradeRulesName,
            $this->stages,
            $this->approvalMedia,
            $this->finalRecoverMedia,
            $this->calculationFinalMedia,
            $this->hasFinalRecovery,
            $this->ruleType,
            $this->hasPartialRecovery,
            $this->partialRecoveries
        );
        $gradeRules = $rulesUseCase->exec();
        $this->gradeRulesId = $gradeRules->id;

        $this->buildUnities(
            $this->stage,
            $this->unities
        );

        return $gradeRules;
    }

    private function buildUnities($stage, $unities)
    {
        foreach ($unities as $unity) {
            if (self::OP_CREATE === $unity['operation'] || self::OP_UPDATE === $unity['operation']) {
                $unityModel = GradeUnity::model()->find('id = :id', [':id' => $unity['id']]);

                if (null == $unityModel) {
                    $unityModel = new GradeUnity();
                }

                $unityModel->name = $unity['name'];
                $unityModel->semester = $unity['semester'];
                $unityModel->type = $unity['type'];
                $unityModel->grade_calculation_fk = $unity['formula'];
                $unityModel->grade_rules_fk = $this->gradeRulesId;

                if (!$unityModel->validate()) {
                    throw new CantSaveGradeUnityException($unityModel);
                }

                $unityModel->save();

                $this->buildAvaliationModalities($unityModel, $unity['modalities']);
            } elseif (self::OP_REMOVE === $unity['operation']) {
                GradeUnity::model()->deleteByPk($unity['id']);
            }
        }
    }

    private function buildAvaliationModalities($unity, $modalities)
    {
        foreach ($modalities as $m) {
            if (GradeUnity::TYPE_UNITY_WITH_RECOVERY != $unity && GradeUnityModality::TYPE_RECOVERY == $m->type) {
                $m['operation'] = self::OP_REMOVE;
            }

            if (self::OP_CREATE === $m['operation'] || self::OP_UPDATE === $m['operation']) {
                $modalityModel = GradeUnityModality::model()->find('id = :id', [':id' => $m['id']]);
                if (null == $modalityModel) {
                    $modalityModel = new GradeUnityModality();
                }
                $modalityModel->name = $m['name'];
                $modalityModel->type = $m['type'];
                $modalityModel->weight = $m['weight'];
                $modalityModel->grade_unity_fk = $unity->id;

                if (!$modalityModel->validate()) {
                    throw new CantSaveGradeUnityModalityException($modalityModel);
                }

                $modalityModel->save();
            } elseif (self::OP_REMOVE === $m['operation']) {
                GradeUnityModality::model()->deleteByPk($m['id']);
            }
        }
    }
}
