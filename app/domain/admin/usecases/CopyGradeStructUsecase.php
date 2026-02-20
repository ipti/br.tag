<?php

/**
 * Caso de uso para copiar uma estrutura de notas e avaliação.
 *
 * A cópia é feita em três etapas para respeitar as dependências de FK:
 *  1. GradeUnity SEM parcial_recovery_fk  →  constrói $unityIdMap
 *  2. GradePartialRecovery + pesos        →  remapeia unity_fk via $unityIdMap; constrói $partialRecoveryIdMap
 *  3. GradeUnity COM parcial_recovery_fk  →  remapeia parcial_recovery_fk via $partialRecoveryIdMap
 *
 * @property int $sourceId  ID da estrutura de origem
 * @property int $schoolYear Ano letivo da cópia (defaults to source year)
 */
class CopyGradeStructUsecase
{
    /** @var int */
    private $sourceId;

    /** @var int|null */
    private $schoolYear;

    public function __construct($sourceId, $schoolYear = null)
    {
        $this->sourceId   = (int) $sourceId;
        $this->schoolYear = $schoolYear;
    }

    /**
     * Executa a cópia e retorna o novo GradeRules criado.
     *
     * @return GradeRules
     * @throws CHttpException se a estrutura de origem não for encontrada
     */
    public function exec(): GradeRules
    {
        $source = GradeRules::model()->findByPk($this->sourceId);
        if ($source === null) {
            throw new CHttpException(404, 'Estrutura não encontrada.');
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            $newGradeRules = $this->cloneGradeRules($source);
            $this->cloneStageAssociations($source, $newGradeRules);
            $unityIdMap            = $this->cloneStandaloneUnities($source, $newGradeRules);
            $partialRecoveryIdMap  = $this->clonePartialRecoveries($source, $newGradeRules, $unityIdMap);
            $this->cloneLinkedUnities($source, $newGradeRules, $partialRecoveryIdMap);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($e->getTraceAsString(), CLogger::LEVEL_ERROR);
            throw $e;
        }

        return $newGradeRules;
    }

    // -------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------

    private function cloneGradeRules(GradeRules $source): GradeRules
    {
        $new                      = new GradeRules();
        $new->name                = $source->name . ' (Cópia)';
        $new->school_year         = $this->schoolYear ?? $source->school_year;
        $new->approvation_media   = $source->approvation_media;
        $new->final_recover_media = $source->final_recover_media;
        $new->grade_calculation_fk = $source->grade_calculation_fk;
        $new->has_final_recovery  = $source->has_final_recovery;
        $new->has_partial_recovery = $source->has_partial_recovery;
        $new->rule_type           = $source->rule_type;
        $new->save(false);
        return $new;
    }

    private function cloneStageAssociations(GradeRules $source, GradeRules $newGradeRules): void
    {
        $assocs = GradeRulesVsEdcensoStageVsModality::model()->findAllByAttributes(['grade_rules_fk' => $source->id]);
        foreach ($assocs as $assoc) {
            $newAssoc = new GradeRulesVsEdcensoStageVsModality();
            $newAssoc->grade_rules_fk               = $newGradeRules->id;
            $newAssoc->edcenso_stage_vs_modality_fk = $assoc->edcenso_stage_vs_modality_fk;
            $newAssoc->save(false);
        }
    }

    /**
     * STEP 1 – Clona GradeUnity sem parcial_recovery_fk.
     *
     * @return array<int,int>  mapa oldUnityId => newUnityId
     */
    private function cloneStandaloneUnities(GradeRules $source, GradeRules $newGradeRules): array
    {
        $unityIdMap = [];

        $criteria            = new CDbCriteria();
        $criteria->condition = 'grade_rules_fk = :grf AND (parcial_recovery_fk IS NULL OR parcial_recovery_fk = 0)';
        $criteria->params    = [':grf' => $source->id];
        $criteria->order     = 'id ASC';

        foreach (GradeUnity::model()->findAll($criteria) as $unity) {
            $newUnity = $this->cloneUnity($unity, $newGradeRules->id, null);
            $unityIdMap[$unity->id] = $newUnity->id;
            $this->cloneModalities($unity->id, $newUnity->id);
        }

        return $unityIdMap;
    }

    /**
     * STEP 2 – Clona GradePartialRecovery e seus pesos (com remapeamento de unity_fk).
     *
     * @param  array<int,int> $unityIdMap
     * @return array<int,int> mapa oldPartialRecoveryId => newPartialRecoveryId
     */
    private function clonePartialRecoveries(GradeRules $source, GradeRules $newGradeRules, array $unityIdMap): array
    {
        $partialRecoveryIdMap = [];

        foreach (GradePartialRecovery::model()->findAllByAttributes(['grade_rules_fk' => $source->id]) as $pr) {
            $newPr                        = new GradePartialRecovery();
            $newPr->grade_rules_fk        = $newGradeRules->id;
            $newPr->name                  = $pr->name;
            $newPr->order_partial_recovery = $pr->order_partial_recovery;
            $newPr->grade_calculation_fk  = $pr->grade_calculation_fk;
            $newPr->semester              = $pr->semester;
            $newPr->save(false);

            $partialRecoveryIdMap[$pr->id] = $newPr->id;

            // Pesos – remapeia unity_fk para o novo ID
            foreach (GradePartialRecoveryWeights::model()->findAllByAttributes(['partial_recovery_fk' => $pr->id]) as $w) {
                $newW                      = new GradePartialRecoveryWeights();
                $newW->partial_recovery_fk = $newPr->id;
                $newW->unity_fk            = ($w->unity_fk !== null && isset($unityIdMap[$w->unity_fk]))
                                             ? $unityIdMap[$w->unity_fk]
                                             : $w->unity_fk;
                $newW->weight              = $w->weight;
                $newW->save(false);
            }
        }

        return $partialRecoveryIdMap;
    }

    /**
     * STEP 3 – Clona GradeUnity com parcial_recovery_fk (remapeado).
     *
     * @param array<int,int> $partialRecoveryIdMap
     */
    private function cloneLinkedUnities(GradeRules $source, GradeRules $newGradeRules, array $partialRecoveryIdMap): void
    {
        $criteria            = new CDbCriteria();
        $criteria->condition = 'grade_rules_fk = :grf AND parcial_recovery_fk IS NOT NULL AND parcial_recovery_fk != 0';
        $criteria->params    = [':grf' => $source->id];
        $criteria->order     = 'id ASC';

        foreach (GradeUnity::model()->findAll($criteria) as $unity) {
            $newPrId  = $partialRecoveryIdMap[$unity->parcial_recovery_fk] ?? null;
            $newUnity = $this->cloneUnity($unity, $newGradeRules->id, $newPrId);
            $this->cloneModalities($unity->id, $newUnity->id);
        }
    }

    /**
     * Cria um novo GradeUnity baseado no original, com grade_rules_fk e parcial_recovery_fk fornecidos.
     */
    private function cloneUnity(GradeUnity $unity, int $newGradeRulesId, ?int $newParcialRecoveryFk): GradeUnity
    {
        $newUnity                                 = new GradeUnity();
        $newUnity->grade_rules_fk                 = $newGradeRulesId;
        $newUnity->name                           = $unity->name;
        $newUnity->type                           = $unity->type;
        $newUnity->semester                       = $unity->semester;
        $newUnity->weight                         = $unity->weight;
        $newUnity->grade_calculation_fk           = $unity->grade_calculation_fk;
        $newUnity->weight_final_media             = $unity->weight_final_media;
        $newUnity->weight_final_recovery          = $unity->weight_final_recovery;
        $newUnity->final_recovery_avarage_formula = $unity->final_recovery_avarage_formula;
        $newUnity->parcial_recovery_fk            = $newParcialRecoveryFk;
        $newUnity->save(false);
        return $newUnity;
    }

    /**
     * Clona as GradeUnityModality de uma unity para a nova unity.
     */
    private function cloneModalities(int $oldUnityId, int $newUnityId): void
    {
        foreach (GradeUnityModality::model()->findAllByAttributes(['grade_unity_fk' => $oldUnityId]) as $modality) {
            $newModality                 = new GradeUnityModality();
            $newModality->grade_unity_fk = $newUnityId;
            $newModality->name           = $modality->name;
            $newModality->type           = $modality->type;
            $newModality->weight         = $modality->weight;
            $newModality->save(false);
        }
    }
}
