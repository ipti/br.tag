<?php

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
                    'TransferForm','GetTransferFormInformation'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function beforeAction($action){
        $this->year = Yii::app()->user->year;
        return true;
    }

    public function actionEnrollmentGradesReport($enrollment_id) {
        $this->layout = "reports";
        $enrollment = StudentEnrollment::model()->findByPk($enrollment_id);
        $this->render('EnrollmentGradesReport', array('enrollment'=>$enrollment));
    }
    public function actionEnrollmentGradesReportBoquim($enrollment_id) {
        $this->layout = "reports";
        $enrollment = StudentEnrollment::model()->findByPk($enrollment_id);
        $this->render('EnrollmentGradesReportBoquim', array('enrollment'=>$enrollment));
    }
    public function actionEnrollmentGradesReportBoquimCiclo($enrollment_id) {
        $this->layout = "reports";
        $enrollment = StudentEnrollment::model()->findByPk($enrollment_id);
        $this->render('EnrollmentGradesReportBoquimCiclo', array('enrollment'=>$enrollment));
    }

    public function actionStudentsFileReport($enrollment_id) {
        $this->layout = "reports";
        $this->render('StudentsFileReport', array('enrollment_id'=>$enrollment_id));
    }

    public function actionEnrollmentDeclarationReport($enrollment_id) {
        $this->layout = "reports";
        $sql = "SELECT si.sex gender, svm.stage stage, svm.id class"
            . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk JOIN classroom c on se.classroom_fk = c.id JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id"
            . " WHERE se.id = " . $enrollment_id . ";";
        $response = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('EnrollmentDeclarationReport', array('enrollment_id'=>$enrollment_id, 'gender'=>$response['gender'], 'stage'=>$response['stage'], 'class'=>$response['class']));
    }




    public function actionGetEnrollmentDeclarationInformation($enrollment_id){
        $sql = "SELECT si.name name, si.filiation_1 filiation_1, si.filiation_2 filiation_2, si.birthday birthday, si.inep_id inep_id, sd.nis nis, ec.name city, c.school_year enrollment_date"
            . " FROM student_enrollment se JOIN classroom c ON c.id = se.classroom_fk JOIN student_identification si ON si.id = se.student_fk JOIN student_documents_and_address sd ON si.id = sd.id JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id"
            . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        echo json_encode($result);
    }

    public function actionTransferRequirement($enrollment_id){
        $this->layout = 'reports';
        $sql = "SELECT si.sex gender, svm.stage stage, svm.id class"
            . " FROM student_identification si JOIN student_enrollment se ON se.student_fk = si.id JOIN classroom c on se.classroom_fk = c.id JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id"
            . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('TransferRequirement', array('enrollment_id'=>$enrollment_id, 'gender'=>$result['gender'], 'stage'=>$result['stage'], 'class'=>$result['class']));
    }

    public function actionGetTransferRequirementInformation($enrollment_id){
        $sql = "SELECT si.name name, si.filiation_1 mother, si.filiation_2 father, si.birthday birthday, ec.name city, euf.acronym state, YEAR(se.create_date) enrollment_date"
            . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk JOIN student_documents_and_address sd ON si.id = sd.id JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id JOIN edcenso_uf euf ON si.edcenso_uf_fk = euf.id"
            . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        echo json_encode($result);
    }

    public function actionEnrollmentNotification($enrollment_id){
        $this->layout = 'reports';
        $sql = "SELECT si.sex gender, cr.turn shift"
            . " FROM student_identification si JOIN student_enrollment se ON se.student_fk = si.id JOIN classroom cr ON se.classroom_fk = cr.id"
            . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('EnrollmentNotification', array('enrollment_id'=>$enrollment_id, 'gender'=>$result['gender'], 'shift'=>$result['shift']));
    }

    public function actionGetEnrollmentNotificationInformation($enrollment_id){
        $sql = "SELECT si.name name, YEAR(se.create_date) enrollment_date"
            . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk"
            . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        echo json_encode($result);
    }

    public function actionStudentsDeclarationReport($enrollment_id) {

        $this->layout = "reports";
        $sql = "SELECT * FROM studentsdeclaration WHERE enrollment_id = ".$enrollment_id;
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('StudentsDeclarationReport', array(
            'report' => $result
        ));
    }

    public function actionGetStudentsFileInformation($enrollment_id){
        $sql = "SELECT * FROM studentsfile WHERE enrollment_id = ".$enrollment_id.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        echo json_encode($result);
    }

    public function actionAtaSchoolPerformance($id) {
        $this->layout = "reports";
        $sql = "SELECT * FROM ata_performance
                    where `year`  = " . $this->year . ""
            . " AND classroom_id = $id;";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        setlocale(LC_ALL, NULL);
        setlocale(LC_ALL, "pt_BR.utf8", "pt_BR", "ptb", "ptb.utf8");
        $time = mktime(0, 0, 0, $result['month']);
        $result['month'] = strftime("%B", $time);

        $classroom = Classroom::model()->findByPk($id);
        $students = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classroom->id));

        $this->render('AtaSchoolPerformance', array(
            'report' => $result,
            'classroom' => $classroom,
            'students' => $students
        ));
    }

    public function actionStudentFileForm($enrollment_id) {
        $this->layout = "reports";
        $this->render('StudentFileForm', array('enrollment_id'=>$enrollment_id,'studentinfo'=>$result));
    }

    public function actionStudentsFileForm($classroom_id) {
        $this->layout = "reports";
        $this->render('StudentsFileForm', array('classroom_id'=>$classroom_id));
    }

    public function actionTransferForm($enrollment_id){
        $this->layout = 'reports';
        $sql = "SELECT si.nationality FROM student_identification si JOIN student_enrollment se ON se.student_fk = si.id WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('TransferForm', array('enrollment_id'=>$enrollment_id, 'nationality'=>$result['nationality']));
    }

    public function actionGetTransferFormInformation($enrollment_id){
        //nacionalidade vem de que tabela?
        $sql = "SELECT si.name, si.inep_id, ec.name birth_city, euf.acronym birth_state,  
                    si.birthday, sda.rg_number, sda.rg_number_expediction_date rg_date, 
                    eoe.name rg_emitter, euf.acronym rg_uf, 
                    sda.civil_certification_term_number civil_certification, sda.civil_certification_sheet, sda.civil_certification_book, 
                    ec.name civil_certification_city, euf.acronym civil_certification_uf, 
                    si.filiation_2 filiation_2, si.filiation_1 filiation_1
                FROM student_identification si
                    JOIN student_enrollment se ON se.student_fk = si.id
                    JOIN student_documents_and_address sda ON sda.student_fk = si.inep_id
                    JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id
                    JOIN edcenso_uf euf ON si.edcenso_uf_fk = euf.id
                    JOIN edcenso_nation en ON si.edcenso_nation_fk = en.id
                    JOIN edcenso_organ_id_emitter eoe ON eoe.id = sda.rg_number_edcenso_organ_id_emitter_fk
                WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        echo json_encode($result);
    }

    public function actionIndex() {
        $this->render('index');
    }

}