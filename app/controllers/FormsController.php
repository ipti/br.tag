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
        
        if (Yii::app()->user->isGuest){
            $this->redirect(yii::app()->createUrl('site/login'));
        }
        
        $this->year = Yii::app()->user->year;
        
        return true;
    }

    private function compareGradeAndResult($grade, $gradesResult) 
    {
        return $grade->discipline_fk == $gradesResult->discipline_fk && $grade->enrollment_fk == $gradesResult->enrollment_fk;
    }

    private function daysOfCalendarCalculate($classroom_fk, $discipline_id) 
    {
        $days = [];
        $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and discipline_fk = :discipline_fk", ["classroom_fk" => $classroom_fk, ":discipline_fk" => $discipline_id]);
        foreach ($schedules as $schedule) {
            if (!isset($days[$schedule->month])) {
                $days[$schedule->month] = [];
            }
            if (!in_array($schedule->day, $days[$schedule->month])) {
                array_push($days[$schedule->month], $schedule->day);
            }
        }
        return count($days);
    }

    private function separateBaseDisciplines($discipline_id)
    {
        if ($discipline_id == 6 || $discipline_id == 10 || $discipline_id == 11 || $discipline_id == 7 ||
            $discipline_id == 3 || $discipline_id == 5 || $discipline_id == 12 || $discipline_id == 13 || 
            $discipline_id == 26)
        {
            return true;
        }
        return false;
    }

    public function actionEnrollmentGradesReport($enrollment_id) {
        $this->layout = "reports";
        $result = array();
        $baseDisciplines = array();
        $diversifiedDisciplines = array();
        $enrollment = StudentEnrollment::model()->findByPk($enrollment_id);
        $grades = Grade::model()->findAllByAttributes(["enrollment_fk" => $enrollment_id]);
        $gradesResult = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $enrollment_id]);
        $classFaults = ClassFaults::model()->findAllByAttributes(["student_fk" => $enrollment->studentFk->id]);
        $curricularMatrix = CurricularMatrix::model()->findAllByAttributes(["stage_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk]);
        $unities = GradeUnity::model()->findAllByAttributes(["edcenso_stage_vs_modality_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk]);

        // separando os headers
        foreach ($curricularMatrix as $matrix) {
            if($this->separateBaseDisciplines($matrix->discipline_fk)) {
                array_push($baseDisciplines, $matrix->disciplineFk->id);
            }else {
                array_push($diversifiedDisciplines, $matrix->disciplineFk->id);
            }
        }
        $totalDisciplines = array_unique(array_merge($baseDisciplines, $diversifiedDisciplines));


        foreach ($totalDisciplines as $discipline) {
            $mediaExists = false;
            foreach ($gradesResult as $finalMedia) {
                if($finalMedia->disciplineFk->id == $discipline) {
                    $schoolDays = $this->daysOfCalendarCalculate($enrollment->classroomFk->id, $finalMedia->disciplineFk->id);
                    $disciplineMatrix = array_values(array_filter($curricularMatrix, function ($matrix) use ($finalMedia) {
                        return $matrix->discipline_fk == $finalMedia->disciplineFk->id;
                    }));
                    array_push($result, [
                        "discipline_id" => $finalMedia->disciplineFk->id,
                        "discipline_name" => $finalMedia->disciplineFk->name,
                        "final_media" => $finalMedia->final_media,
                        "grades" => array_values(array_filter($grades, function ($grade) use ($finalMedia) {
                            return $this->compareGradeAndResult($grade, $finalMedia);
                        })),
                        "faults" => count(array_filter($classFaults, function ($fault) use ($enrollment, $finalMedia) {
                            return $fault->scheduleFk->discipline_fk == $finalMedia->discipline_fk && $fault->scheduleFk->classroom_fk == $enrollment->classroom_fk;
                        })),
                        "workload" => $disciplineMatrix[0]->workload,
                        "school_days" => $schoolDays,
                    ]);
                    $mediaExists = true;
                    break;
                }
            }

            // O aluno não tem grade results para essa disciplina
            if(!$mediaExists) {
                array_push($result, [
                    "discipline_id" => $discipline,
                    "final_media" => null,
                    "grades" => null,
                    "faults" => null,
                    "workload" => null,
                    "school_days" => null,
                ]);
            }
        }

        // Aqui eu ordeno o array de notas de acordo com a ordem da coluna de disciplinas
        $report = [];
        foreach ($totalDisciplines as $disciplineId) {
            foreach ($result as $item) {
                if ($item['discipline_id'] === $disciplineId) {
                    $report[] = $item;
                    break;
                }
            }
        }

        // echo '<pre>';
        // var_dump($report);
        // echo '</pre>';
        // exit;

        $this->render('EnrollmentGradesReport', array(
            'result' => $report,
            'baseDisciplines' => array_unique($baseDisciplines),
            'diversifiedDisciplines' => array_unique($diversifiedDisciplines),
            'unities' => $unities
        ));
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
        $sql = "SELECT 
                se.id AS enrollment_id,
                si.name AS student_name,
                si.id AS student_id,
                ed.name AS discipline_name,
                ed.id AS discipline_id,
                gr.final_media
                FROM classroom c
                JOIN curricular_matrix cm ON c.edcenso_stage_vs_modality_fk = cm.stage_fk
                JOIN student_enrollment se ON se.classroom_fk = c.id
                JOIN student_identification si ON si.id = se.student_fk
                JOIN edcenso_discipline ed ON cm.discipline_fk = ed.id 
                LEFT JOIN grade_results gr ON gr.enrollment_fk = se.id AND cm.discipline_fk = gr.discipline_fk
                WHERE c.id = ".$id."
                ORDER BY discipline_name;";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        setlocale(LC_ALL, NULL);
        setlocale(LC_ALL, "pt_BR.utf8", "pt_BR", "ptb", "ptb.utf8");
        $time = mktime(0, 0, 0, $result['month']);
        $result['month'] = strftime("%B", $time);

        $classroom = Classroom::model()->findByPk($id);

        $disciplines = Yii::app()->db->createCommand(
            "select ed.id as 'discipline_id', ed.name as 'discipline_name' from curricular_matrix cm 
            join edcenso_discipline ed on ed.id = cm.discipline_fk where stage_fk = :stage_fk and school_year = :year order by ed.name"
        )->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)->bindParam(":year", Yii::app()->user->year)->queryAll();
        
        $students = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classroom->id));

        $grades = [];

        foreach ($result as $r) {
            foreach ($disciplines as $d) {
                if($r['discipline_id'] == $d['discipline_id']) {
                    array_push($grades, array(
                            "discipline_id" => $d['discipline_id'],
                            "discipline_name" => $d['discipline_name'],
                            "student_name" => $r['student_name'],
                            "student_id" => $r['student_id'],
                            "final_media" => $r['final_media']
                        )
                    );
                    break;
                }
            }
        }

        $this->render('AtaSchoolPerformance', array(
            'report' => $result,
            'classroom' => $classroom,
            'students' => $students,
            'disciplines' => $disciplines,
            'grades' => $grades
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