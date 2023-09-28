<?php

require_once(__DIR__.'/../repository/FormsRepository.php');

class FormsController extends Controller {

    public $layout = 'fullmenu';
    public $year = 0;

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'EnrollmentGradesReport', 'StudentsFileReport','EnrollmentDeclarationReport',
                    'EnrollmentGradesReportBoquim','EnrollmentGradesReportBoquimCiclo',
                    'GetEnrollmentDeclarationInformation','TransferRequirement','GetTransferRequirementInformation',
                    'EnrollmentNotification','GetEnrollmentNotificationInformation','StudentsDeclarationReport',
                    'GetStudentsFileInformation','AtaSchoolPerformance','StudentFileForm',
                    'TransferForm','GetTransferFormInformation', 'StudentStatementAttended', 'IndividualRecord'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function beforeAction($action){
        
        if (Yii::app()->user->isGuest){
            $this->redirect(yii::app()->createUrl('site/login'));
        }
        
        $this->year = Yii::app()->user->year;
        
        return true;
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionEnrollmentGradesReport($enrollment_id)
    {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getEnrollmentGrades($enrollment_id);
        $this->render('EnrollmentGradesReport', $query);
    }
    
    public function actionIndividualRecord($enrollment_id)
    {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getIndividualRecord($enrollment_id);
        $this->render('IndividualRecord', $query);
    }

    public function actionEnrollmentGradesReportBoquim($enrollment_id)
    {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getEnrollmentGradesBoquim($enrollment_id);
        $this->render('EnrollmentGradesReportBoquim', $query);
    }
    public function actionEnrollmentGradesReportBoquimCiclo($enrollment_id)
    {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getEnrollmentGradesBoquimCiclo($enrollment_id);
        $this->render('EnrollmentGradesReportBoquimCiclo', $query);
    }

    public function actionEnrollmentDeclarationReport($enrollment_id)
    {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getEnrollmentDeclaration($enrollment_id);
        $this->render('EnrollmentDeclarationReport', $query);
    }

    public function actionGetEnrollmentDeclarationInformation($enrollment_id)
    {
        $repository = new FormsRepository;
        $repository->getEnrollmentDeclaration($enrollment_id);
    }

    public function actionTransferRequirement($enrollment_id)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository;
        $query = $repository->getTransferRequirement($enrollment_id);
        $this->render('TransferRequirement', $query);
    }

    public function actionGetTransferRequirementInformation($enrollment_id)
    {
        $repository = new FormsRepository;
        $repository->getTransferRequirementInformation($enrollment_id);
    }

    public function actionEnrollmentNotification($enrollment_id)
    {
        $this->layout = 'reports';
        $repository = new FormsRepository;
        $query = $repository->getEnrollmentNotification($enrollment_id);
        $this->render('EnrollmentNotification', $query);
    }

    public function actionGetEnrollmentNotificationInformation($enrollment_id)
    {
        $repository = new FormsRepository;
        $repository->getEnrollmentNotificationInformation($enrollment_id);
    }

    public function actionStudentsDeclarationReport($enrollment_id) 
    {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getStudentsDeclaration($enrollment_id);
        $this->render('StudentsDeclarationReport', $query);
    }

    public function actionGetStudentsFileInformation($enrollment_id)
    {
        $repository = new FormsRepository;
        $repository->getStudentsFileInformation($enrollment_id);
    }

    public function actionAtaSchoolPerformance($id) {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getAtaSchoolPerformance($id);
        $this->render('AtaSchoolPerformance', $query);
    }

    public function actionStudentFileForm($enrollment_id) {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getStudentFileForm($enrollment_id);
        $this->render('StudentFileForm', $query);
    }

    public function actionStudentsFileForm($classroom_id) {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getStudentsFileForm($classroom_id);
        $this->render('StudentsFileForm', $query);
    }

    public function actionTransferForm($enrollment_id){
        $this->layout = 'reports';
        $repository = new FormsRepository;
        $query = $repository->getTransferForm($enrollment_id);
        $this->render('TransferForm', $query);
    }

    public function actionGetTransferFormInformation($enrollment_id){
        $repository = new FormsRepository;
        $repository->getTransferFormInformation($enrollment_id);
    }

    public function actionStatementAttended($enrollment_id) {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getStatementAttended($enrollment_id);
        $this->render('StudentStatementAttended', $query);
    }

    public function actionWarningTerm($enrollment_id) {

        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getWarningTerm($enrollment_id);
        $this->render('WarningTerm', $query);
    }
}