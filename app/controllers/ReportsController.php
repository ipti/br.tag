<?php

require_once __DIR__.'/../repository/ReportsRepository.php';

class ReportsController extends Controller
{
    public $layout = 'reportsclean';
    public $year = 0;

    public function accessRules()
    {
        return [
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'BFReport', 'numberStudentsPerClassroomReport',
                    'EnrollmentStatisticsByYearReport', 'InstructorsPerClassroomReport', 'StudentsFileReport',
                    'getStudentsFileInformation', 'ResultBoardReport',
                    'StatisticalDataReport', 'StudentsDeclarationReport',
                    'EnrollmentPerClassroomReport', 'AtaSchoolPerformance',
                    'EnrollmentDeclarationReport', 'TransferForm',
                    'StudentsWithDisabilitiesPerClassroom', 'StudentsWithDisabilitiesPerSchool',
                    'EnrollmentNotification', 'TransferRequirement',
                    'EnrollmentComparativeAnalysisReport', 'SchoolProfessionalNumberByClassroomReport',
                    'ComplementarActivityAssistantByClassroomReport', 'EducationalAssistantPerClassroomReport',
                    'DisciplineAndInstructorRelationReport', 'ClassroomWithoutInstructorRelationReport',
                    'StudentInstructorNumbersRelationReport', 'StudentPendingDocument',
                    'BFRStudentReport', 'ElectronicDiary', 'OutOfTownStudentsReport', 'StudentSpecialFood',
                    'ClassCouncilReport', 'QuarterlyReport', 'GetStudentClassrooms', 'QuarterlyFollowUpReport',
                    'EvaluationFollowUpStudentsReport', 'CnsPerClassroomReport', 'CnsSchools', 'CnsPerSchool',
                    'TeacherTrainingReport', 'ClassroomTransferReport', 'SchoolTransferReport', 'AllSchoolsTransferReport',
                    'TeachersByStage', 'TeachersBySchool', 'StatisticalData', 'NumberOfStudentsEnrolledPerPeriodPerClassroom',
                    'NumberOfStudentsEnrolledPerPeriodPerSchool', 'NumberOfStudentsEnrolledPerPeriodAllSchools',
                    'AllSchoolsReportOfStudentsBenefitingFromTheBF', 'AllClassroomsReportOfStudentsBenefitingFromTheBF',
                    'ReportOfStudentsBenefitingFromTheBFPerClassroom', 'TeachersByStage', 'TeachersBySchool', 'StatisticalData',
                    'NumberOfClassesPerSchool', 'NumberOfClassesPerSchool', 'StudentCpfRgNisPerClassroom', 'FoodMenu'],
                'users' => ['@'],
            ],
            ['deny', // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Yii::app()->user->isGuest) {
            $this->redirect(yii::app()->createUrl('site/login'));
        }

        $this->year = Yii::app()->user->year;

        return true;
    }

    public function actionTotalNumberOfStudentsEnrolled()
    {
        $repository = new ReportsRepository();
        $query = $repository->getTotalNumberOfStudentsEnrolled();
        $this->render('TotalNumberOfStudentsEnrolled', $query);
    }

    public function actionStudentCpfRgNisAllSchools()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentCpfRgNisAllSchools();
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionStudentCpfRgNisAllClassrooms()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentCpfRgNisAllClassrooms();
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionStudentCpfRgNisPerClassroom()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentCpfRgNisPerClassroom(Yii::app()->request);
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodAllSchools()
    {
        $repository = new ReportsRepository();
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodAllSchools(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodPerSchool()
    {
        $repository = new ReportsRepository();
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodPerSchool(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodPerClassroom()
    {
        $repository = new ReportsRepository();
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodPerClassroom(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);
    }

    public function actionAllClassroomsReportOfStudentsBenefitingFromTheBF()
    {
        $repository = new ReportsRepository();
        $query = $repository->getAllClassroomsReportOfStudentsBenefitingFromTheBF();
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionAllSchoolsReportOfStudentsBenefitingFromTheBF()
    {
        $repository = new ReportsRepository();
        $query = $repository->getAllSchoolsReportOfStudentsBenefitingFromTheBF();
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionReportOfStudentsBenefitingFromTheBFPerClassroom()
    {
        $repository = new ReportsRepository();
        $query = $repository->getReportOfStudentsBenefitingFromTheBFPerClassroom(Yii::app()->request);
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionNumberOfClassesPerSchool()
    {
        $repository = new ReportsRepository();
        $query = $repository->getNumberOfClassesPerSchool();
        $this->render('NumberOfClassesPerSchool', $query);
    }

    public function actionTeacherTrainingReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getTeacherTrainingReport(Yii::app()->request);
        $this->render('buzios/TeacherTraining', $query);
    }

    public function actionStatisticalData()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStatisticalData();
        $this->render('StatisticalData', $query);
    }

    public function actionClassroomTransferReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getClassroomTransferReport(Yii::app()->request);
        $this->render('TransferReport', $query);
    }

    public function actionSchoolTransferReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getSchoolTransferReport();
        $this->render('TransferReport', $query);
    }

    public function actionAllSchoolsTransferReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getAllSchoolsTransferReport();
        $this->render('TransferReport', $query);
    }

    public function actionTeachersByStage()
    {
        $repository = new ReportsRepository();
        $query = $repository->getTeachersByStage();
        $this->render('TeachersByStage', $query);
    }

    public function actionTeachersBySchool()
    {
        $repository = new ReportsRepository();
        $query = $repository->getTeachersBySchool();
        $this->render('TeachersBySchool', $query);
    }

    public function actionCnsPerClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getCnsPerClassroomReport(Yii::app()->request);
        $this->render('CnsReport', $query);
    }

    public function actionCnsSchools()
    {
        $repository = new ReportsRepository();
        $query = $repository->getCnsSchools();
        $this->render('CnsReport', $query);
    }

    public function actionCnsPerSchool()
    {
        $repository = new ReportsRepository();
        $query = $repository->getCnsPerSchool();
        $this->render('CnsReport', $query);
    }

    public function actionQuarterlyReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getQuarterlyReport(Yii::app()->request);
        if ($query) {
            $this->render($query['view'], $query['response']);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionQuarterlyFollowUpReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getQuarterlyFollowUpReport(Yii::app()->request);
        if ($query['error']) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query['message']));

            return $this->redirect(['index']);
        } else {
            $this->render('buzios/quarterly/QuarterlyFollowUpReport', $query['response']);
        }
    }

    public function actionEvaluationFollowUpStudentsReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getEvaluationFollowUpStudentsReport(Yii::app()->request);
        if ($query['error']) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query['message']));

            return $this->redirect(['index']);
        } else {
            $this->render('buzios/quarterly/EvaluationFollowUpReport', $query['response']);
        }
    }

    public function actionClassCouncilReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getClassCouncilReport(Yii::app()->request);
        if ($query['error']) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query['message']));

            return $this->redirect(['index']);
        } else {
            $this->render($query['view'], $query['response']);
        }
    }

    public function actionStudentsUsingSchoolTransportationRelationReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentsUsingSchoolTransportationRelationReport();
        $this->render('StudentsUsingSchoolTransportationRelationReport', $query);
    }

    public function actionStudentsWithDisabilitiesPerClassroom()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentsWithDisabilitiesPerClassroom(Yii::app()->request);
        $this->render('StudentsWithDisabilitiesPerClassroom', $query);
    }

    public function actionStudentsWithDisabilitiesPerSchool()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentsWithDisabilitiesPerSchool();
        $this->render('StudentsWithDisabilitiesPerSchool', $query);
    }

    public function actionStudentsWithDisabilitiesRelationReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentsWithDisabilitiesRelationReport();
        $this->render('StudentsWithDisabilitiesRelationReport', $query);
    }

    public function actionStudentsInAlphabeticalOrderRelationReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentsInAlphabeticalOrderRelationReport();
        $this->render('StudentsInAlphabeticalOrderRelationReport', $query);
    }

    public function actionEnrollmentPerClassroomReport($id)
    {
        $repository = new ReportsRepository();
        $query = $repository->getEnrollmentPerClassroomReport($id);
        $this->render('EnrollmentPerClassroomReport', $query);
    }

    public function actionStudentPendingDocument()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentPendingDocument();
        $this->render('StudentPendingDocument', $query);
    }

    public function actionStudentPerClassroom($id)
    {
        $this->layout = 'reports';
        $repository = new ReportsRepository();
        $query = $repository->getStudentPerClassroom($id);
        $this->render('StudentPerClassroom', $query);
    }

    public function actionClocPerClassroom($id)
    {
        $repository = new ReportsRepository();
        $query = $repository->getClocPerClassroom($id);
        $this->render('ClocPerClassroom', $query);
    }

    public function actionStudentsByClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentsByClassroomReport();
        $this->render('StudentsByClassroomReport', $query);
    }

    public function actionStudentsBetween5And14YearsOldReport()
    {
        $this->layout = 'reportsclean';
        $repository = new ReportsRepository();
        $query = $repository->getStudentsBetween5And14YearsOldReport();
        $this->render('StudentsBetween5And14YearsOldReport', $query);
    }

    public function actionComplementarActivityAssistantByClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getComplementarActivityAssistantByClassroomReport();
        $this->render('ComplementarActivityAssistantByClassroomReport', $query);
    }

    public function actionDisciplineAndInstructorRelationReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getDisciplineAndInstructorRelationReport();
        $this->render('DisciplineAndInstructorRelationReport', $query);
    }

    public function actionIncompatibleStudentAgeByClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getIncompatibleStudentAgeByClassroomReport();
        $this->render('IncompatibleStudentAgeByClassroomReport', $query);
    }

    public function actionStudentsWithOtherSchoolEnrollmentReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentsWithOtherSchoolEnrollmentReport();
        $this->render('StudentsWithOtherSchoolEnrollmentReport', $query);
    }

    public function actionEducationalAssistantPerClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getEducationalAssistantPerClassroomReport();
        $this->render('EducationalAssistantPerClassroomReport', $query);
    }

    public function actionClassroomWithoutInstructorRelationReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getClassroomWithoutInstructorRelationReport();
        $this->render('ClassroomWithoutInstructorRelationReport', $query);
    }

    public function actionStudentInstructorNumbersRelationReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentInstructorNumbersRelationReport();
        $this->render('StudentInstructorNumbersRelationReport', $query);
    }

    public function actionSchoolProfessionalNumberByClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getSchoolProfessionalNumberByClassroomReport();
        $this->render('SchoolProfessionalNumberByClassroomReport', $query);
    }

    public function actionStudentByClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentByClassroom(Yii::app()->request);
        $this->render('StudentByClassroomReport', $query);
    }

    public function actionEnrollmentComparativeAnalysisReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getEnrollmentComparativeAnalysis();
        $this->render('EnrollmentComparativeAnalysisReport', $query);
    }

    public function actionNumberStudentsPerClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getNumberStudentsPerClassroom();
        $this->render('NumberStudentsPerClassroomReport', $query);
    }

    public function actionEnrollmentStatisticsByYearReport()
    {
        // Construíndo condicionais e definindo ordenação para a consulta
        $criteria = new CDbCriteria();
        $criteria->order = 'edcenso_stage_vs_modality_fk, name ASC';
        $criteria->condition = 'school_year = :year';
        $criteria->params = ['year' => Yii::app()->user->year];
        //Consulta todas as classes abertas no ano atual
        $classrooms = Classroom::model()->findAll($criteria);
        $stages = [];
        $schools = [];
        foreach ($classrooms as $classroom) {
            //Coloca em um array o nome de todas as escolas que já não estão no mesmo (através da lista de classes)
            if (!in_array($classroom->schoolInepFk->name, $schools)) {
                $schools[] = $classroom->schoolInepFk->name;
            }
            //Coloca em um array todos o stage number e nome dos estágios que
            // já não estão no mesmo (através da lista de classes)
            if (array_search($classroom->edcensoStageVsModalityFk->name, array_column($stages, 'name')) === false) {
                array_push($stages, ['stageNumber' => $classroom->edcensoStageVsModalityFk->stage,
                    'name'                         => $classroom->edcensoStageVsModalityFk->name,
                    'alias'                        => $classroom->edcensoStageVsModalityFk->alias]);
            }
        }
        //Cria a primeira linha da tabela com o grupo de estágios
        $stageNumberGroups = [];
        foreach ($stages as $stage) {
            if ($stageNumberGroups[$stage['stageNumber']] == null) {
                //Adiciona indexado pelo stageNumber do array $stage a quantidade de celulas do estágio e o nome
                $stageNumberGroups[$stage['stageNumber']]['colspan'] = 0;
                $stageNumberGroups[$stage['stageNumber']]['colname'] = $this->translateStageNumbers($stage['stageNumber']);
            }
            //Pra cada estagio, concatena mais uma celula ao grupo de estagios
            $stageNumberGroups[$stage['stageNumber']]['colspan']++;
        }

        //Inicializa as celulas de contagem de matriculas com valor 0
        $schoolStages = [];
        foreach ($schools as $school) {
            foreach ($stages as $stage) {
                $schoolStages[$school][$stage['stageNumber']][$stage['name']] = 0;
            }
        }

        //Para cada classe incrementa o contador de matriculas em cada celular dos estágios
        foreach ($classrooms as $classroom) {
            $schoolStages[$classroom->schoolInepFk->name][$classroom->edcensoStageVsModalityFk->stage][$classroom->edcensoStageVsModalityFk->name] += count($classroom->activeStudentEnrollments);
        }

        $this->render('EnrollmentStatisticsByYearReport', [
            'schoolStages'      => $schoolStages,
            'stageNumberGroups' => $stageNumberGroups,
            'stages'            => $stages,
        ]);
    }

    public function actionClassContentsReport($classroomId, $month, $year, $disciplineId)
    {
        $classroom = Classroom::model()->findByPk($classroomId);
        $classroomName = $classroom->name;
        $disciplineId = $disciplineId === 'null' ? null : $disciplineId;
        $disciplineName = EdcensoDiscipline::model()->findByAttributes(['id' => $disciplineId])->name ?? null;
        if (TagUtils::isInstructor()) {
            $instructorName = InstructorIdentification::model()->findByAttributes(['users_fk' => Yii::app()->user->loginInfos->id])->name ?? null;
        }
        $students = Yii::app()->db->createCommand(
            'select si.id, si.name from student_enrollment se join student_identification si on si.id = se.student_fk
            where classroom_fk = :classroom_fk
            order by si.name'
        )
        ->bindParam(':classroom_fk', $classroomId)
        ->queryAll();
        $isMinorEducation = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : Classroom::model()->checkIsStageMinorEducation($classroom);
        $totalClasses = ClassContents::model()->getTotalClassesByMonth($classroomId, $month, $year, $disciplineId);
        $totalClassContents = ClassContents::model()->getTotalClassContentsByMonth($classroomId, $month, $year, $disciplineId);

        if (!$isMinorEducation) {
            $schedules = $this->getSchedulesFromMajorStage($classroomId, $month, $year, $disciplineId);
        } else {
            $schedules = $this->getSchedulesFromMinorStage($classroomId, $month, $year);
        }

        $classContents = ClassContents::model()->buildClassContents($schedules, $students);
        $frequency = $this->getFrequencyPercentage($schedules, $students);

        $month = str_pad($month, 2, '0', STR_PAD_LEFT);

        $this->layout = 'reportsclean';
        $this->render('ClassContentsReport', [
            'classContents'      => $classContents,
            'totalClasses'       => $totalClasses,
            'totalClassContents' => $totalClassContents,
            'instructorName'     => $instructorName,
            'disciplineName'     => $disciplineName,
            'classroomName'      => $classroomName,
            'frequency'          => $frequency,
            'month'              => $month,
            'year'               => $year,
        ]);
    }

    private function getSchedulesFromMajorStage($classroomId, $month, $year, $disciplineId)
    {
        return Schedule::model()->findAll(
            'classroom_fk = :classroom_fk and month = :month and year = :year and discipline_fk = :discipline_fk and unavailable = 0 group by day order by day, schedule',
            [
                'classroom_fk'  => $classroomId,
                'month'         => $month,
                'year'          => $year,
                'discipline_fk' => $disciplineId,
            ]
        );
    }

    private function getSchedulesFromMinorStage($classroomId, $month, $year)
    {
        return Schedule::model()->findAll(
            'classroom_fk = :classroom_fk and month = :month and year = :year and unavailable = 0 group by day order by day, schedule',
            [
                'classroom_fk' => $classroomId,
                'month'        => $month,
                'year'         => $year,
            ]
        );
    }

    private function getFrequencyPercentage($schedules, $students)
    {
        $frequency = [];
        foreach ($schedules as $schedule) {
            foreach ($students as $student) {
                $studentStatus = StudentEnrollment::model()->findByAttributes(['student_fk' => $student['id'], 'classroom_fk' => $schedule->classroom_fk])->status ?? null;
                if ($studentStatus == 1 || $studentStatus == 6 || $studentStatus == 7 || $studentStatus == 8) {
                    $frequency[$schedule->day]['totalStudents'] += 1;
                }

                $classFault = ClassFaults::model()->find('schedule_fk = :schedule_fk and student_fk = :student_fk', ['schedule_fk' => $schedule->id, 'student_fk' => $student['id']]);
                if ($classFault) {
                    $frequency[$schedule->day]['totalAbsentStudents'] += 1;
                }
            }
            if ($frequency[$schedule->day]['totalStudents'] == 0) {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Não há alunos ativos nessa turma.'));

                return $this->redirect(['classes/classContents']);
            }
            $frequency[$schedule->day]['attendance'] = round(100 - (($frequency[$schedule->day]['totalAbsentStudents'] / $frequency[$schedule->day]['totalStudents']) * 100), 2);
        }

        return $frequency;
    }

    private function translateStageNumbers($stageNumber)
    {
        switch($stageNumber) {
            case '1' :
                return 'Ensino Infantil';
            case '2':
                return 'Ensino Fundamental Menor';
            case '3':
                return 'Ensino Fundamental Maior';
            case '4':
                return 'Ensino Médio';
            case '5':
                return 'Curso Técnico';
            case '6':
                return 'EJA';
            case '7':
                return 'Multisseriada';
            default:
                return 'Outro';
        }
    }

    public function actionClocReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getClocReport();
        $this->render('NumberStudentsPerClassroomReport', $query);
    }

    public function actionInstructorsPerClassroomReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getInstructorsPerClassroom();
        $this->render('InstructorsPerClassroomReport', $query);
    }

    public function actionBFReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getAttendanceForBF(Yii::app()->request);
        $this->render('BFReport', $query);
    }

    public function actionBFRStudentReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentsParticipatingInBF();
        $this->render('BFRStudentReport', $query);
    }

    public function actionIndex()
    {
        $this->layout = 'fullmenu';
        $repository = new ReportsRepository();
        $data = $repository->getIndexData();
        $this->render('index', $data);
    }

    public function actionElectronicDiary()
    {
        $this->layout = 'fullmenu';
        $repository = new ReportsRepository();
        $data = $repository->getElectronicDiary();
        $this->render('ElectronicDiary', $data);
    }

    public function actionOutOfTownStudentsReport()
    {
        $repository = new ReportsRepository();
        $query = $repository->getOutOfTownStudents();
        $this->render('OutOfTownStudentsReport', $query);
    }

    public function actionStudentSpecialFood()
    {
        $repository = new ReportsRepository();
        $query = $repository->getStudentSpecialFood();
        $this->render('StudentSpecialFood', $query);
    }

    public function actionGetStudentClassrooms($id)
    {
        $repository = new ReportsRepository();
        $students = $repository->getStudentClassroomsOptions($id);
        foreach ($students as $student) {
            echo CHtml::tag('option', ['value' => $student['id']], $student['name'], true);
        }
    }

    public function actionGetDisciplines()
    {
        $repository = new ReportsRepository();
        $repository->getDisciplines(Yii::app()->request);
    }

    public function actionGetStagesMulti()
    {
        $classroomId = Yii::app()->request->getPost('classroomId');
        $enrollmentId = Yii::app()->request->getPost('enrollmentId');

        if (!is_numeric($classroomId) && !is_numeric($enrollmentId)) {
            echo CHtml::tag('option', [], CHtml::encode('Invalid classroom ID'), true);
            Yii::app()->end();
        }

        $classroom = Classroom::model()->findByPk($classroomId);
        $enrollment = StudentEnrollment::model()->findByPk($enrollmentId);

        if (!$classroom || !$enrollment) {
            echo CHtml::tag('option', [], CHtml::encode('Classroom or enrollment not found'), true);
            Yii::app()->end();
        }

        $classroomStage = $classroom->edcensoStageVsModalityFk;
        $enrollmentStage = $enrollment->edcensoStageVsModalityFk;

        // Opção padrão
        echo CHtml::tag('option', [
            'value'                => '',
            'data-classroom-stage' => '',
        ], CHtml::encode('Selecione...'), true);

        echo CHtml::tag('option', [
            'value'                => $classroomStage->id,
            'data-classroom-stage' => '1',
        ], CHtml::encode($classroomStage->name), true);

        echo CHtml::tag('option', [
            'value'                => $enrollmentStage->id,
            'data-classroom-stage' => '0',
        ], CHtml::encode($enrollmentStage->name), true);

        Yii::app()->end();
    }

    public function actionGetEnrollments()
    {
        $repository = new ReportsRepository();
        $repository->getEnrollments(Yii::app()->request);
    }

    public function actionGenerateElectronicDiaryReport()
    {
        $repository = new ReportsRepository();
        $repository->getElectronicDiaryData(Yii::app()->request);
    }
}
