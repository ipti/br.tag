<?php

class TemplatesController extends Controller
{
    private ?MaceteLessonPlanTemplateService $templateService = null;
    private ?MaceteLessonPlanService $lessonPlanService = null;
    private ?MaceteAbilityService $abilityService = null;
    private ?MaceteAccessService $accessService = null;

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
                'actions' => ['index', 'create', 'update', 'delete', 'getDisciplines'],
                'users' => ['@'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    // Templates são cadastrados pelo administrador e valem para a rede
    // inteira (sem dono/escola para restringir o dado), então, diferente
    // do resto do módulo MACETE (que só esconde os links no menu), aqui a
    // checagem de papel é feita de verdade. Não dá pra usar o 'roles' do
    // CAccessControlFilter nativo do Yii: ele resolve o usuário via
    // Yii::app()->user->getId(), que aqui devolve o username (string),
    // enquanto auth_assignment guarda o id numérico de users — nunca bate,
    // então TODO admin cairia em 403. TagUtils::checkAccess() usa o id
    // certo (loginInfos->id), então a checagem é feita manualmente aqui.
    public function beforeAction($action)
    {
        if (!TagUtils::checkAccess(TRole::ADMIN)) {
            throw new CHttpException(403, 'Você não tem permissão para acessar esta funcionalidade.');
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->accessService()->requireLessonPlanFeature();
        $criteria = new CDbCriteria();
        $criteria->order = 'updated_at DESC';

        $dataProvider = new CActiveDataProvider('MaceteLessonPlanTemplate', [
            'criteria' => $criteria,
            'pagination' => false,
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    // A criação de um modelo agora acontece na própria tela de "Novo plano"
    // (macete/lessonsplan/create), marcando o checkbox "é um plano modelo"
    // — mantido aqui só para não quebrar links antigos.
    public function actionCreate()
    {
        $this->redirect(MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_CREATE));
    }

    public function actionUpdate($id)
    {
        $this->accessService()->requireLessonPlanFeature();
        $template = $this->loadModel($id);

        if (isset($_POST['MaceteLessonPlanTemplate'])) {
            try {
                $template = $this->templateService()->save($template, $_POST);
                TLog::info('Modelo de plano MACETE atualizado com sucesso.', ['MaceteLessonPlanTemplate' => $template->id]);
                Yii::app()->user->setFlash('success', 'Modelo de plano MACETE atualizado com sucesso!');
                $this->redirect(['update', 'id' => $template->id]);
            } catch (Exception $exception) {
                TLog::error('Erro ao atualizar modelo de plano MACETE.', $exception->getMessage());
                Yii::app()->user->setFlash('error', $exception->getMessage());
            }
        }

        $this->render('update', $this->buildFormData($template));
    }

    public function actionDelete($id)
    {
        $this->accessService()->requireLessonPlanFeature();
        $template = $this->loadModel($id);
        $template->delete();

        Yii::app()->user->setFlash('success', 'Modelo de plano MACETE excluído com sucesso!');
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

    public function loadModel($id): MaceteLessonPlanTemplate
    {
        $model = $this->templateService()->loadModel((int) $id);
        if ($model === null) {
            throw new CHttpException(404, 'Modelo de plano MACETE não encontrado.');
        }

        return $model;
    }

    private function buildFormData(MaceteLessonPlanTemplate $template): array
    {
        $abilityIds = $this->templateService()->getAbilityIds($template);
        $postedStageComponents = Yii::app()->request->getPost('stage_components');
        $stageComponents = $postedStageComponents !== null
            ? $this->lessonPlanService()->normalizeStageComponents($postedStageComponents)
            : $this->templateService()->getStageComponents($template);
        $selectedStageIds = array_map(static fn (array $component): int => $component['stage_id'], $stageComponents);
        $stageComponentDisciplines = [];
        foreach ($stageComponents as $index => $component) {
            $stageComponentDisciplines[$index] = $this->lessonPlanService()->getDisciplines([$component['stage_id']]);
        }

        return [
            'template' => $template,
            'stages' => $this->lessonPlanService()->getStages(),
            'stageComponents' => $stageComponents,
            'stageComponentDisciplines' => $stageComponentDisciplines,
            'selectedStageIds' => $selectedStageIds,
            'sectionValues' => $this->templateService()->getSectionValues($template),
            'resourceValues' => $this->templateService()->getResourceValues($template),
            'materialValues' => $this->templateService()->getMaterialValues($template),
            'selectedAbilities' => $this->abilityService()->getByIds($abilityIds),
        ];
    }

    private function templateService(): MaceteLessonPlanTemplateService
    {
        if ($this->templateService === null) {
            $this->templateService = new MaceteLessonPlanTemplateService();
        }

        return $this->templateService;
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
}
