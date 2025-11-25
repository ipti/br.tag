<?php

Yii::import('application.modules.classdiary.usecases.*');
Yii::import('application.components.auth.*');

class DefaultController extends Controller
{
    public $features = [TTask::TASK_DIARY_RECORD];

    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'Index',
                    'GetClassrooms',
                    'ClassDiary',
                    'classDays',
                    'GetMonths',
                    'GetDates',
                    'GetClassesContents',
                    'SaveClassContents',
                    'RenderAccordion',
                    'RenderFrequencyElementMobile',
                    'RenderFrequencyElementDesktop',
                    'SaveFresquency',
                    'StudentClassDiary',
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
        try {
            $getDisciplines = new GetDisciplines();
            $disciplines = $getDisciplines->exec();
            $this->render('index', [
                'disciplines' => $disciplines
            ]);
        } catch (\Throwable $th) {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro ao carregar as turmas.'));
        }
    }

    public function actionGetClassrooms()
    {
        $getClassrooms = new GetClassrooms();
        $isInstructor = Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id);
        $discipline = $_POST['discipline'];
        $classrooms = $getClassrooms->exec($isInstructor, $discipline);
        echo json_encode($classrooms, JSON_OBJECT_AS_ARRAY);
    }

    public function actionClassDiary($disciplineName, $classroomName, $date, $disciplineFk, $stageFk)
    {
        $getCoursePlans = new GetCoursePlans();
        $coursePlans = $getCoursePlans->exec($disciplineFk, $stageFk);

        $getAbilities = new GetAbilities();
        $abilities = $getAbilities->exec($disciplineFk, $stageFk);

        $this->render('classDiary', [
            'discipline_name' => $disciplineName,
            'classroom_name' => $classroomName,
            'coursePlans' => $coursePlans,
            'abilities' => $abilities,
            'date' => $date,
        ]);
    }

    public function actionclassDays($disciplineName, $classroomName)
    {
        $this->render('classDays', [
            'discipline_name' => $disciplineName,
            'classroom_name' => $classroomName
        ]);
    }

    public function actionGetMonths()
    {
        $result = [];
        $classroom = Classroom::model()->findByPk($_POST['classroom']);
        if ($classroom->calendar_fk != null) {
            $result['months'] = [];
            $calendar = $classroom->calendarFk;
            $begin = new Datetime($calendar->start_date);
            $begin->modify('first day of this month');
            $end = new Datetime($calendar->end_date);
            $end->modify('first day of next month');
            $interval = DateInterval::createFromDateString('1 month');
            $period = new DatePeriod($begin, $interval, $end);
            $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
            foreach ($period as $date) {
                array_push($result['months'], ['id' => $date->format('Y') . '-' . $date->format('n'), 'name' => $meses[$date->format('n') - 1] . '/' . $date->format('Y')]);
            }
            $result['valid'] = true;
        } else {
            $result = ['valid' => false, 'error' => 'A Turma está sem Calendário Escolar vinculado.'];
        }
        echo json_encode($result);
    }

    private function getInstructorFilter($classroom)
    {
        if (!TagUtils::isInstructor()) {
            return '';
        }

        $condition = TagUtils::isSubstituteInstructor($classroom) ? 'is not null' : 'is null';
        return 'and substitute_instructor_fk ' . $condition;
    }

    public function actionGetDates()
    {
        $year = $_POST['year'];
        $month = $_POST['month'];
        $classroomId = $_POST['classroom'];
        $disciplineId = $_POST['discipline'];
        $classroom = Classroom::model()->findByPk($classroomId);
        $discipline = EdcensoDiscipline::model()->findByPk($disciplineId);
        $isMinor = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : $this->checkIsStageMinorEducation($classroom);
        $instructorFilter = $this->getInstructorFilter($classroom);
        if ($isMinor === false) {
            $schedules = Schedule::model()->findAll(
                'classroom_fk = :classroom_fk and year = :year and month = :month and discipline_fk = :discipline_fk and unavailable = 0 ' . $instructorFilter . ' order by day, schedule',
                [
                    'classroom_fk' => $classroomId,
                    'year' => $year,
                    'month' => $month,
                    'discipline_fk' => $disciplineId
                ]
            );
        } else {
            $schedules = Schedule::model()->findAll(
                'classroom_fk = :classroom_fk and year = :year and month = :month and unavailable = 0 ' . $instructorFilter . ' group by day order by day, schedule',
                [
                    'classroom_fk' => $classroomId,
                    'year' => $year,
                    'month' => $month
                ]
            );
        }

        $criteria = new CDbCriteria();
        $criteria->with = ['studentFk'];
        $criteria->together = true;
        $criteria->order = 'name';

        if ($schedules != null) {
            $scheduleDays = $this->getScheduleDays($schedules);
            echo json_encode(
                [
                    'valid' => true,
                    'scheduleDays' => $scheduleDays,
                    'classroom_fk' => $classroomId,
                    'stage_fk' => $classroom->edcenso_stage_vs_modality_fk,
                    'discipline_fk' => $disciplineId,
                    'discipline_name' => $discipline->name,
                    'classroom_name' => $classroom->name,
                ]
            );
        } else {
            echo json_encode(['valid' => false, 'error' => 'Mês/Ano ' . ($isMinor === false ? 'e Disciplina' : '') . ' sem aula no Quadro de Horário.']);
        }
    }

    public function actionGetClassesContents($classroomFk, $stageFk, $date, $disciplineFk)
    {
        $getClassContents = new GetClassContents();
        $classContent = $getClassContents->exec($classroomFk, $stageFk, $date, $disciplineFk);
        header('Content-Type: application/json; charset="UTF-8"');
        echo json_encode($classContent, JSON_OBJECT_AS_ARRAY);
    }

    public function actionSaveClassContents()
    {
        $stageFk = Yii::app()->request->getPost('stage_fk');
        $date = Yii::app()->request->getPost('date');
        $disciplineFk = Yii::app()->request->getPost('discipline_fk');
        $classroomFk = Yii::app()->request->getPost('classroom_fk');
        $classContent = Yii::app()->request->getPost('classContent');
        $hasNewClassContent = Yii::app()->request->getPost('hasNewClassContent');
        $content = Yii::app()->request->getPost('content');
        $methodology = Yii::app()->request->getPost('methodology');
        $coursePlanId = Yii::app()->request->getPost('coursePlanId');
        $abilities = Yii::app()->request->getPost('abilities');

        if ($hasNewClassContent) {
            $saveNewClassContent = new SaveNewClassContent();
            $newClassContentId = $saveNewClassContent->exec($coursePlanId, $content, $methodology, $abilities);
            if ($classContent != null) {
                $classContent[] = $newClassContentId;
            } else {
                $classContent = [$newClassContentId];
            }
        }
        $saveClassContent = new SaveClassContents();
        $saveClassContent->exec($stageFk, $date, $disciplineFk, $classroomFk, $classContent);
    }

    public function actionRenderAccordion()
    {
        $courseClassId = $_POST['id'];
        $getCourseClasses = new GetCourseClasses();
        $getCourseClasses->exec($courseClassId);
        $planName = $_POST['plan_name'];
        $this->renderPartial('_accordion', ['plan_name' => $planName]);
    }

    public function actionRenderFrequencyElementMobile($classroomFk, $stageFk, $disciplineFk, $date)
    {
        $getFrequency = new GetFrequency();
        $frequency = $getFrequency->exec($classroomFk, $stageFk, $disciplineFk, $date);
        $this->renderPartial('_frequencyElementMobile', ['frequency' => $frequency, 'date' => $date,  'discipline_fk' => $disciplineFk, 'stage_fk' => $stageFk, 'classroom_fk' => $classroomFk]);
    }

    public function actionRenderFrequencyElementDesktop($classroomFk, $stageFk, $disciplineFk, $date)
    {
        $getFrequency = new GetFrequency();
        $frequency = $getFrequency->exec($classroomFk, $stageFk, $disciplineFk, $date);
        $this->renderPartial('frequencyElementDesktop', ['frequency' => $frequency]);
    }

    public function actionSaveFresquency()
    {
        $saveFrequency = new SaveFrequency();
        $saveFrequency->exec($_POST['schedule'], $_POST['studentId'], $_POST['fault'], $_POST['stage_fk'], $_POST['date'], $_POST['classroom_id']);
    }

    public function actionStudentClassDiary($studentId, $stageFk, $classroomId, $schedule, $date, $disciplineFk, $justification)
    {
        $getStudent = new GetStudent();
        $student = $getStudent->exec($studentId);

        $getStudentFault = new GetStudentFault();
        $studentFault = $getStudentFault->exec($stageFk, $classroomId, $disciplineFk, $date, $studentId, $schedule) != null;

        $getStudentDiary = new GetStudentDiary();
        $studentObservation = $getStudentDiary->exec($stageFk, $classroomId, $disciplineFk, $date, $studentId);

        if (isset($_POST['justification'])) {
            $justification = $_POST['justification'];
            $saveJustification = new SaveJustification();
            $saveJustification->exec($studentId, $stageFk, $classroomId, $schedule, $date, $justification);
        }
        if (isset($_POST['student_observation'])) {
            $studentObservation = $_POST['student_observation'];
            $saveStudentDiary = new SaveStudentDiary();
            $saveStudentDiary->exec($stageFk, $classroomId, $date, $disciplineFk, $studentId, $studentObservation);
        }
        if (isset($_POST['student_observation']) || isset($_POST['justification'])) {
            $getDiscipline = new GetDiscipline();
            $discipline = $getDiscipline->exec($disciplineFk)[0]['name'];
            $classroom = Classroom::model()->findByPk($classroomId);
            $this->redirect(['classDiary', 'classroom_fk' => $classroomId, 'stage_fk' => $stageFk, 'discipline_fk' => $disciplineFk, 'discipline_name' => $discipline, 'classroom_name' => $classroom->name, 'date' => $date]);
        }

        $this->render('studentClassDiary', ['student' => $student, 'stage_fk' => $stageFk, 'classroom_id' => $classroomId, 'schedule' => $schedule, 'date' => $date, 'justification' => $justification, 'studentFault' => $studentFault, 'student_observation' => $studentObservation]);
    }

    private function checkIsStageMinorEducation($classroom)
    {
        $isMinor = TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk);

        if (!$isMinor && TagUtils::isMultiStage($classroom->edcenso_stage_vs_modality_fk)) {
            $enrollments = StudentEnrollment::model()->findAllByAttributes(['classroom_fk' => $classroom->id]);

            foreach ($enrollments as $enrollment) {
                if (!$enrollment->edcenso_stage_vs_modality_fk ||
                    !TagUtils::isStageMinorEducation($enrollment->edcenso_stage_vs_modality_fk)) {
                    return false;
                }
            }

            $isMinor = true;
        }

        return $isMinor;
    }

    private function getScheduleDays($schedules)
    {
        $result = [];
        $dayName = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
        foreach ($schedules as $schedule) {
            $day = ($schedule->day < 10) ? '0' . $schedule->day : $schedule->day;
            $month = ($schedule->month < 10) ? '0' . $schedule->month : $schedule->month;
            $date = $day . '/' . $month . '/' . $schedule->year;
            $index = array_search($date, array_column($result, 'date'));
            if ($index === false) {
                array_push($result, [
                    'day' => $schedule->day,
                    'date' => $date,
                    'week_day' => $dayName[$schedule->week_day],
                ]);
            }
        }
        return $result;
    }
}
