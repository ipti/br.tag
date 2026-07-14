<?php

class StructureController extends Controller
{
    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'create', 'getunities', 'saveunities', 'copy'],
                'users' => ['@'],
            ],
            [
                'allow',
                'actions' => ['delete'],
                'users' => ['admin'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('GradeRules');
        $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate($id = null)
    {
        $gradeUnity = new GradeUnity();
        $stages = EdcensoStageVsModality::model()->findAll();
        $formulas = GradeCalculation::model()->findAll();

        $this->render('gradesStructure', [
            'gradeUnity' => $gradeUnity,
            'stages' => $stages,
            'formulas' => $formulas,
        ]);
    }

    public function actionDelete($id)
    {
        $this->deleteUnities($id);
        $this->deletePartialRecoveries($id);

        $gradeRules = GradeRules::model()->findByPk($id);
        if ($gradeRules !== null) {
            $gradeRules->delete();
        }

        Yii::app()->user->setFlash('notice', 'Estrutura excluída com sucesso!');
        $this->redirect(['index']);
    }

    public function actionGetunities()
    {
        $stageId = Yii::app()->request->getPost('stageId');
        $gradeRulesId = Yii::app()->request->getPost('gradeRulesId');

        $criteria = new CDbCriteria();
        $criteria->alias = 'gu';
        $criteria->join = 'INNER JOIN grade_rules gr ON gr.id = gu.grade_rules_fk';
        $criteria->join .= ' INNER JOIN grade_rules_vs_edcenso_stage_vs_modality grvesvm ON grvesvm.grade_rules_fk = gr.id';
        $criteria->condition = 'grvesvm.edcenso_stage_vs_modality_fk = :stageId';
        $criteria->params = [':stageId' => $stageId];

        if ($gradeRulesId) {
            $criteria->condition .= ' AND gr.id = :gradeRulesId';
            $criteria->params[':gradeRulesId'] = $gradeRulesId;
        }

        $unities = GradeUnity::model()->findAll($criteria);
        echo CJSON::encode($unities);
    }

    public function actionSaveunities()
    {
        $data = Yii::app()->request->getPost('data');
        $data = json_decode($data, true);

        try {
            (new UpdateGradeStructUsecase($data))->exec();
            echo CJSON::encode(['success' => true]);
        } catch (CantSaveGradeUnityModalityException $e) {
            echo CJSON::encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function actionCopy($id, $year)
    {
        try {
            (new CopyGradeStructUsecase((int)$id, (int)$year))->exec();
            Yii::app()->user->setFlash('notice', 'Estrutura copiada com sucesso!');
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'Não foi possível copiar a estrutura: ' . $e->getMessage());
        }

        $this->redirect(['index']);
    }

    private function deletePartialRecoveries($gradeRulesId)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'gu';
        $criteria->join = 'INNER JOIN grade_rules gr ON gr.id = gu.grade_rules_fk';
        $criteria->condition = 'gr.id = :gradeRulesId AND (gu.type = :recovery OR gu.type = :finalRecovery)';
        $criteria->params = [
            ':gradeRulesId' => $gradeRulesId,
            ':recovery' => GradeUnity::TYPE_PARTIAL_RECOVERY,
            ':finalRecovery' => GradeUnity::TYPE_FINAL_RECOVERY,
        ];
        $recoveries = GradeUnity::model()->findAll($criteria);

        foreach ($recoveries as $recovery) {
            if (!$this->recoveryHasGrade($recovery->id)) {
                $recovery->delete();
            }
        }
    }

    private function deleteUnities($gradeRulesId)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'gu';
        $criteria->join = 'INNER JOIN grade_rules gr ON gr.id = gu.grade_rules_fk';
        $criteria->condition = 'gr.id = :gradeRulesId AND (gu.type = :unity OR gu.type = :unityWithRecovery OR gu.type = :unityConcept)';
        $criteria->params = [
            ':gradeRulesId' => $gradeRulesId,
            ':unity' => GradeUnity::TYPE_UNITY,
            ':unityWithRecovery' => GradeUnity::TYPE_UNITY_WITH_RECOVERY,
            ':unityConcept' => GradeUnity::TYPE_UNITY_BY_CONCEPT,
        ];
        $unities = GradeUnity::model()->findAll($criteria);

        foreach ($unities as $unity) {
            if (!$this->unityHasGrade($unity->id)) {
                foreach ($unity->gradeUnityModalities as $modality) {
                    $modality->delete();
                }
                $unity->delete();
            }
        }
    }

    private function handleFinalConcept($gradeRules, $data)
    {
        if (isset($data['finalConcept']) && $data['finalConcept']) {
            $gradeRules->has_final_concept = 1;
        } else {
            $gradeRules->has_final_concept = 0;
        }
    }

    private function handleFinalRecovery($gradeRules, $data)
    {
        if (isset($data['hasRecovery']) && $data['hasRecovery']) {
            $gradeRules->has_final_recovery = 1;
        } else {
            $gradeRules->has_final_recovery = 0;
        }
    }

    private function unityHasGrade($unityId)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'g';
        $criteria->join = 'INNER JOIN grade_unity_modality gum ON gum.id = g.grade_unity_modality_fk';
        $criteria->join .= ' INNER JOIN grade_unity gu ON gu.id = gum.grade_unity_fk';
        $criteria->condition = 'gu.id = :unityId';
        $criteria->params = [':unityId' => $unityId];

        return Grade::model()->count($criteria) > 0;
    }

    private function recoveryHasGrade($unityId)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = 'g';
        $criteria->join = 'INNER JOIN grade_unity_modality gum ON gum.id = g.grade_unity_modality_fk';
        $criteria->join .= ' INNER JOIN grade_unity gu ON gu.id = gum.grade_unity_fk';
        $criteria->condition = 'gu.id = :unityId';
        $criteria->params = [':unityId' => $unityId];

        return Grade::model()->count($criteria) > 0;
    }

    private function buildPartialRecoveryResult($partialRecovery)
    {
        return [
            'id' => $partialRecovery->id,
            'name' => $partialRecovery->name,
            'type' => $partialRecovery->type,
        ];
    }
}
