<?php

class DiaryController extends Controller
{
    private ?MaceteLessonPlanService $lessonPlanService = null;
    private ?MaceteLessonRecordService $lessonRecordService = null;
    private ?MaceteInstructionalDaysService $instructionalDaysService = null;
    private ?MaceteAccessService $accessService = null;

    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'getDisciplines', 'getSummary'],
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

    public function actionGetDisciplines()
    {
        $this->accessService()->requireLessonRecordFeature();
        $classroom = $this->accessService()->findClassroom((int) Yii::app()->request->getPost('classroom'));

        $disciplines = $classroom !== null
            ? $this->lessonPlanService()->getDisciplines([(int) $classroom->edcenso_stage_vs_modality_fk])
            : [];

        echo CJSON::encode($disciplines);
        Yii::app()->end();
    }

    public function actionGetSummary()
    {
        $this->accessService()->requireLessonRecordFeature();

        $classroomId = (int) Yii::app()->request->getPost('classroom');
        $disciplineId = Yii::app()->request->getPost('discipline');
        $disciplineId = ($disciplineId !== null && $disciplineId !== '') ? (int) $disciplineId : null;
        $month = (int) Yii::app()->request->getPost('month');
        $year = (int) Yii::app()->request->getPost('year');

        $classroom = $this->accessService()->findClassroom($classroomId);
        if ($classroom === null || $month < 1 || $month > 12 || $year < 2000) {
            echo CJSON::encode(['valid' => false, 'error' => 'Selecione turma, mês e ano válidos.']);
            Yii::app()->end();
        }

        $instructionalDays = $this->instructionalDaysService()->getInstructionalDays($classroom, $month, $year);
        $records = $this->lessonRecordService()->getRecordsByMonth($classroomId, $disciplineId, $month, $year);

        $recordsByDay = [];
        foreach ($records as $record) {
            $day = (int) date('j', strtotime((string) $record->lesson_date));
            $recordsByDay[$day][] = [
                'plan' => $record->lessonPlanFk !== null ? $record->lessonPlanFk->name : '',
                'status' => $record->getStatusLabel(),
                'content' => $record->executed_content,
            ];
        }

        $days = [];
        foreach ($instructionalDays as $day) {
            $days[] = [
                'day' => $day,
                'registered' => isset($recordsByDay[$day]),
                'records' => $recordsByDay[$day] ?? [],
            ];
        }

        echo CJSON::encode([
            'valid' => true,
            'totalScheduled' => count($instructionalDays),
            'totalRegistered' => count($records),
            'days' => $days,
        ]);
        Yii::app()->end();
    }

    private function instructionalDaysService(): MaceteInstructionalDaysService
    {
        if ($this->instructionalDaysService === null) {
            $this->instructionalDaysService = new MaceteInstructionalDaysService();
        }

        return $this->instructionalDaysService;
    }

    private function lessonPlanService(): MaceteLessonPlanService
    {
        if ($this->lessonPlanService === null) {
            $this->lessonPlanService = new MaceteLessonPlanService();
        }

        return $this->lessonPlanService;
    }

    private function lessonRecordService(): MaceteLessonRecordService
    {
        if ($this->lessonRecordService === null) {
            $this->lessonRecordService = new MaceteLessonRecordService();
        }

        return $this->lessonRecordService;
    }

    private function accessService(): MaceteAccessService
    {
        if ($this->accessService === null) {
            $this->accessService = new MaceteAccessService();
        }

        return $this->accessService;
    }
}
