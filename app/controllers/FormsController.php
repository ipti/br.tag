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
                    'TransferForm','GetTransferFormInformation', 'StudentStatementAttended'),
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
                    where `school_year` = " . $this->year . ""
            . " AND classroom_id = $id  AND (status = 1 OR status IS NULL);";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        setlocale(LC_ALL, NULL);
        setlocale(LC_ALL, "pt_BR.utf8", "pt_BR", "ptb", "ptb.utf8");
        $time = mktime(0, 0, 0, $result['month']);
        $result['month'] = strftime("%B", $time);

        $disciplines = Yii::app()->db->createCommand(
            "select * from ((select c.`id` as 'classroom_id', d.id as 'discipline_id', d.`name` as 'discipline_name'

                from `edcenso_discipline` as `d`
                JOIN `instructor_teaching_data` `t` ON 
                        (`t`.`discipline_1_fk` = `d`.`id` 
                        || `t`.`discipline_2_fk` = `d`.`id` 
                        || `t`.`discipline_3_fk` = `d`.`id`
                        || `t`.`discipline_4_fk` = `d`.`id`
                        || `t`.`discipline_5_fk` = `d`.`id`
                        || `t`.`discipline_6_fk` = `d`.`id`
                        || `t`.`discipline_7_fk` = `d`.`id`
                        || `t`.`discipline_8_fk` = `d`.`id`
                        || `t`.`discipline_9_fk` = `d`.`id`
                        || `t`.`discipline_10_fk` = `d`.`id`
                        || `t`.`discipline_11_fk` = `d`.`id`
                        || `t`.`discipline_12_fk` = `d`.`id`
                        || `t`.`discipline_13_fk` = `d`.`id`)
                join `classroom` as `c` on (c.id = t.classroom_id_fk)
            ) union (
                select c.`id` as 'classroom_id', d.id as 'discipline_id', d.`name` as 'discipline_name'
                from `classroom` as `c`
                        join `class_board` as `cb` on (c.id = cb.classroom_fk)
                        join `edcenso_discipline` as `d` on (d.id = cb.discipline_fk)
            )) as classroom_disciplines
            where classroom_id = " . $id)->queryAll();

        $classroom = Classroom::model()->findByPk($id);

        foreach($disciplines as $key => $value){
            if($value == 1){
                $enableDisciplines[$key] = $disciplineLabels[$key];
            }
        }
        
        $students = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classroom->id));

        $this->render('AtaSchoolPerformance', array(
            'report' => $result,
            'classroom' => $classroom,
            'students' => $students,
            'disciplines' => $disciplines
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

    public function actionStatementAttended($enrollment_id) {

        $this->layout = "reports";
        $sql = "SELECT si.name name_student, si.birthday, si.filiation_1, si.filiation_2, svm.name class, svm.*, c.modality, c.school_year, svm.stage stage, svm.id class"
            . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk JOIN classroom c on se.classroom_fk = c.id JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id"
            . " WHERE se.id = " . $enrollment_id . ";";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        
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
        $this->render('StudentStatementAttended', array(
            'student' => $data,
            'modality' => $modality,
            'descCategory' => $descCategory
        ));
    }

    public function actionWarningTerm($enrollment_id) {

        $this->layout = "reports";
        $sql = "SELECT si.name name_student, si.birthday, si.filiation_1, si.filiation_2, svm.name class, svm.*, c.modality, c.school_year, svm.stage stage, svm.id class"
            . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk JOIN classroom c on se.classroom_fk = c.id JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id"
            . " WHERE se.id = " . $enrollment_id . ";";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        
        $sql = "SELECT * FROM studentsdeclaration WHERE enrollment_id = ".$enrollment_id;
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        
        $turn = '';
        switch ($data['turn']) {
            case 'M':
                $turn = 'M';
                break;
            case 'T':
                $turn = 'Vespertino';
                break;
            case 'N':
                $turn = 'Noturno';
                break;
            
        }
        
        $this->render('WarningTerm', array(
            'student' => $data,
            'turn' => $turn
        ));
    }

    public function actionIndex() {
        $this->render('index');
    }

}