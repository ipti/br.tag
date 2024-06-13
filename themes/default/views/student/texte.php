Observe esse codigos abaixo:

codiog 01:
<?php

class FormsRepository {

    private $currentSchool;
    private $currentYear;

    public function __construct()
    {
        $this->currentSchool = Yii::app()->user->school;
        $this->currentYear = Yii::app()->user->year;
    }


    /**
     * Declaração de ano cursado na escola
     */
    public function getStatementAttended($enrollmentId) : array
    {
        $sql = "SELECT si.name name_student, si.birthday, si.filiation_1, si.filiation_2, svm.name class,
                        svm.*, c.modality, c.school_year, svm.stage stage, svm.id class
                    FROM student_enrollment se
                JOIN student_identification si ON si.id = se.student_fk
                JOIN classroom c on se.classroom_fk = c.id
                JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id
                WHERE se.id = :enrollment_id;";

        $data = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        $modality = array(
            '1' => 'Ensino Regular',
            '2' => 'Educação Especial - Modalidade Substitutiva',
            '3' => 'Educação de Jovens e Adultos (EJA)'
        );

        $c = '';
        switch ($data['class']) {
            case '4':
                $c = '1º';
                break;
            case '5':
                $c = '2º';
                break;
            case '6':
                $c = '3º';
                break;
            case '7':
                $c = '4º';
                break;
            case '8':
                $c = '5º';
                break;
            case '9':
                $c = '6º';
                break;
            case '10':
                $c = '7º';
                break;
            case '11':
                $c = '8º';
                break;
            case '14':
                $c = '1º';
                break;
            case '15':
                $c = '2º';
                break;
            case '16':
                $c = '3º';
                break;
            case '17':
                $c = '4º';
                break;
            case '18':
                $c = '5º';
                break;
            case '19':
                $c = '6º';
                break;
            case '20':
                $c = '7º';
                break;
            case '21':
                $c = '8º';
                break;
            case '41':
                $c = '9º';
                break;
            case '35':
                $c = '1º';
                break;
            case '36':
                $c = '2º';
                break;
            case '37':
                $c = '3º';
                break;
            case '38':
                $c = '4º';
                break;
        }

        $descCategory = '';
        switch ($data['stage']) {
            case '1':
                $descCategory = "na Educação Infantil";
                break;
            case '3':
                $descCategory = "no " . $c . " Ano do Ensino Fundamental";
                break;
            case '4':
                $descCategory = "no " . $c . " Ano do Ensino Médio";
                break;
        }

        $response = array(
            'student' => $data,
            'modality' => $modality,
            'descCategory' => $descCategory
        );

        return $response;
    }

}

    Código 02:
    <?php

require_once __DIR__.'/../repository/FormsRepository.php';

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
        $result = $repository->getEnrollmentDeclarationInformation($enrollment_id);

        echo CJSON::encode($result);
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
        $result = $repository->getTransferRequirementInformation($enrollment_id);

        echo CJSON::encode($result);
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
        $result = $repository->getEnrollmentNotificationInformation($enrollment_id);

        echo CJSON::encode($result);
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
        $result = $repository->getStudentsFileInformation($enrollment_id);

        echo CJSON::encode($result);
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
        $result = $repository->getTransferFormInformation($enrollment_id);

        echo CJSON::encode($result);
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

mais especificamente essa parte:

    public function actionStatementAttended($enrollment_id) {
        $this->layout = "reports";
        $repository = new FormsRepository;
        $query = $repository->getStatementAttended($enrollment_id);
        $this->render('StudentStatementAttended', $query);
    }

    Código 03:
    <?php
/* @var $this ReportsController */
/* @var $report Mixed */
/* @var $school SchoolIdentification*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsDeclarationReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="pageA4V">
    <?php $this->renderPartial('head'); ?>
    <div id="report" style="font-size: 14px">
        <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
            <br><br/><br/><br/>
            <div id="report_type_container" style="text-align: center">
                <span id="report_type_label" style="font-size: 16px">DECLARAÇÃO</span>
            </div>
            <br><br><br/>
            <p class="text-indent">Declaro para os devidos fins de direito e comprovação que o(a) aluno(a) <?= $student['name_student'] ?>, nascido(a) em <?= $student['birthday'] ?>,
            filho(a) de <?= $student['filiation_1'] ?> e <?= $student['filiation_2'] ?>, cursou neste estabelecimento de ensino <?= $descCategory ?>
            na modalidade <?= $modality[$student['modality']] ?> no ano letivo de <?= $student['school_year'] ?>.</p>
            <br><br>
            <p class="text-center"><strong>Situação do(a) aluno(a):</strong>           <span class="ml-30"> <span class="mr-10">(</span>) Aprovado(a) </span><span class="ml-30"><span class="mr-10">(</span>) Retido(a)</span></p>
            <br><br><br/>
            <p class="text-center">Este documento não contém rasuras e terá validade de 30 (trinta) dias, a contar da data de expedição.</p>
            <br><br><br><br>
            <span class="pull-right">
                <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
            </span>
            <br/><br/><br><br><br>
            <div style="text-align: center">
                <div class="signature">Gestor</div>
            </div>
        </div>
    </div>
    <br/><br/><br/><br/><br/><br/><br/><br/>
    <?php $this->renderPartial('footer'); ?>
</div>
<style>
    .ml-30 {
        margin-left: 30px
    }
    .mr-10 {
        margin-right: 10px;
    }

    .text-center {
        text-align: center;
    }

    .text-indent {
        text-indent: 50px;
    }

    .signature {
        width: 500px;
        border-top: solid 1px #000;
        margin: auto;
    }

    @media screen{
        .pageA4V{width:980px; height:1400px; margin:0 auto;}
        .pageA4H{width:1400px; height:810px; margin:0 auto;}
        #header-report ul#info, #header-report ul#addinfo {
            width: 100%;
            margin: 0;
            display: block;
            overflow: hidden;
        }
    }

    @media print {
        #header-report ul#info, #header-report ul#addinfo {
            width:100%;
            margin: auto;
            display: block;
            text-align: center;
        }

        #report {
            margin: 0 50px 0 100px;
        }

        #report_type_container{
            border-color: white !important;
        }
        #report_type_label{
            border-bottom: 1px solid black !important;
            font-size: 22px !important;
            font-weight: 500;
            font-family: serif;
        }
        table, td, tr, th {
            border-color: black !important;
        }
        .report-table-empty td {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    }
</style>



Mais especificamnete essa parte:
    <p class="text-indent">Declaro para os devidos fins de direito e comprovação que o(a) aluno(a) <?= $student['name_student'] ?>, nascido(a) em <?= $student['birthday'] ?>,
            filho(a) de <?= $student['filiation_1'] ?> e <?= $student['filiation_2'] ?>, cursou neste estabelecimento de ensino <?= $descCategory ?>
            na modalidade <?= $modality[$student['modality']] ?> no ano letivo de <?= $student['school_year'] ?>.</p>
            <br><br>

Preciso que a partir dessa informações responda minhas perguntas



Eu tenho esse código
codigo 01:
public function getStudentCertificate(/* add aqui*/): array
    {

    }
codigo 02:

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
                    'EnrollmentStatisticsByYearReport','InstructorsPerClassroomReport', 'StudentsFileReport',
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
                    'NumberOfClassesPerSchool', 'NumberOfClassesPerSchool', 'StudentCpfRgNisPerClassroom','FoodMenu', 'StudentCertificate'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionStudentCertificate()
    {
        $repository = new ReportsRepository;
        $query = $repository->getStudentCertificate();
        $this->render('StudentCertificate', $query);
    }
}


Como faço para modificar o código para receber o id do aluno e fazer a mesma coisa que o código mostrado no chat anterior adicionar as informações do aluno no codiog abaixo:

    
<div class="container-certificate">
<!-- <p>O(A) Diretor(a) da: ____________________________________</p> -->
<p>O(A) Diretor(a) da <?php echo $school->name ?>
no uso de suas atribuições legais, confere o presente Certificado do ___(ano de ensino)___ do ___(tipo de ensino)___ a</p>
<p>filho(a) de ______________________________________</p>
<p>e de ____________________________________________</p>
<p>Nascido(a) em ____ de _______________ de ________, no Município de _______________________________</p>
<p>Estado do _______________________________</p>
</div>



<a class="t-button-secondary mobile-margin" rel="noopener" target="_blank" href="/?r=forms/StatementAttended&amp;type=&amp;enrollment_id=9807">
                                                            <span class="t-icon-printer"></span>
                                                            Declaração de Cursou                                                        </a>