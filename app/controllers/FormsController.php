<?php

require_once __DIR__ . '/../repository/FormsRepository.php';

class FormsController extends Controller
{
    public $layout = 'fullmenu';
    public $year = 0;

    public function accessRules()
    {
        return [
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['index', 'EnrollmentGradesReport', 'StudentsFileReport', 'EnrollmentDeclarationReport',
                    'EnrollmentGradesReportBoquim', 'EnrollmentGradesReportBoquimCiclo',
                    'GetEnrollmentDeclarationInformation', 'TransferRequirement', 'GetTransferRequirementInformation',
                    'EnrollmentNotification', 'GetEnrollmentNotificationInformation', 'StudentsDeclarationReport',
                    'GetStudentsFileInformation', 'AtaSchoolPerformance', 'StudentFileForm',
                    'TransferForm', 'GetTransferFormInformation', 'StudentStatementAttended', 'IndividualRecord'],
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

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionEnrollmentGradesReport($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getEnrollmentGrades($enrollmentId);
        $this->render('EnrollmentGradesReport', $query);
    }

    public function actionIndividualRecord($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getIndividualRecord($enrollmentId);
        $this->render('IndividualRecord', $query);
    }

    public function actionEnrollmentGradesReportBoquim($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getEnrollmentGradesBoquim($enrollmentId);
        $this->render('EnrollmentGradesReportBoquim', $query);
    }

    public function actionEnrollmentGradesReportBoquimCiclo($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getEnrollmentGradesBoquimCiclo($enrollmentId);
        $this->render('EnrollmentGradesReportBoquimCiclo', $query);
    }

    public function actionEnrollmentDeclarationReport($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getEnrollmentDeclaration($enrollmentId);
        $this->render('EnrollmentDeclarationReport', $query);
    }

    public function actionStudentIMCReport($classroomId)
    {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $response = $repository->getStudnetIMC($classroomId);
        $this->render('StudentIMCReport',  array("response" => $response));
    }

    public function actionConclusionCertification($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getConclusionCertification($enrollmentId);
        $this->render('ConclusionCertification', $query);
    }

    public function actionGetEnrollmentDeclarationInformation($enrollmentId)
    {
        $repository = new FormsRepository();
        $result = $repository->getEnrollmentDeclarationInformation($enrollmentId);

        echo CJSON::encode($result);
    }

    public function actionTransferRequirement($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getTransferRequirement($enrollmentId);
        $this->render('TransferRequirement', $query);
    }

    public function actionGetTransferRequirementInformation($enrollmentId)
    {
        $repository = new FormsRepository();
        $result = $repository->getTransferRequirementInformation($enrollmentId);

        echo CJSON::encode($result);
    }

    public function actionEnrollmentNotification($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getEnrollmentNotification($enrollmentId);
        $this->render('EnrollmentNotification', $query);
    }

    public function actionGetEnrollmentNotificationInformation($enrollmentId)
    {
        $repository = new FormsRepository();
        $result = $repository->getEnrollmentNotificationInformation($enrollmentId);

        echo CJSON::encode($result);
    }

    public function actionStudentsDeclarationReport($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getStudentsDeclaration($enrollmentId);
        $this->render('StudentsDeclarationReport', $query);
    }

    public function actionGetStudentsFileInformation($enrollmentId)
    {
        $repository = new FormsRepository();
        $result = $repository->getStudentsFileInformation($enrollmentId);

        echo CJSON::encode($result);
    }

    public function actionAtaSchoolPerformance($id)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getAtaSchoolPerformance($id);
        $this->render('AtaSchoolPerformance', $query);
    }

    public function actionStudentFileForm($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getStudentFileForm($enrollmentId);
        $this->render('StudentFileForm', $query);
    }

    public function actionStudentsFileForm($classroom_id)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getStudentsFileForm($classroom_id);
        $this->render('StudentsFileForm', $query);
    }

    public function actionTransferForm($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getTransferForm($enrollmentId);
        $this->render('TransferForm', $query);
    }

    public function actionGetTransferFormInformation($enrollmentId)
    {
        $repository = new FormsRepository();
        $result = $repository->getTransferFormInformation($enrollmentId);

        echo CJSON::encode($result);
    }

    public function actionStatementAttended($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getStatementAttended($enrollmentId);
        $this->render('StudentStatementAttended', $query);
    }

    public function actionWarningTerm($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getWarningTerm($enrollmentId);
        $this->render('WarningTerm', $query);
    }

    public function actionSuspensionTerm($enrollmentId)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository();
        $query = $repository->getSuspensionTerm($enrollmentId);
        $this->render('SuspensionTerm', $query);
    }
}
