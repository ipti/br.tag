<?php

/**
 * GradesStructureController
 * Manages grade rules (evaluation structures): listing, creating/editing, deleting, and copying.
 * Moved from AdminController. All lists are filtered by the session year.
 */
class GradesStructureController extends Controller
{
    public $layout = 'fullmenu';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    /**
     * Specifies the access control rules.
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'index',
                    'create',
                    'delete',
                    'getunities',
                    'saveunities',
                    'copy',
                ],
                'users' => ['@'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    // -------------------------------------------------------
    // Index – list grade structures filtered by session year
    // -------------------------------------------------------

    public function actionIndex()
    {
        $year = Yii::app()->user->year;

        $criteria        = new CDbCriteria();
        $criteria->order = 'id DESC';

        if ($year < 2026) {
            // Legacy structures (no school_year) are visible for any pre-2026 year
            $criteria->condition = 'school_year = :year OR school_year IS NULL';
            $criteria->params    = [':year' => $year];
        } else {
            $criteria->condition = 'school_year = :year';
            $criteria->params    = [':year' => $year];
        }

        $dataProvider = new CActiveDataProvider('GradeRules', [
            'criteria'   => $criteria,
            'pagination' => false,
        ]);

        $this->render('//admin/indexGradesStructure', [
            'dataProvider' => $dataProvider,
        ]);
    }

    // -------------------------------------------------------
    // Create / Edit form
    // -------------------------------------------------------

    public function actionCreate()
    {
        $stages = Yii::app()->db->createCommand('
            select
                distinct esvm.id,
                esvm.name
            from edcenso_stage_vs_modality esvm
                join curricular_matrix cm on cm.stage_fk = esvm.id order by esvm.name')
            ->queryAll();

        $formulas   = GradeCalculation::model()->findAll();
        $gradeUnity = new GradeUnity();
        $this->render('//admin/gradesStructure', [
            'gradeUnity' => $gradeUnity,
            'stages'     => $stages,
            'formulas'   => $formulas,
        ]);
    }

    // -------------------------------------------------------
    // Delete
    // -------------------------------------------------------

    public function actionDelete($id)
    {
        $gradeRules = GradeRules::model()->findByPK($id);

        // Check grades on units
        $criteria = new CDbCriteria();
        $criteria->alias = 'g';
        $criteria->join  = 'join grade_unity_modality gum on gum.id = g.grade_unity_modality_fk ';
        $criteria->join .= 'join grade_unity gu on gu.id = gum.grade_unity_fk ';
        $criteria->join .= 'join grade_rules gr on gr.id = gu.grade_rules_fk';
        $criteria->condition = 'gr.id = :grade_rules_id and g.grade IS NOT NULL and g.grade != 0';
        $criteria->params    = [':grade_rules_id' => $gradeRules->id];
        $hasUnityGrades      = Grade::model()->count($criteria) > 0;

        // Check grades on partial recoveries
        $criteria = new CDbCriteria();
        $criteria->alias = 'g';
        $criteria->join  = 'join grade_partial_recovery gpr on gpr.id = g.grade_partial_recovery_fk ';
        $criteria->join .= 'join grade_rules gr on gr.id = gpr.grade_rules_fk';
        $criteria->condition = 'gr.id = :grade_rules_id and g.grade IS NOT NULL and g.grade != 0';
        $criteria->params    = [':grade_rules_id' => $gradeRules->id];
        $hasRecoveryGrades   = Grade::model()->count($criteria) > 0;

        if ($hasUnityGrades === true || $hasRecoveryGrades === true) {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível apagar a estrutura, pois já existem notas cadastradas para ela.'));
            $this->redirect(['index']);
        }

        $classroomsVsGradeRulesCount = ClassroomVsGradeRules::model()->countByAttributes(['grade_rules_fk' => $gradeRules->id]) > 0;
        if ($classroomsVsGradeRulesCount === true) {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível apagar a estrutura, pois ela já está vinculada a alguma turma.'));
            $this->redirect(['index']);
        }

        if (!empty($gradeRules->created_at) && (date('Y', strtotime($gradeRules->created_at)) < 2025)) {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível apagar a estrutura.'));
            $this->redirect(['index']);
        }

        $transaction = Yii::app()->db->beginTransaction();
        try {
            GradeRulesVsEdcensoStageVsModality::model()->deleteAllByAttributes(['grade_rules_fk' => $id]);
            $this->deletePartialRecoveries($id);
            $this->deleteUnities($id);
            $gradeRules->delete();
            $transaction->commit();
            Yii::app()->user->setFlash('notice', Yii::t('default', 'Estrutura apagada com sucesso!'));
        } catch (Exception $e) {
            $transaction->rollback();
            Yii::app()->user->setFlash('error', Yii::t('default', 'Erro ao apagar estrutura.'));
        }
        $this->redirect(['index']);
    }

    private function deletePartialRecoveries($gradeRuleId)
    {
        $gradePartialRecoveries = GradePartialRecovery::model()->findAllByAttributes([
            'grade_rules_fk' => $gradeRuleId,
        ]);
        foreach ($gradePartialRecoveries as $partialRecovery) {
            Grade::model()->deleteAllByAttributes(['grade_partial_recovery_fk' => $partialRecovery->id]);
            $partialRecovery->delete();
        }
    }

    private function deleteUnities($gradeRuleId)
    {
        $unities = GradeUnity::model()->findAllByAttributes(['grade_rules_fk' => $gradeRuleId]);
        foreach ($unities as $unity) {
            $modalities = GradeUnityModality::model()->findAllByAttributes(['grade_unity_fk' => $unity->id]);
            foreach ($modalities as $modality) {
                Grade::model()->deleteAllByAttributes(['grade_unity_modality_fk' => $modality->id]);
                $modality->delete();
            }
            $unity->delete();
        }
    }

    // -------------------------------------------------------
    // Get Unities (AJAX)
    // -------------------------------------------------------

    public function actionGetunities()
    {
        $gradeRulesId = Yii::app()->request->getPost('grade_rules_id');

        $result            = [];
        $result['unities'] = [];

        $criteria = new CDbCriteria();
        $criteria->alias = 'gu';
        $criteria->condition = 'grade_rules_fk = :grade_rules_fk';
        $criteria->addInCondition('gu.type', [GradeUnity::TYPE_UNITY, GradeUnity::TYPE_UNITY_BY_CONCEPT, GradeUnity::TYPE_UNITY_WITH_RECOVERY]);
        $criteria->params = array_merge([':grade_rules_fk' => $gradeRulesId], $criteria->params);
        $criteria->order  = 'gu.id';

        $gradeUnities = GradeUnity::model()
            ->with('gradeUnityModalities')
            ->findAll($criteria);

        foreach ($gradeUnities as $gradeUnity) {
            $arr              = $gradeUnity->attributes;
            $arr['hasGrades'] = $this->unityHasGrade($gradeUnity);
            $normal           = [];
            $recovery         = [];

            foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
                $modality = $gradeUnityModality->attributes;
                if ($modality['type'] === 'R') {
                    $recovery[] = $modality;
                } else {
                    $normal[] = $modality;
                }
            }
            $arr['modalities']  = array_merge($normal, $recovery);
            $result['unities'][] = $arr;
        }

        $criteria->condition = 'grade_rules_fk = :grade_rules_fk and gu.type = :type';
        $criteria->params    = [':grade_rules_fk' => $gradeRulesId, ':type' => GradeUnity::TYPE_FINAL_RECOVERY];

        $finalRecovery                   = GradeUnity::model()->with('gradeUnityModalities')->find($criteria);
        $result['final_recovery']        = $finalRecovery->attributes;
        $result['final_recovery']['modalities'] = [];
        foreach ($finalRecovery->gradeUnityModalities as $gradeUnityModality) {
            array_push($result['final_recovery']['modalities'], $gradeUnityModality->attributes);
        }

        $gradeRules = GradeRules::model()->findByPk($gradeRulesId);

        $stageIds = Yii::app()->db->createCommand('
            SELECT DISTINCT esvm.id
            FROM
                edcenso_stage_vs_modality esvm
            JOIN
                curricular_matrix cm ON cm.stage_fk = esvm.id
            JOIN
                grade_rules_vs_edcenso_stage_vs_modality grvesvm ON grvesvm.edcenso_stage_vs_modality_fk = esvm.id
            WHERE
                grvesvm.grade_rules_fk = :grade_rule
            ORDER BY
                esvm.name
        ')
            ->bindParam(':grade_rule', $gradeRulesId)
            ->queryColumn();

        $result['edcenso_stage_vs_modality_fk'] = $stageIds;
        $result['approvalMedia']                 = $gradeRules->approvation_media;
        $result['finalRecoverMedia']             = $gradeRules->final_recover_media;
        $result['mediaCalculation']              = $gradeRules->grade_calculation_fk;
        $result['ruleType']                      = $gradeRules->rule_type;
        $result['ruleName']                      = $gradeRules->name;
        $result['hasFinalRecovery']              = (bool) $gradeRules->has_final_recovery;

        $result['partialRecoveries'] = [];
        $gPartialRecoveries = GradePartialRecovery::model()->findAllByAttributes(['grade_rules_fk' => $gradeRules->id]);
        foreach ($gPartialRecoveries as $partialRecovery) {
            $result['partialRecoveries'][] = $this->buildPartialRecoveryResult($partialRecovery);
        }

        echo CJSON::encode($result);
    }

    private function buildPartialRecoveryResult($partialRecovery): array
    {
        $entry = [
            'id'                  => $partialRecovery->id,
            'hasGrades'           => $this->recoveryHasGrade($partialRecovery),
            'name'                => $partialRecovery->name,
            'order'               => $partialRecovery->order_partial_recovery,
            'grade_calculation_fk' => $partialRecovery->grade_calculation_fk,
            'semester'            => $partialRecovery->semester,
            'weights'             => [],
        ];

        if ($partialRecovery->gradeCalculationFk->name == 'Peso') {
            $gradeRecoveryWeights = GradePartialRecoveryWeights::model()->findAllByAttributes(['partial_recovery_fk' => $partialRecovery->id]);
            foreach ($gradeRecoveryWeights as $weight) {
                $entry['weights'][] = [
                    'id'      => $weight['id'],
                    'unity_fk' => $weight['unity_fk'],
                    'weight'  => $weight['weight'],
                    'name'    => $weight['unity_fk'] !== null ? $weight->unityFk->name : 'recuperação',
                ];
            }
        }

        $entry['unities'] = GradeUnity::model()->findAllByAttributes(['parcial_recovery_fk' => $partialRecovery->id]);
        return $entry;
    }

    private function unityHasGrade($unity)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'g';
        $criteria->join  = 'join grade_unity_modality gum on gum.id = g.grade_unity_modality_fk ';
        $criteria->join .= 'join grade_unity gu on gu.id = gum.grade_unity_fk ';
        $criteria->condition = 'gu.id = :unity_id and g.grade IS NOT NULL and g.grade != 0';
        $criteria->params    = [':unity_id' => $unity->id];
        return Grade::model()->count($criteria) > 0;
    }

    private function recoveryHasGrade($recovery)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'g';
        $criteria->join  = 'join grade_partial_recovery gpr on gpr.id = g.grade_partial_recovery_fk';
        $criteria->condition = 'gpr.id = :recovery_id and g.grade IS NOT NULL';
        $criteria->params    = [':recovery_id' => $recovery->id];
        return Grade::model()->count($criteria) > 0;
    }

    // -------------------------------------------------------
    // Save Unities (AJAX) — sets school_year on new records
    // -------------------------------------------------------

    public function actionSaveunities()
    {
        set_time_limit(0);
        ignore_user_abort();
        $gradeRulesId          = Yii::app()->request->getPost('grade_rules_id');
        $gradeRulesName        = Yii::app()->request->getPost('grade_rules_name');
        $reply                 = Yii::app()->request->getPost('reply');
        $stages                = Yii::app()->request->getPost('stage');
        $unities               = Yii::app()->request->getPost('unities');
        $approvalMedia         = Yii::app()->request->getPost('approvalMedia');
        $finalRecoverMedia     = Yii::app()->request->getPost('finalRecoverMedia');
        $calculationFinalMedia = Yii::app()->request->getPost('finalMediaCalculation');
        $finalRecovery         = Yii::app()->request->getPost('finalRecovery');
        $ruleType              = Yii::app()->request->getPost('ruleType');
        $hasFinalRecovery      = Yii::app()->request->getPost('hasFinalRecovery') === 'true';
        $hasPartialRecovery    = Yii::app()->request->getPost('hasPartialRecovery') === 'true';
        $partialRecoveries     = Yii::app()->request->getPost('partialRecoveries');

        try {
            $usecase = new UpdateGradeStructUsecase(
                $gradeRulesId,
                $gradeRulesName,
                $reply,
                $stages,
                $unities,
                $approvalMedia,
                $finalRecoverMedia,
                $calculationFinalMedia,
                $hasFinalRecovery,
                $ruleType,
                $hasPartialRecovery,
                $partialRecoveries
            );
            $gradeRules = $usecase->exec();

            // Ensure school_year is set on the record
            if (empty($gradeRules->school_year)) {
                $gradeRules->school_year = Yii::app()->user->year;
                $gradeRules->save(false);
            }

            $this->handleFinalConcept($gradeRules);

            if ($hasFinalRecovery === true) {
                $this->handleFinalRecovery($gradeRulesId, $finalRecovery, true);
            } elseif ($hasFinalRecovery === false && $finalRecovery['operation'] === 'delete' && $gradeRules->rule_type === 'N') {
                $recoveryUnity = GradeUnity::model()->findByPk((int) $finalRecovery['id']);
                $recoveryUnity?->delete();
                echo json_encode(['valid' => true, 'gradeRules' => $gradeRules->id]);
                Yii::app()->end();
            }

            echo json_encode(['valid' => true, 'gradeRules' => $gradeRules->id]);
        } catch (\Throwable $th) {
            Yii::log($th->getMessage(), CLogger::LEVEL_ERROR);
            Yii::log($th->getTraceAsString(), CLogger::LEVEL_ERROR);
            throw $th;
        }
    }

    private function handleFinalConcept($gradeRules): void
    {
        if ($gradeRules->rule_type === 'C') {
            $existing = GradeUnity::model()->findAllByAttributes(
                ['grade_rules_fk' => $gradeRules->id, 'type' => GradeUnity::TYPE_FINAL_CONCEPT]
            );
            if (!$existing) {
                $unity = new GradeUnity();
                $unity->name               = 'CONCEITO FINAL';
                $unity->type               = GradeUnity::TYPE_FINAL_CONCEPT;
                $unity->grade_calculation_fk = 2;
                $unity->grade_rules_fk     = $gradeRules->id;
                if (!$unity->validate()) {
                    $msg = Yii::app()->utils->stringfyValidationErrors($unity);
                    throw new CHttpException(400, "Não foi possivel salvar dados do conceito final: \n" . $msg, 1);
                }
                $unity->save();

                $modality = new GradeUnityModality();
                $modality->name          = 'AVALIAÇÃO';
                $modality->type          = GradeUnityModality::TYPE_FINAL_CONCEPT;
                $modality->weight        = null;
                $modality->grade_unity_fk = $unity->id;
                if (!$modality->validate()) {
                    throw new CantSaveGradeUnityModalityException($modality);
                }
                $modality->save();
            }
        } elseif ($gradeRules->rule_type === 'N') {
            $concepts = GradeUnity::model()->findAllByAttributes(
                ['grade_rules_fk' => $gradeRules->id, 'type' => GradeUnity::TYPE_FINAL_CONCEPT]
            );
            if ($concepts) {
                foreach ($concepts as $concept) {
                    $modalityModel = GradeUnityModality::model()->findByAttributes(['grade_unity_fk' => $concept->id]);
                    if ($modalityModel != null) {
                        Grade::model()->deleteAllByAttributes(['grade_unity_modality_fk' => $modalityModel->id]);
                        $modalityModel->delete();
                    }
                    $concept->delete();
                }
            }
        }
    }

    private function handleFinalRecovery($gradeRulesId, $finalRecovery, $hasFinalRecovery): void
    {
        $recoveryUnity = GradeUnity::model()->findByPk($finalRecovery['id']);
        if ($recoveryUnity === null) {
            $recoveryUnity = new GradeUnity();
        }
        $recoveryUnity->name                        = $finalRecovery['name'];
        $recoveryUnity->type                        = 'RF';
        $recoveryUnity->grade_calculation_fk        = $finalRecovery['grade_calculation_fk'];
        $recoveryUnity->grade_rules_fk              = $gradeRulesId;
        $recoveryUnity->final_recovery_avarage_formula = $finalRecovery['final_recovery_avarage_formula'];

        $gradeCalculation = GradeCalculation::model()->findByPk($finalRecovery['grade_calculation_fk']);
        if ($gradeCalculation->name === 'Peso') {
            $recoveryUnity->weight_final_media     = $finalRecovery['WeightfinalMedia'];
            $recoveryUnity->weight_final_recovery  = $finalRecovery['WeightfinalRecovery'];
        }
        if (!$recoveryUnity->validate()) {
            $msg = Yii::app()->utils->stringfyValidationErrors($recoveryUnity);
            throw new CHttpException(400, "Não foi possivel salvar dados da recuperação final: \n" . $msg, 1);
        }
        $recoveryUnity->save();

        $modalityModel = GradeUnityModality::model()->findByAttributes(['grade_unity_fk' => $recoveryUnity->id]);
        if ($modalityModel == null) {
            $modalityModel = new GradeUnityModality();
        }
        $modalityModel->name          = 'Avaliação/Prova';
        $modalityModel->type          = 'R';
        $modalityModel->weight        = null;
        $modalityModel->grade_unity_fk = $recoveryUnity->id;
        if (!$modalityModel->validate()) {
            throw new CantSaveGradeUnityModalityException($modalityModel);
        }
        $modalityModel->save();
    }

    // -------------------------------------------------------
    // Copy a grade structure (including units, modalities, recoveries)
    // -------------------------------------------------------

    public function actionCopy($id, $year)
    {
        $effectiveYear = (int) Yii::app()->request->getParam('year', Yii::app()->user->year);

        try {
            $usecase = new CopyGradeStructUsecase($id, $effectiveYear);
            $usecase->exec();
            Yii::app()->user->setFlash('notice', 'Estrutura copiada com sucesso!');
        } catch (CHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'Erro ao copiar estrutura.');
        }

        $this->redirect(['index']);
    }
}

