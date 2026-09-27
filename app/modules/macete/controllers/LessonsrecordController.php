<?php

class LessonsrecordController extends Controller
{
    private ?MaceteLessonRecordService $lessonRecordService = null;
    private ?MaceteLessonPlanService $lessonPlanService = null;
    private ?MaceteInstructionalDaysService $instructionalDaysService = null;
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
                'actions' => ['index', 'create', 'update', 'delete', 'getMonths', 'getDays'],
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
        $this->accessService()->requireLessonRecordFeature();

        $this->render('index', [
            'classrooms' => $this->lessonPlanService()->getClassrooms(),
        ]);
    }

    public function actionGetMonths()
    {
        $this->accessService()->requireLessonRecordFeature();
        $classroom = $this->accessService()->findClassroom((int) Yii::app()->request->getPost('classroom'));

        if ($classroom === null) {
            echo CJSON::encode(['valid' => false, 'error' => 'Turma não encontrada.']);
            Yii::app()->end();
        }

        $months = $this->instructionalDaysService()->getAvailableMonths($classroom);
        if (empty($months)) {
            echo CJSON::encode(['valid' => false, 'error' => 'A turma está sem Calendário Escolar vinculado.']);
            Yii::app()->end();
        }

        echo CJSON::encode(['valid' => true, 'months' => $months]);
        Yii::app()->end();
    }

    public function actionGetDays()
    {
        $this->accessService()->requireLessonRecordFeature();
        $classroomId = (int) Yii::app()->request->getPost('classroom');
        $month = (int) Yii::app()->request->getPost('month');
        $year = (int) Yii::app()->request->getPost('year');

        $classroom = $this->accessService()->findClassroom($classroomId);
        if ($classroom === null || $month < 1 || $month > 12 || $year < 2000) {
            echo CJSON::encode(['valid' => false, 'error' => 'Selecione turma e mês válidos.']);
            Yii::app()->end();
        }

        $instructionalDays = $this->instructionalDaysService()->getInstructionalDays($classroom, $month, $year);
        if (empty($instructionalDays)) {
            echo CJSON::encode(['valid' => false, 'error' => 'Nenhum dia letivo encontrado no Calendário Escolar da turma para esse mês.']);
            Yii::app()->end();
        }

        $monthRecords = $this->lessonRecordService()->getRecordsByMonth($classroomId, null, $month, $year);
        $recordsByDay = [];
        foreach ($monthRecords as $record) {
            $day = (int) date('j', strtotime((string) $record->lesson_date));
            $recordsByDay[$day][] = [
                'id' => (int) $record->id,
                'plan' => $record->lessonPlanFk !== null ? $record->lessonPlanFk->name : '',
            ];
        }

        $weekDayNames = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
        $days = [];
        foreach ($instructionalDays as $day) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $days[] = [
                'day' => $day,
                'date' => $date,
                'weekDay' => $weekDayNames[(int) date('w', strtotime($date))],
                'registered' => isset($recordsByDay[$day]),
                'records' => $recordsByDay[$day] ?? [],
            ];
        }

        echo CJSON::encode([
            'valid' => true,
            'classroom_fk' => $classroomId,
            'classroom_name' => $classroom->name,
            'days' => $days,
        ]);
        Yii::app()->end();
    }

    public function actionCreate()
    {
        $this->accessService()->requireLessonRecordFeature();
        $lessonRecord = new MaceteLessonRecord();
        $lessonRecord->lesson_date = date('d/m/Y');
        $existingDayRecords = [];

        $lessonPlanId = Yii::app()->request->getQuery('lessonPlanId');
        if ($lessonPlanId !== null && $lessonPlanId !== '') {
            $lessonPlan = $this->accessService()->findPlan((int) $lessonPlanId);
            if ($lessonPlan !== null) {
                $lessonRecord->lesson_plan_fk = $lessonPlan->id;
                $lessonRecord->classroom_fk = $lessonPlan->classroom_fk;
                $lessonRecord->edcenso_stage_vs_modality_fk = $lessonPlan->edcenso_stage_vs_modality_fk;
                $lessonRecord->edcenso_discipline_fk = $lessonPlan->edcenso_discipline_fk;
            }
        }

        $classroomId = Yii::app()->request->getQuery('classroomId');
        $date = Yii::app()->request->getQuery('date');
        $lessonDateLocked = $date !== null && $date !== '';
        if ($classroomId !== null && $classroomId !== '') {
            $lessonRecord->classroom_fk = (int) $classroomId;
        }
        if ($lessonDateLocked) {
            $lessonRecord->lesson_date = MaceteLessonRecordService::convertDateToView($date);
        }
        if ($lessonRecord->classroom_fk && $lessonRecord->lesson_date) {
            $existingDayRecords = $this->lessonRecordService()->getRecordsForDay(
                (int) $lessonRecord->classroom_fk,
                MaceteLessonRecordService::convertDateToDatabase($lessonRecord->lesson_date)
            );
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
                // O service já converte lesson_date para o formato do banco
                // antes de validar; se a validação falhar, o campo precisa
                // voltar pro formato de exibição (DD/MM/AAAA) antes de
                // re-renderizar o formulário, senão a máscara de data bugra.
                $lessonRecord->lesson_date = MaceteLessonRecordService::convertDateToView($lessonRecord->lesson_date);
            }
        }

        $this->render('create', $this->buildFormData($lessonRecord, $existingDayRecords, $lessonDateLocked));
    }

    public function actionUpdate($id)
    {
        $this->accessService()->requireLessonRecordFeature();
        $lessonRecord = $this->loadModel($id);
        $existingDayRecords = $this->lessonRecordService()->getRecordsForDay(
            (int) $lessonRecord->classroom_fk,
            (string) $lessonRecord->lesson_date
        );
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
                // Mesmo motivo do actionCreate: o service converte lesson_date
                // para o formato do banco antes de validar.
                $lessonRecord->lesson_date = MaceteLessonRecordService::convertDateToView($lessonRecord->lesson_date);
            }
        }

        $this->render('update', $this->buildFormData($lessonRecord, $existingDayRecords, true));
    }

    public function actionDelete($id)
    {
        $this->accessService()->requireLessonRecordFeature();
        $lessonRecord = $this->loadModel($id);
        $lessonRecord->delete();

        Yii::app()->user->setFlash('success', 'Registro de aula MACETE excluído com sucesso!');
        $this->redirect(['index']);
    }

    public function loadModel($id): MaceteLessonRecord
    {
        $model = $this->accessService()->findRecord((int) $id);
        if ($model === null) {
            throw new CHttpException(404, 'Registro de aula MACETE não encontrado.');
        }

        return $model;
    }

    private function buildFormData(MaceteLessonRecord $lessonRecord, array $existingDayRecords = [], bool $lessonDateLocked = false): array
    {
        $abilityIds = $this->lessonRecordService()->getAbilityIds($lessonRecord);
        if (empty($abilityIds) && $lessonRecord->lessonPlanFk !== null) {
            foreach ($lessonRecord->lessonPlanFk->abilities as $ability) {
                $abilityIds[] = $ability->ability_fk;
            }
        }

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        return [
            'lessonRecord' => $lessonRecord,
            'plans' => $this->lessonRecordService()->getPlans(),
            'classrooms' => $this->lessonPlanService()->getClassrooms(),
            'selectedAbilities' => $this->abilityService()->getByIds($abilityIds),
            'territoryContext' => $school !== null ? (string) $school->territory_context : '',
            'existingDayRecords' => array_filter(
                $existingDayRecords,
                static fn (MaceteLessonRecord $record): bool => $record->id !== $lessonRecord->id
            ),
            'lessonDateLocked' => $lessonDateLocked,
        ];
    }

    private function instructionalDaysService(): MaceteInstructionalDaysService
    {
        if ($this->instructionalDaysService === null) {
            $this->instructionalDaysService = new MaceteInstructionalDaysService();
        }

        return $this->instructionalDaysService;
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

    private function accessService(): MaceteAccessService
    {
        if ($this->accessService === null) {
            $this->accessService = new MaceteAccessService();
        }

        return $this->accessService;
    }
}
