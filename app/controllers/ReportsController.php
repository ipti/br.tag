<?php

require_once __DIR__.'/../repository/ReportsRepository.php';

class ReportsController extends Controller
{
    public $layout = 'reportsclean';
    public $year = 0;

    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'BFReport', 'numberStudentsPerClassroomReport',
                    'InstructorsPerClassroomReport', 'StudentsFileReport',
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
                    'TeacherTrainingReport','ClassroomTransferReport', 'SchoolTransferReport', 'AllSchoolsTransferReport',
                    'TeachersByStage', 'TeachersBySchool', 'StatisticalData', 'NumberOfStudentsEnrolledPerPeriodPerClassroom',
                    'NumberOfStudentsEnrolledPerPeriodPerSchool', 'NumberOfStudentsEnrolledPerPeriodAllSchools',
                    'AllSchoolsReportOfStudentsBenefitingFromTheBF','AllClassroomsReportOfStudentsBenefitingFromTheBF',
                    'ReportOfStudentsBenefitingFromTheBFPerClassroom', 'TeachersByStage', 'TeachersBySchool', 'StatisticalData',
                    'NumberOfClassesPerSchool', 'NumberOfClassesPerSchool', 'StudentCpfRgNisPerClassroom',),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
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
        $repository = new ReportsRepository;
        $query = $repository->getTotalNumberOfStudentsEnrolled();
        $this->render('TotalNumberOfStudentsEnrolled', $query);
    }

    public function actionStudentCpfRgNisAllSchools()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentCpfRgNisAllSchools();
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionStudentCpfRgNisAllClassrooms()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentCpfRgNisAllClassrooms();
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionStudentCpfRgNisPerClassroom()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentCpfRgNisPerClassroom(Yii::app()->request);
        $this->render('StudentCpfRgNis', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodAllSchools()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodAllSchools(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodPerSchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodPerSchool(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);
    }

    public function actionNumberOfStudentsEnrolledPerPeriodPerClassroom()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberOfStudentsEnrolledPerPeriodPerClassroom(Yii::app()->request);
        $this->render('NumberOfStudentsEnrolledPerPeriod', $query);

    }

    public function actionAllClassroomsReportOfStudentsBenefitingFromTheBF()
    {
        $repository = new ReportsRepository;
        $query = $repository->getAllClassroomsReportOfStudentsBenefitingFromTheBF();
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionAllSchoolsReportOfStudentsBenefitingFromTheBF()
    {
        $repository = new ReportsRepository;
        $query = $repository->getAllSchoolsReportOfStudentsBenefitingFromTheBF();
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionReportOfStudentsBenefitingFromTheBFPerClassroom()
    {
        $repository = new ReportsRepository;
        $query = $repository->getReportOfStudentsBenefitingFromTheBFPerClassroom(Yii::app()->request);
        $this->render('ReportOfStudentsBenefitingFromTheBF', $query);
    }

    public function actionNumberOfClassesPerSchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberOfClassesPerSchool();
        $this->render('NumberOfClassesPerSchool', $query);
    }

    public function actionTeacherTrainingReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getTeacherTrainingReport(Yii::app()->request);
        $this->render('buzios/TeacherTraining', $query);
    }

    public function actionStatisticalData()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStatisticalData();
        $this->render('StatisticalData', $query);
    }

    public function actionClassroomTransferReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getClassroomTransferReport(Yii::app()->request);
        $this->render('TransferReport', $query);
    }

    public function actionSchoolTransferReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getSchoolTransferReport();
        $this->render('TransferReport', $query);
    }

    public function actionAllSchoolsTransferReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getAllSchoolsTransferReport();
        $this->render('TransferReport', $query);
    }
    public function actionTeachersByStage()
    {
        $repository = new ReportsRepository;
        $query = $repository->getTeachersByStage();
        $this->render('TeachersByStage', $query);
    }

    public function actionTeachersBySchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getTeachersBySchool();
        $this->render('TeachersBySchool', $query);
    }

    public function actionCnsPerClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getCnsPerClassroomReport(Yii::app()->request);
        $this->render('CnsReport', $query);
    }

    public function actionCnsSchools()
    {
        $repository = new ReportsRepository;
        $query = $repository->getCnsSchools();
        $this->render('CnsReport', $query);
    }

    public function actionCnsPerSchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getCnsPerSchool();
        $this->render('CnsReport', $query);
    }

    public function actionQuarterlyReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getQuarterlyReport(Yii::app()->request);
        if($query) {
            $this->render($query["view"], $query["response"]);
        }else {
            return $this->redirect(array('index'));
        }
    }

    public function actionQuarterlyFollowUpReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getQuarterlyFollowUpReport(Yii::app()->request);
        if($query["error"]) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query["message"]));
            return $this->redirect(array('index'));
        }else {
            $this->render('buzios/quarterly/QuarterlyFollowUpReport', $query["response"]);
        }
    }

    public function actionEvaluationFollowUpStudentsReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getEvaluationFollowUpStudentsReport(Yii::app()->request);
        if($query["error"]) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query["message"]));
            return $this->redirect(array('index'));
        }else {
            $this->render('buzios/quarterly/EvaluationFollowUpReport', $query["response"]);
        }
    }

    public function actionClassCouncilReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getClassCouncilReport(Yii::app()->request);
        if($query["error"]) {
            Yii::app()->user->setFlash('error', Yii::t('default', $query["message"]));
            return $this->redirect(array('index'));
        }else {
            $this->render($query["view"], $query["response"]);
        }
    }

    public function actionStudentsUsingSchoolTransportationRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsUsingSchoolTransportationRelationReport();
        $this->render('StudentsUsingSchoolTransportationRelationReport', $query);
    }

    public function actionStudentsWithDisabilitiesPerClassroom()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsWithDisabilitiesPerClassroom(Yii::app()->request);
        $this->render('StudentsWithDisabilitiesPerClassroom', $query);
    }

    public function actionStudentsWithDisabilitiesPerSchool()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsWithDisabilitiesPerSchool();
        $this->render('StudentsWithDisabilitiesPerSchool', $query);
    }

    public function actionStudentsWithDisabilitiesRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsWithDisabilitiesRelationReport();
        $this->render('StudentsWithDisabilitiesRelationReport', $query);
    }

    public function actionStudentsInAlphabeticalOrderRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsInAlphabeticalOrderRelationReport();
        $this->render('StudentsInAlphabeticalOrderRelationReport', $query);
    }

    public function actionEnrollmentPerClassroomReport($id)
    {
        $repository = new ReportsRepository;
        $query = $repository->getEnrollmentPerClassroomReport($id);
        $this->render('EnrollmentPerClassroomReport', $query);
    }

    public function actionStudentPendingDocument()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentPendingDocument();
        $this->render('StudentPendingDocument', $query);
    }

    public function actionStudentPerClassroom($id)
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentPerClassroom($id);
        $this->render('StudentPerClassroom', $query);
    }

    public function actionClocPerClassroom($id)
    {
        $repository = new ReportsRepository;
        $query = $repository->getClocPerClassroom($id);
        $this->render('ClocPerClassroom', $query);
    }

    public function actionStudentsByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsByClassroomReport();
        $this->render('StudentsByClassroomReport', $query);
    }

    public function actionStudentsBetween5And14YearsOldReport()
    {
        $this->layout = "reportsclean";
        $repository = new ReportsRepository;
        $query = $repository->getStudentsBetween5And14YearsOldReport();
        $this->render('StudentsBetween5And14YearsOldReport', $query);
    }

    public function actionComplementarActivityAssistantByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getComplementarActivityAssistantByClassroomReport();
        $this->render('ComplementarActivityAssistantByClassroomReport', $query);
    }

    public function actionDisciplineAndInstructorRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getDisciplineAndInstructorRelationReport();
        $this->render('DisciplineAndInstructorRelationReport', $query);
    }

    public function actionIncompatibleStudentAgeByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getIncompatibleStudentAgeByClassroomReport();
        $this->render('IncompatibleStudentAgeByClassroomReport', $query);
    }

    public function actionStudentsWithOtherSchoolEnrollmentReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsWithOtherSchoolEnrollmentReport();
        $this->render('StudentsWithOtherSchoolEnrollmentReport', $query);
    }

    public function actionEducationalAssistantPerClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getEducationalAssistantPerClassroomReport();
        $this->render('EducationalAssistantPerClassroomReport', $query);
    }

    public function actionClassroomWithoutInstructorRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getClassroomWithoutInstructorRelationReport();
        $this->render('ClassroomWithoutInstructorRelationReport', $query);
    }

    public function actionStudentInstructorNumbersRelationReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentInstructorNumbersRelationReport();
        $this->render('StudentInstructorNumbersRelationReport', $query);
    }

    public function actionSchoolProfessionalNumberByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getSchoolProfessionalNumberByClassroomReport();
        $this->render('SchoolProfessionalNumberByClassroomReport', $query);
    }

    public function actionStudentByClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentByClassroom(Yii::app()->request);
        $this->render('StudentByClassroomReport', $query);
    }

    public function actionEnrollmentComparativeAnalysisReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getEnrollmentComparativeAnalysis();
        $this->render('EnrollmentComparativeAnalysisReport', $query);
    }

    public function actionNumberStudentsPerClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getNumberStudentsPerClassroom();
        $this->render('NumberStudentsPerClassroomReport', $query);
    }

    public function actionClocReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getClocReport();
        $this->render('NumberStudentsPerClassroomReport', $query);
    }

    public function actionInstructorsPerClassroomReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getInstructorsPerClassroom();
        $this->render('InstructorsPerClassroomReport', $query);
    }

    public function actionBFReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getAttendanceForBF(Yii::app()->request);
        $this->render('BFReport', $query);
    }


    public function actionBFRStudentReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentsParticipatingInBF();
        $this->render('BFRStudentReport', $query);
    }

    public function actionIndex()
    {
        $this->layout = "fullmenu";
        $repository = new ReportsRepository;
        $data = $repository->getIndexData();
        $this->render('index', $data);
    }

    public function actionElectronicDiary()
    {
        $this->layout = "fullmenu";
        $repository = new ReportsRepository;
        $data = $repository->getElectronicDiary();
        $this->render('ElectronicDiary', $data);
    }

    public function actionOutOfTownStudentsReport()
    {
        $repository = new ReportsRepository;
        $query = $repository->getOutOfTownStudents();
        $this->render('OutOfTownStudentsReport', $query);
    }

    public function actionStudentSpecialFood()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentSpecialFood();
        $this->render('StudentSpecialFood', $query);
    }

    public function actionGetStudentClassrooms($id)
    {
        $repository = new ReportsRepository;
        $repository->getStudentClassroomsOptions($id);
    }

    public function actionGetDisciplines()
    {
        $repository = new ReportsRepository;
        $repository->getDisciplines(Yii::app()->request);
    }

    public function actionGetEnrollments()
    {
        $repository = new ReportsRepository;
        $repository->getEnrollments(Yii::app()->request);
    }

    public function actionGenerateElectronicDiaryReport()
    {
        $repository = new ReportsRepository;
        $repository->getElectronicDiaryData(Yii::app()->request);
    }
}
