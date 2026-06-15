<?php

class LessonRecordController extends Controller
{
    private ?MaceteLessonRecordService $lessonRecordService = null;
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
                'actions' => ['index', 'create', 'update', 'delete'],
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
        $criteria->condition = 'school_inep_fk = :school AND YEAR(lesson_date) = :school_year';
        $criteria->params = [
            ':school' => Yii::app()->user->school,
            ':school_year' => Yii::app()->user->year,
        ];
        $criteria->order = 'lesson_date DESC, updated_at DESC';

        if (TagUtils::isInstructor()) {
            $criteria->addCondition('users_fk = :user_id');
            $criteria->params[':user_id'] = Yii::app()->user->loginInfos->id;
        }

        $dataProvider = new CActiveDataProvider('MaceteLessonRecord', [
            'criteria' => $criteria,
            'pagination' => false,
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $lessonRecord = new MaceteLessonRecord();
        $lessonRecord->status = MaceteLessonRecord::STATUS_DRAFT;
        $lessonRecord->lesson_date = date('d/m/Y');

        $lessonPlanId = Yii::app()->request->getQuery('lessonPlanId');
        if ($lessonPlanId !== null && $lessonPlanId !== '') {
            $lessonPlan = MaceteLessonPlan::model()->findByPk($lessonPlanId);
            if ($lessonPlan !== null) {
                $lessonRecord->lesson_plan_fk = $lessonPlan->id;
                $lessonRecord->classroom_fk = $lessonPlan->classroom_fk;
                $lessonRecord->edcenso_stage_vs_modality_fk = $lessonPlan->edcenso_stage_vs_modality_fk;
                $lessonRecord->edcenso_discipline_fk = $lessonPlan->edcenso_discipline_fk;
            }
        }

        if (isset($_POST['MaceteLessonRecord'])) {
            try {
                $lessonRecord = $this->lessonRecordService()->save($lessonRecord, $_POST);
                TLog::info('Registro de aula MACETE salvo com sucesso.', ['MaceteLessonRecord' => $lessonRecord->id]);
                Yii::app()->user->setFlash('success', 'Registro de aula MACETE salvo com sucesso!');
                $this->redirect(['update', 'id' => $lessonRecord->id]);
            } catch (Exception $exception) {
                TLog::error('Erro ao salvar registro de aula MACETE.', $exception->getMessage());
                Yii::app()->user->setFlash('error', $exception->getMessage());
            }
        }

        $this->render('create', $this->buildFormData($lessonRecord));
    }

    public function actionUpdate($id)
    {
        $lessonRecord = $this->loadModel($id);
        $lessonRecord->lesson_date = MaceteLessonRecordService::convertDateToView($lessonRecord->lesson_date);

        if (isset($_POST['MaceteLessonRecord'])) {
            try {
                $lessonRecord = $this->lessonRecordService()->save($lessonRecord, $_POST);
                TLog::info('Registro de aula MACETE atualizado com sucesso.', ['MaceteLessonRecord' => $lessonRecord->id]);
                Yii::app()->user->setFlash('success', 'Registro de aula MACETE atualizado com sucesso!');
                $this->redirect(['update', 'id' => $lessonRecord->id]);
            } catch (Exception $exception) {
                TLog::error('Erro ao atualizar registro de aula MACETE.', $exception->getMessage());
                Yii::app()->user->setFlash('error', $exception->getMessage());
            }
        }

        $this->render('update', $this->buildFormData($lessonRecord));
    }

    public function actionDelete($id)
    {
        $lessonRecord = $this->loadModel($id);
        $lessonRecord->delete();

        Yii::app()->user->setFlash('success', 'Registro de aula MACETE excluído com sucesso!');
        $this->redirect(['index']);
    }

    public function loadModel($id): MaceteLessonRecord
    {
        $model = MaceteLessonRecord::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'Registro de aula MACETE não encontrado.');
        }

        return $model;
    }

    private function buildFormData(MaceteLessonRecord $lessonRecord): array
    {
        $abilityIds = $this->lessonRecordService()->getAbilityIds($lessonRecord);
        if (empty($abilityIds) && $lessonRecord->lessonPlanFk !== null) {
            foreach ($lessonRecord->lessonPlanFk->abilities as $ability) {
                $abilityIds[] = $ability->ability_fk;
            }
        }

        return [
            'lessonRecord' => $lessonRecord,
            'plans' => $this->lessonRecordService()->getPlans(),
            'classrooms' => $this->lessonPlanService()->getClassrooms(),
            'selectedAbilities' => $this->abilityService()->getByIds($abilityIds),
        ];
    }

    private function lessonRecordService(): MaceteLessonRecordService
    {
        if ($this->lessonRecordService === null) {
            $this->lessonRecordService = new MaceteLessonRecordService();
        }

        return $this->lessonRecordService;
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
