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

    private function compareGradeAndResult($grade, $gradesResult) 
    {
        // faz o casamento entre o grade_result e o grade para ter mais garantia do resultado
        return $grade->discipline_fk == $gradesResult->discipline_fk && $grade->enrollment_fk == $gradesResult->enrollment_fk;
    }

    private function daysOfClassContentsCalculate($classroom_fk, $discipline_id) 
    {
        // calculando o total de aulas ministradas naquela turma na disciplina específica
        $days = ClassContents::model()->findAll(array(
            'join' => 'JOIN course_class cc2 ON t.course_class_fk = cc2.id
                       JOIN course_plan cp ON cc2.course_plan_fk = cp.id
                       JOIN classroom c ON c.edcenso_stage_vs_modality_fk = cp.modality_fk',
            'condition' => 'c.id = :classroomId AND cp.discipline_fk = :disciplineId',
            'params' => array(
                ':classroomId' => $classroom_fk,
                ':disciplineId' => $discipline_id,
            ),
        ));
        return count($days);
    }

    private function schoolDaysCalculate($classroom_fk, $discipline_id) {
        // calculando todos dias letivos no quadro de horário para a turma naquela disciplina
        $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and discipline_fk = :discipline_fk", ["classroom_fk" => $classroom_fk, ":discipline_fk" => $discipline_id]);
        return count($schedules);
    }

    private function separateBaseDisciplines($discipline_id)
    {
        // verifica se a disciplina faz parte da BNCC
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
        $result = array(); // array de notas
        $baseDisciplines = array(); // disciplinas da BNCC
        $diversifiedDisciplines = array(); //disciplinas diversas
        $enrollment = StudentEnrollment::model()->findByPk($enrollment_id);
        $grades = Grade::model()->findAllByAttributes(["enrollment_fk" => $enrollment_id]); // notas do aluno na turma
        $gradesResult = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $enrollment_id]); // medias do aluno na turma
        $classFaults = ClassFaults::model()->findAllByAttributes(["student_fk" => $enrollment->studentFk->id]); // faltas do aluno na turma
        $curricularMatrix = CurricularMatrix::model()->findAllByAttributes(["stage_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk, "school_year" => $enrollment->classroomFk->school_year]); // matriz da turma
        $unities = GradeUnity::model()->findAllByAttributes(["edcenso_stage_vs_modality_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk]); // unidades da turma

        // Aqui eu separo as disciplinas da BNCC das disciplinas diversas para depois montar o cabeçalho 
        foreach ($curricularMatrix as $matrix) {
            if($this->separateBaseDisciplines($matrix->discipline_fk)) { // se for disciplina da BNCC
                array_push($baseDisciplines, $matrix->disciplineFk->id);
            }else { // se for disciplina diversa
                array_push($diversifiedDisciplines, $matrix->disciplineFk->id);
            }
        }

        // Junto todas as disciplinas na ordem do cabeçalho
        $totalDisciplines = array_unique(array_merge($baseDisciplines, $diversifiedDisciplines));


        foreach ($totalDisciplines as $discipline) { // aqui eu monto as notas das disciplinas, faltas, dias letivos e cargas horárias

            $mediaExists = false; // verifica se o aluno tem notas para a disciplina

            // cálculo de dias letivos
            $schoolDays = $this->schoolDaysCalculate($enrollment->classroomFk->id, $discipline);

            // cálculo de aulas dadas
            $totaNumberOfClasses = $this->daysOfClassContentsCalculate($enrollment->classroomFk->id, $discipline);

            // pego o registro de matriz curricular da disciplina para verificar a carga horaria da disciplina
            $disciplineMatrix = array_values(array_filter($curricularMatrix, function ($matrix) use ($discipline) {
                return $matrix->discipline_fk == $discipline;
            }));

            // pegando somente as faltas da disciplina
            $faults = count(array_filter($classFaults, function ($fault) use ($enrollment, $discipline) {
                return $fault->scheduleFk->discipline_fk == $discipline && $fault->scheduleFk->classroom_fk == $enrollment->classroom_fk;
            }));

            foreach ($gradesResult as $finalMedia) {
                // se existe notas para essa disciplina
                if($finalMedia->disciplineFk->id == $discipline) {
                    array_push($result, [
                        "discipline_id" => $finalMedia->disciplineFk->id,
                        "final_media" => $finalMedia->final_media,
                        "grades" => array_values(array_filter($grades, function ($grade) use ($finalMedia) {
                            return $this->compareGradeAndResult($grade, $finalMedia);
                        })),
                        "faults" => $faults,
                        "workload" => $disciplineMatrix[0]->workload,
                        "total_number_of_classes" => $totaNumberOfClasses,
                        "school_days" => $schoolDays,
                    ]);
                    $mediaExists = true;
                    break; // quebro o laço para diminuir a complexidade do algoritmo para O(log n)2
                }
            }

            if(!$mediaExists) { // o aluno não tem notas para a disciplina
                array_push($result, [
                    "discipline_id" => $discipline,
                    "final_media" => null,
                    "grades" => null,
                    "faults" => $faults,
                    "workload" => $disciplineMatrix[0]->workload,
                    "total_number_of_classes" => $totaNumberOfClasses,
                    "school_days" => $schoolDays,
                ]);
            }
        }

        // Aqui eu ordeno o array de notas de acordo com a ordem da coluna de disciplinas
        $report = [];
        foreach ($totalDisciplines as $disciplineId) {
            // eu pego o array de notas para ordenar e garantir que a ordem das notas esteja correta
            foreach ($result as $item) {
                if ($item['discipline_id'] === $disciplineId) {
                    $report[] = $item;
                    break; // quebro o laço para diminuir a complexidade do algoritmo para O(log n)2
                }
            }
        }

        $this->render('EnrollmentGradesReport', array(
            'enrollment' => $enrollment,
            'result' => $report,
            'baseDisciplines' => array_unique($baseDisciplines), //função usada para evitar repetição
            'diversifiedDisciplines' => array_unique($diversifiedDisciplines), //função usada para evitar repetição
            'unities' => $unities
        ));
    }

    private function calcularFrequencia($diasLetivos, $totalFaltas) {
        if ($diasLetivos === 0) {
            return 0; // Evitar divisão por zero
        }
    
        $frequencia = (($diasLetivos - $totalFaltas) / $diasLetivos) * 100;
        return number_format($frequencia, 2);
    }
    
    public function actionIndividualRecord($enrollment_id, $segment = 1)
    {
        $this->layout = "reports";                                             
        $disciplines = array();
        $enrollment = StudentEnrollment::model()->findByPk($enrollment_id);
        $gradesResult = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $enrollment_id]); // medias do aluno na turma
        $curricularMatrix = CurricularMatrix::model()->findAllByAttributes(["stage_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk, "school_year" => $enrollment->classroomFk->school_year]); // matriz da turma
        $schedules = Schedule::model()->findAllByAttributes(["classroom_fk" => $enrollment->classroom_fk]);
        $portuguese = array();
        $history = array();
        $geography = array();
        $mathematics = array();
        $sciences = array();

        $workload = 0;
        foreach ($curricularMatrix as $c) {
            $workload += $c->workload;
        }

        foreach ($curricularMatrix as $c) {
            foreach ($gradesResult as $g) {
                if($c->disciplineFk->id == $g->discipline_fk) {
                    if($c->disciplineFk->id == 6) {
                        array_push($portuguese, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "final_media" => $g->final_media
                        ]);
                    }else if ($c->disciplineFk->id == 12) {
                        array_push($history, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "final_media" => $g->final_media
                        ]);
                    }else if ($c->disciplineFk->id == 13) {
                        array_push($geography, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "final_media" => $g->final_media
                        ]);
                    }else if ($c->disciplineFk->id == 3) {
                        array_push($mathematics, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "final_media" => $g->final_media
                        ]);
                    }else if ($c->disciplineFk->id == 5) {
                        array_push($sciences, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "final_media" => $g->final_media
                        ]);
                    }
                }
            } 
        }

        foreach ($curricularMatrix as $c) {
            foreach ($gradesResult as $g) {
                if($c->disciplineFk->id == $g->discipline_fk) {
                    
                    if($c->disciplineFk->id != 6 && $c->disciplineFk->id != 12 && 
                        $c->disciplineFk->id != 13 && $c->disciplineFk->id != 3 && 
                        $c->disciplineFk->id != 5 ) {
                        array_push($disciplines, [
                            "name" => $c->disciplineFk->name,
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "final_media" => $g->final_media
                        ]);
                    }
                }
            }
        }

        $totalFaults = 0;
        foreach ($disciplines as $d) {
            $totalFaults += $d["faults1"] + $d["faults2"] + $d["faults3"] + $d["faults4"];
        }

        $totalFaults += $portuguese[0]["faults1"] + $portuguese[0]["faults2"] + $portuguese[0]["faults3"] + $portuguese[0]["faults4"];
        $totalFaults += $history[0]["faults1"] + $history[0]["faults2"] + $history[0]["faults3"] + $history[0]["faults4"];
        $totalFaults += $geography[0]["faults1"] + $geography[0]["faults2"] + $geography[0]["faults3"] + $geography[0]["faults4"];
        $totalFaults += $mathematics[0]["faults1"] + $mathematics[0]["faults2"] + $mathematics[0]["faults3"] + $mathematics[0]["faults4"];
        $totalFaults += $sciences[0]["faults1"] + $sciences[0]["faults2"] + $sciences[0]["faults3"] + $sciences[0]["faults4"];

        $frequency = $this->calcularFrequencia($workload, $totalFaults);

        $this->render('IndividualRecord', array(
            'enrollment' => $enrollment,
            'disciplines' => $disciplines,
            'portuguese' => $portuguese,
            'history' => $history,
            'geography' => $geography,
            'mathematics' => $mathematics,
            'sciences' => $sciences,
            'workload' => $workload,
            'schedules' => $schedules,
            'frequency' => $frequency,
            'segment' => $segment
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
        $enrollment = StudentEnrollment::model()->with("studentFk.edcensoUfFk")->findByPk($enrollment_id);
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        $this->layout = "reports";
        $this->render('StudentFileForm', array(
            'enrollment' => $enrollment,
            'school' => $school, 
        ));
    }

    public function actionStudentsFileForm($classroom_id) {
        $this->layout = "reports";
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $classroom = Classroom::model()->with("studentEnrollments.studentFk.edcensoUfFk")->findByPk($classroom_id);
        $enrollments = $classroom->studentEnrollments;
        $this->render('StudentsFileForm', array(
            'classroom_id' => $classroom_id,
            'enrollments' => $enrollments,
            'school' => $school 
        ));
    }

    public function actionTransferForm($enrollment_id){
        $this->layout = 'reports';
        $sql = "SELECT si.nationality FROM student_identification si JOIN student_enrollment se ON se.student_fk = si.id WHERE se.id = " . $enrollment_id . ";";
        $result = Yii::app()->db->createCommand($sql)->queryRow();
        $this->render('TransferForm', array(
            'enrollment_id'=>$enrollment_id, 
            'nationality'=>$result['nationality']
        ));
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