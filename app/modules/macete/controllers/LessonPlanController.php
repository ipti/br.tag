<?php

class LessonPlanController extends Controller
{
    private ?MaceteLessonPlanService $lessonPlanService = null;
    private ?MaceteAbilityService $abilityService = null;

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
                'actions' => ['index', 'create', 'update', 'delete', 'getDisciplines', 'getPlan'],
                'users' => ['@'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'school_inep_fk = :school AND school_year = :school_year';
        $criteria->params = [
            ':school' => Yii::app()->user->school,
            ':school_year' => Yii::app()->user->year,
        ];
        $criteria->order = 'updated_at DESC';

        if (TagUtils::isInstructor()) {
            $criteria->addCondition('users_fk = :user_id');
            $criteria->params[':user_id'] = Yii::app()->user->loginInfos->id;
        }

        $stage = Yii::app()->request->getQuery('stage');
        if ($stage !== null && $stage !== '') {
            $criteria->addCondition('EXISTS (
                SELECT 1
                FROM macete_lesson_plan_stage mlps
                WHERE mlps.lesson_plan_fk = t.id
                    AND mlps.edcenso_stage_vs_modality_fk = :stage
            )');
            $criteria->params[':stage'] = (int) $stage;
        }

        $discipline = Yii::app()->request->getQuery('discipline');
        if ($discipline !== null && $discipline !== '') {
            $criteria->addCondition('edcenso_discipline_fk = :discipline');
            $criteria->params[':discipline'] = (int) $discipline;
        }

        $status = Yii::app()->request->getQuery('status');
        if ($status !== null && $status !== '') {
            $criteria->addCondition('status = :status');
            $criteria->params[':status'] = $status;
        }

        $dataProvider = new CActiveDataProvider('MaceteLessonPlan', [
            'criteria' => $criteria,
            'pagination' => false,
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'stages' => $this->lessonPlanService()->getStages(),
            'disciplines' => $this->lessonPlanService()->getDisciplines(),
            'filters' => [
                'stage' => $stage,
                'discipline' => $discipline,
                'status' => $status,
            ],
        ]);
    }

    public function actionCreate()
    {
        $lessonPlan = new MaceteLessonPlan();
        $lessonPlan->status = MaceteLessonPlan::STATUS_DRAFT;

        if (isset($_POST['MaceteLessonPlan'])) {
            try {
                $lessonPlan = $this->lessonPlanService()->save($lessonPlan, $_POST);
                TLog::info('Plano MACETE salvo com sucesso.', ['MaceteLessonPlan' => $lessonPlan->id]);
                Yii::app()->user->setFlash('success', 'Plano MACETE salvo com sucesso!');
                $this->redirect(['update', 'id' => $lessonPlan->id]);
            } catch (Exception $exception) {
                TLog::error('Erro ao salvar plano MACETE.', $exception->getMessage());
                Yii::app()->user->setFlash('error', $exception->getMessage());
            }
        }

        $this->render('create', $this->buildFormData($lessonPlan));
    }

    public function actionUpdate($id)
    {
        $lessonPlan = $this->loadModel($id);

        if (isset($_POST['MaceteLessonPlan'])) {
            try {
                $lessonPlan = $this->lessonPlanService()->save($lessonPlan, $_POST);
                TLog::info('Plano MACETE atualizado com sucesso.', ['MaceteLessonPlan' => $lessonPlan->id]);
                Yii::app()->user->setFlash('success', 'Plano MACETE atualizado com sucesso!');
                $this->redirect(['update', 'id' => $lessonPlan->id]);
            } catch (Exception $exception) {
                TLog::error('Erro ao atualizar plano MACETE.', $exception->getMessage());
                Yii::app()->user->setFlash('error', $exception->getMessage());
            }
        }

        $this->render('update', $this->buildFormData($lessonPlan));
    }

    public function actionDelete($id)
    {
        $lessonPlan = $this->loadModel($id);
        $lessonPlan->delete();

        Yii::app()->user->setFlash('success', 'Plano MACETE excluído com sucesso!');
        $this->redirect(['index']);
    }

    public function actionGetDisciplines()
    {
        $stageIds = Yii::app()->request->getPost('stage');
        $stageIds = $stageIds !== null ? $this->lessonPlanService()->normalizeStageIds($stageIds) : [];

        echo CJSON::encode($this->lessonPlanService()->getDisciplines($stageIds));
        Yii::app()->end();
    }

    public function actionGetPlan($id)
    {
        $lessonPlan = $this->loadModel($id);

        echo CJSON::encode([
            'id' => (int) $lessonPlan->id,
            'name' => $lessonPlan->name,
            'theme' => $lessonPlan->theme,
            'stage' => $lessonPlan->getStageNames(),
            'discipline' => $lessonPlan->disciplineFk !== null ? $lessonPlan->disciplineFk->name : '',
            'classroom' => $lessonPlan->classroomFk !== null ? $lessonPlan->classroomFk->name : '',
            'abilities' => $lessonPlan->getAbilityCodes(),
        ]);
        Yii::app()->end();
    }

    public function loadModel($id): MaceteLessonPlan
    {
        $model = MaceteLessonPlan::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'Plano MACETE não encontrado.');
        }

        return $model;
    }

    private function buildFormData(MaceteLessonPlan $lessonPlan): array
    {
        $abilityIds = $this->lessonPlanService()->getAbilityIds($lessonPlan);
        $postedStageIds = Yii::app()->request->getPost('stage_ids');
        $selectedStageIds = $postedStageIds !== null
            ? $this->lessonPlanService()->normalizeStageIds($postedStageIds)
            : $this->lessonPlanService()->getStageIds($lessonPlan);

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $loginInfos = Yii::app()->user->loginInfos;

        return [
            'lessonPlan' => $lessonPlan,
            'stages' => $this->lessonPlanService()->getStages(),
            'selectedStageIds' => $selectedStageIds,
            'selectedStages' => $this->lessonPlanService()->getStagesByIds($selectedStageIds),
            'disciplines' => $this->lessonPlanService()->getDisciplines($selectedStageIds),
            'sectionValues' => $this->lessonPlanService()->getSectionValues($lessonPlan),
            'resourceValues' => $this->lessonPlanService()->getResourceValues($lessonPlan),
            'materialValues' => $this->lessonPlanService()->getMaterialValues($lessonPlan),
            'selectedAbilities' => $this->abilityService()->getByIds($abilityIds),
            'schoolName' => $school !== null ? (string) $school->name : '',
            'professorName' => $loginInfos !== null ? (string) $loginInfos->name : '',
        ];
    }

    private function lessonPlanService(): MaceteLessonPlanService
    {
        if ($this->lessonPlanService === null) {
            $this->lessonPlanService = new MaceteLessonPlanService();
        }

        return $this->lessonPlanService;
    }

    private function abilityService(): MaceteAbilityService
    {
        if ($this->abilityService === null) {
            $this->abilityService = new MaceteAbilityService();
        }

        return $this->abilityService;
    }
}
