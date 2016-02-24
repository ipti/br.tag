<?php

class ReportsController extends Controller {

    public $layout = 'fullmenu';
    public $year = 0;

    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'BFReport', 'numberStudentsPerClassroomReport',
                                    'InstructorsPerClassroomReport','StudentsFileReport','StudentsFileBoquimReport',
                                    'getStudentsFileInformation', 'ResultBoardReport',
                                    'StatisticalDataReport', 'StudentsDeclarationReport',
                                    'EnrollmentPerClassroomReport','AtaSchoolPerformance',
                                    'EnrollmentDeclarationReport', 'TransferForm',
                                    'EnrollmentNotification', 'TransferRequirement', 'EnrollmentGradesReport'),
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

    public function actionAtaSchoolPerformance($id) {
        $sql = "SELECT * FROM ataPerformance
                    where `year`  = " . $this->year . ""
                . " AND classroom_id = $id;";

        $result = Yii::app()->db->createCommand($sql)->queryRow();
        setlocale(LC_ALL, NULL);
        setlocale(LC_ALL, "pt_BR.utf8", "pt_BR", "ptb", "ptb.utf8");
        $time = mktime(0, 0, 0, $result['month']);
        $result['month'] = strftime("%B", $time);

        $classroom = Classroom::model()->findByPk($id);
        $students = StudentEnrollment::model()->findAll('classroom_fk = '.$classroom->id);

        $this->render('AtaSchoolPerformance', array(
            'report' => $result,
            'classroom' => $classroom,
            'students' => $students
        ));
    }

    public function actionGetEnrollmentPerClassroomInformation($cid){
        $sql = "SELECT * FROM classroom_enrollment where `year`  = " . $this->year . " AND classroom_id = " . $cid . " ORDER BY name;";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        echo json_encode($result);
    }

    public function actionEnrollmentPerClassroomReport($id){
        $this->layout = "reports";
        $sql = "SELECT * FROM classroom_enrollment
                    where `year`  = ".$this->year.""
            . " AND classroom_id = $id"
            . " ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $classroom = Classroom::model()->findByPk($id);

        $this->render('EnrollmentPerClassroomReport', array(
            'report' => $result,
            'classroom' => $classroom
        ));
    }

    public function actionStudentPerClassroom($cid){
        $this->layout = "reports";
        $sql = "SELECT * FROM classroom_enrollment
                    where `year`  = ".$this->year.""
            . " AND classroom_id = $cid"
            . " ORDER BY name;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $classroom = Classroom::model()->findByPk($cid);

        $this->render('StudentPerClassroom', array(
            'report' => $result,
            'classroom' => $classroom
        ));
    }

    public function actionStatisticalDataReport(){
        $result = null;
        $this->render('StatisticalDataReport', array(
            'report' => $result,
        ));
    }

    public function actionResultBoardReport(){
        $result = null;
        $this->render('ResultBoardReport', array(
            'report' => $result,
        ));
    }

    public function actionNumberStudentsPerClassroomReport() {
        $sql = "SELECT * FROM classroom_qtd_students
                    where school_year  = ".$this->year." and school_inep_fk=".Yii::app()->user->school." order by name;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('NumberStudentsPerClassroomReport', array(
            'report' => $result,
        ));
    }

    public function actionInstructorsPerClassroomReport() {
        $sql = "SELECT * FROM classroom_instructors "
                . "where school_year = ".$this->year." and school_inep_fk= ".Yii::app()->user->school." order by name;";

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('InstructorsPerClassroomReport', array(
            'report' => $result,
        ));
    }

    public function actionStudentsDeclarationReport($id) {
        $sql = "SELECT * FROM StudentsDeclaration WHERE student_id = ".$id." AND `year`  = ".$this->year.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('StudentsDeclarationReport', array(
            'report' => $result
        ));
    }

    public function actionStudentsFileReport() {
        $this->render('StudentsFileReport', array());
    }
    
    public function actionGetStudentsFileBoquimInformation($enrollment_id){
        $sql = "SELECT * FROM studentsfile_boquim WHERE enrollment_id = ".$enrollment_id.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        echo json_encode($result);
    }


    public function actionEnrollmentGradesReport($enrollment_id) {
        $this->layout = "reports";
        $enrollment = StudentEnrollment::model()->findByPk($enrollment_id);
        $this->render('EnrollmentGradesReport', array('enrollment'=>$enrollment));
    }

    public function actionStudentsFileBoquimReport($enrollment_id) {
        $this->layout = "reports";
        $this->render('StudentsFileBoquimReport', array('enrollment_id'=>$enrollment_id));
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
        $sql = "SELECT si.name name, si.mother_name mother, si.father_name father, si.birthday birthday, si.inep_id inep_id, si.nis nis, ec.name city, c.school_year enrollment_date"
                . " FROM student_enrollment se JOIN classroom c ON(c.id=se.classroom_fk) JOIN student_identification si ON si.id = se.student_fk JOIN student_documents_and_address sd ON si.id = sd.id JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id"
                . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        echo json_encode($result);
    }

    public function actionGetStudentsFileInformation($student_id){
        $sql = "SELECT * FROM StudentsFile WHERE id = ".$student_id.";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        echo json_encode($result);
    }

    public function actionBFReport() {
        //@done s3 - Verificar se a frequencia dos últimos 3 meses foi adicionada(existe pelo menso 1 class cadastrado no mês)
        //@done S3 - Selecionar todas as aulas de todas as turmas ativas dos ultimos 3 meses
        //@done s3 - Pegar todos os alunos matriculados nas turmas atuais.
        //@done s3 - Enviar dados pre-processados para a página.
        $month = (int)date('m');
        $monthI = $month <= 3 ? 1 : $month-3;
        $monthF = $month <= 1 ? 1 : $month-1;
        $year = date('Y');
        /*
        select c.name classroom, si.name student, si.nis nis, si.birthday, t.month, count(*) count , cf.faults
        from class t
        left join classroom c on c.id = t.classroom_fk
        left join student_enrollment se on se.classroom_fk = t.classroom_fk
        left join student_identification si on se.student_fk = si.id
        left join (
            SELECT class.classroom_fk, class.month, student_fk, count(*) faults
            FROM class_faults cf
            left join class on class.id = class_fk
            group by student_fk, class.month, class.classroom_fk) cf
        on (c.id = cf.classroom_fk AND se.student_fk = cf.student_fk AND cf.month = t.month)
        where c.school_year = 2013
            AND t.month >= 1
            AND t.month <= 3
            AND t.given_class = 1
        group by c.id, t.month, si.id
        order by student;
         */

        $command = Yii::app()->db->createCommand();
        //day é um armengo, se colocar colunas que não estão na tabela o count não aparece na array
        $command->select = 'c.name classroom, si.name student, si.nis nis, si.birthday, t.month, count(*) count , cf.faults ';
        $command->from = 'class t ';
        $command->join  ='left join classroom c on c.id = t.classroom_fk ';
        $command->join .='left join student_enrollment se on se.classroom_fk = t.classroom_fk ';
        $command->join .='left join student_identification si on se.student_fk = si.id ';
        $command->join .='left join (
            SELECT class.classroom_fk, class.month, student_fk, count(*) faults 
            FROM class_faults cf
            left join class on class.id = class_fk
            group by student_fk, class.month,class.classroom_fk) cf 
        on (c.id = cf.classroom_fk AND se.student_fk = cf.student_fk AND cf.month = t.month) ';
        $command->where('c.school_year = :year '
                . 'AND t.month >= :monthI '
                . 'AND t.month <= :monthF '
                . 'AND t.given_class = 1 ',//0 não, 1 sim
                array(":year" => $year, ":monthI" => $monthI, ":monthF" => $monthF));
        $command->group = "c.id, t.month, si.id";
        $command->order = "student, month";
        $query = $command->queryAll();

        //@done S3 - Organizar o resultado da query que estava ilegível.
        $report = array();
        foreach ($query as $v) {
            $classroom  = $v['classroom'];
            $student    = $v['student'];
            $month      = $v['month'];
            $birthday   = $v['birthday'];
            $nis        = isset($v['nis'])          ? $v['nis']         : "Não Informado";
            $count      = isset($v['count'])        ? $v['count']       : 0;
            $faults     = isset($v['faults'])       ? $v['faults']      : 0;

            //$report[$student]['Classes'][$month] = $faults/$count or N/A
            //@done s3 - Calcular frequência para cada aluno: (Total de horários - faltas do aluno) / (Total de horários - Dias não ministrados)

            $report[$student]['Classes'][$month]  =
                        ($count == 0)   //Se Count for 0, então não houveram aulas cadastradas
                        ? ('N/A')       //Assim atribuimos N/A
                        : (floor(
                                (($count-$faults)/$count)*100   //Calcula a %
                                *100    //Multiplica por 100, para guardar 2 casas decimais
                            )/100       //Efetua o truncamento e divide por 100 para colocar as casas decimais em seus devidos lugares
                            )."%";      //coloca o sinal de % no final

            $report[$student]['Info']['Classroom']  = $classroom;
            $report[$student]['Info']['NIS']        = $nis;
            $report[$student]['Info']['birthday']   = $birthday;
        }

        //Se não houver aulas no mês, coloca 0 no lugar.
        foreach ($report as $name => $c){
            for ($i = $monthI; $i <= $monthF; $i++) {
                $report[$name]['Classes'][$i] = isset($c['Classes'][$i]) ? $c['Classes'][$i] : ('N/A');
            }
        }

        $this->render('BFReport', array(
            'report' => $report,
        ));
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
                    si.father_name father, si.mother_name mother
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

    public function actionTransferRequirement($enrollment_id){
        $this->layout = 'reports';
        $sql = "SELECT si.sex gender, svm.stage stage, svm.id class"
                . " FROM student_identification si JOIN student_enrollment se ON se.student_fk = si.id JOIN classroom c on se.classroom_fk = c.id JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id"
                . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('TransferRequirement', array('enrollment_id'=>$enrollment_id, 'gender'=>$result['gender'], 'stage'=>$result['stage'], 'class'=>$result['class']));
    }

    public function actionGetTransferRequirementInformation($enrollment_id){
        $sql = "SELECT si.name name, si.mother_name mother, si.father_name father, si.birthday birthday, ec.name city, euf.acronym state, YEAR(se.create_date) enrollment_date"
                . " FROM student_enrollment se JOIN student_identification si ON si.id = se.student_fk JOIN student_documents_and_address sd ON si.id = sd.id JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id JOIN edcenso_uf euf ON si.edcenso_uf_fk = euf.id"
                . " WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        echo json_encode($result);
    }

    public function actionIndex() {
        $this->render('index');
    }

}
