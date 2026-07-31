<?php

class LessonsplanController extends Controller
{
    private ?MaceteLessonPlanService $lessonPlanService = null;
    private ?MaceteAbilityService $abilityService = null;
    private ?MaceteAccessService $accessService = null;
    private ?MaceteAiAssistantGateway $assistantGateway = null;
    private ?MaceteAiPlanContextResolver $assistantContextResolver = null;

    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete, startAssistantConversation, sendAssistantMessage',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'index',
                    'create',
                    'update',
                    'delete',
                    'getDisciplines',
                    'getPlan',
                    'startAssistantConversation',
                    'sendAssistantMessage',
                ],
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
        $this->accessService()->requireLessonPlanFeature();
        $criteria = new CDbCriteria();
        $this->accessService()->applyPlanScope($criteria);
        $criteria->order = 'updated_at DESC';

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
            $criteria->addCondition('EXISTS (
                SELECT 1
                FROM macete_lesson_plan_stage mlps
                WHERE mlps.lesson_plan_fk = t.id
                    AND mlps.edcenso_discipline_fk = :discipline
            )');
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
        $this->accessService()->requireLessonPlanFeature();
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
        $this->accessService()->requireLessonPlanFeature();
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
        $this->accessService()->requireLessonPlanFeature();
        $lessonPlan = $this->loadModel($id);
        $lessonPlan->delete();

        Yii::app()->user->setFlash('success', 'Plano MACETE excluído com sucesso!');
        $this->redirect(['index']);
    }

    public function actionGetDisciplines()
    {
        $this->accessService()->requireLessonPlanFeature();
        $stageIds = Yii::app()->request->getPost('stage');
        $stageIds = $stageIds !== null ? $this->lessonPlanService()->normalizeStageIds($stageIds) : [];

        echo CJSON::encode($this->lessonPlanService()->getDisciplines($stageIds));
        Yii::app()->end();
    }

    public function actionGetPlan($id)
    {
        $this->accessService()->requireLessonPlanFeature();
        $lessonPlan = $this->loadModel($id);

        echo CJSON::encode([
            'id' => (int) $lessonPlan->id,
            'name' => $lessonPlan->name,
            'theme' => $lessonPlan->theme,
            'stage' => $lessonPlan->getStageComponentLabels(),
            'discipline' => $lessonPlan->getDisciplineNames(),
            'classroom' => $lessonPlan->classroomFk !== null ? $lessonPlan->classroomFk->name : '',
            'abilities' => $lessonPlan->getAbilityCodes(),
        ]);
        Yii::app()->end();
    }

    public function actionStartAssistantConversation()
    {
        $this->accessService()->requireLessonPlanFeature();
        if (!$this->validateAssistantCsrf()) {
            return;
        }
        $lessonPlan = $this->findAssistantPlan();
        if ($lessonPlan === null) {
            return;
        }

        if (!$this->isAssistantConfigured()) {
            $this->renderAssistantJson(['code' => 'assistant_not_configured'], 503);
            return;
        }

        try {
            $response = $this->assistantGateway()->startConversation(
                $this->assistantContextResolver()->createConversationPayload($lessonPlan)
            );
            $this->renderAssistantJson($response, 201);
        } catch (MaceteAiAssistantGatewayException $exception) {
            TLog::error('Não foi possível iniciar a conversa do assistente MACETE.', $exception->errorCode);
            $this->renderAssistantJson(['code' => $exception->errorCode], $this->assistantStatusCode($exception));
        }
    }

    public function actionSendAssistantMessage()
    {
        $this->accessService()->requireLessonPlanFeature();
        if (!$this->validateAssistantCsrf()) {
            return;
        }
        $lessonPlan = $this->findAssistantPlan();
        if ($lessonPlan === null) {
            return;
        }
        $conversationId = trim((string) Yii::app()->request->getPost('conversation_id'));
        $message = trim((string) Yii::app()->request->getPost('message'));

        if ($conversationId === '' || $message === '') {
            $this->renderAssistantJson(['code' => 'assistant_invalid_request'], 422);
            return;
        }
        if (!$this->isAssistantConfigured()) {
            $this->renderAssistantJson(['code' => 'assistant_not_configured'], 503);
            return;
        }

        try {
            $response = $this->assistantGateway()->sendMessage(
                $conversationId,
                $this->assistantContextResolver()->sendMessagePayload($lessonPlan, $message)
            );
            $this->renderAssistantJson($this->validateAssistantReply($response));
        } catch (MaceteAiAssistantGatewayException $exception) {
            TLog::error('Não foi possível enviar a mensagem ao assistente MACETE.', $exception->errorCode);
            $this->renderAssistantJson(['code' => $exception->errorCode], $this->assistantStatusCode($exception));
        }
    }

    public function loadModel($id): MaceteLessonPlan
    {
        $model = $this->accessService()->findPlan((int) $id);
        if ($model === null) {
            throw new CHttpException(404, 'Plano MACETE não encontrado.');
        }

        return $model;
    }

    private function buildFormData(MaceteLessonPlan $lessonPlan): array
    {
        $abilityIds = $this->lessonPlanService()->getAbilityIds($lessonPlan);
        $postedStageComponents = Yii::app()->request->getPost('stage_components');
        $stageComponents = $postedStageComponents !== null
            ? $this->lessonPlanService()->normalizeStageComponents($postedStageComponents)
            : $this->lessonPlanService()->getStageComponents($lessonPlan);
        $selectedStageIds = array_map(static fn (array $component): int => $component['stage_id'], $stageComponents);
        $stageComponentDisciplines = [];
        foreach ($stageComponents as $index => $component) {
            $stageComponentDisciplines[$index] = $this->lessonPlanService()->getDisciplines([$component['stage_id']]);
        }

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $loginInfos = Yii::app()->user->loginInfos;

        return [
            'lessonPlan' => $lessonPlan,
            'stages' => $this->lessonPlanService()->getStages(),
            'stageComponents' => $stageComponents,
            'stageComponentDisciplines' => $stageComponentDisciplines,
            'selectedStageIds' => $selectedStageIds,
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

    private function accessService(): MaceteAccessService
    {
        if ($this->accessService === null) {
            $this->accessService = new MaceteAccessService();
        }

        return $this->accessService;
    }

    private function assistantGateway(): MaceteAiAssistantGateway
    {
        if ($this->assistantGateway === null) {
            $this->assistantGateway = new MaceteAiAssistantGateway();
        }

        return $this->assistantGateway;
    }

    private function assistantContextResolver(): MaceteAiPlanContextResolver
    {
        if ($this->assistantContextResolver === null) {
            $this->assistantContextResolver = new MaceteAiPlanContextResolver();
        }

        return $this->assistantContextResolver;
    }

    private function isAssistantConfigured(): bool
    {
        return $this->assistantGateway()->isConfigured() && $this->assistantContextResolver()->isConfigured();
    }

    private function findAssistantPlan(): ?MaceteLessonPlan
    {
        $id = (int) Yii::app()->request->getPost('lesson_plan_id');
        if ($id <= 0) {
            $this->renderAssistantJson(['code' => 'assistant_invalid_request'], 422);
            return null;
        }

        $lessonPlan = $this->accessService()->findPlan($id);
        if ($lessonPlan === null) {
            $this->renderAssistantJson(['code' => 'assistant_plan_not_found'], 404);
            return null;
        }

        return $lessonPlan;
    }

    private function validateAssistantReply(array $response): array
    {
        foreach (['message', 'proposals', 'sources', 'warnings'] as $requiredField) {
            if (!array_key_exists($requiredField, $response)) {
                throw new MaceteAiAssistantGatewayException('assistant_invalid_response');
            }
        }
        if (!is_string($response['message']) || !is_array($response['proposals']) || !is_array($response['sources']) || !is_array($response['warnings'])) {
            throw new MaceteAiAssistantGatewayException('assistant_invalid_response');
        }

        return $response;
    }

    private function assistantStatusCode(MaceteAiAssistantGatewayException $exception): int
    {
        return $exception->statusCode > 0 ? $exception->statusCode : 503;
    }

    private function renderAssistantJson(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo CJSON::encode($payload);
        Yii::app()->end();
    }

    private function validateAssistantCsrf(): bool
    {
        try {
            Yii::app()->request->validateCsrfToken(new CEvent($this));
        } catch (CHttpException) {
            $this->renderAssistantJson(['code' => 'assistant_invalid_csrf'], 400);
            return false;
        }

        return true;
    }
}
