<?php

class FormsRepository {

    private $currentSchool;
    private $currentYear;

    public function __construct()
    {
        $this->currentSchool = Yii::app()->user->school;
        $this->currentYear = Yii::app()->user->year;
    }

    private function compareGradeAndResult($grade, $gradesResult) : bool
    {
        // faz o casamento entre o grade_result e o grade para ter mais garantia do resultado
        return $grade->discipline_fk == $gradesResult->discipline_fk && $grade->enrollment_fk == $gradesResult->enrollment_fk;
    }

    private function daysOfClassContentsCalculate($classroomFk, $disciplineId) : int
    {
        // calculando o total de aulas ministradas naquela turma na disciplina específica
        $days = ClassContents::model()->findAll(array(
            'join' => 'JOIN course_class cc2 ON t.course_class_fk = cc2.id
                       JOIN course_plan cp ON cc2.course_plan_fk = cp.id
                       JOIN classroom c ON c.edcenso_stage_vs_modality_fk = cp.modality_fk',
            'condition' => 'c.id = :classroomId AND cp.discipline_fk = :disciplineId',
            'params' => array(
                ':classroomId' => $classroomFk,
                ':disciplineId' => $disciplineId,
            ),
        ));
        return count($days);
    }

    private function schoolDaysCalculate($classroomFk, $disciplineId) 
    {
        // calculando todos dias letivos no quadro de horário para a turma naquela disciplina
        $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and discipline_fk = :discipline_fk", ["classroom_fk" => $classroomFk, ":discipline_fk" => $disciplineId]);
        return count($schedules);
    }

    private function separateBaseDisciplines($disciplineId)
    {
        // verifica se a disciplina faz parte da BNCC
        if ($disciplineId == 6 || $disciplineId == 10 || $disciplineId == 11 || $disciplineId == 7 ||
            $disciplineId == 3 || $disciplineId == 5 || $disciplineId == 12 || $disciplineId == 13 || 
            $disciplineId == 26)
        {
            return true;
        }
        return false;
    }

    /**
     * Rendimento Escolar por Atividades
     */
    public function getEnrollmentGrades($enrollmentId) : array
    {
        $result = array(); // array de notas
        $baseDisciplines = array(); // disciplinas da BNCC
        $diversifiedDisciplines = array(); //disciplinas diversas
        $enrollment = StudentEnrollment::model()->findByPk($enrollmentId);
        $grades = Grade::model()->findAllByAttributes(["enrollment_fk" => $enrollmentId]); // notas do aluno na turma
        $gradesResult = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $enrollmentId]); // medias do aluno na turma
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

        $response = array(
            'enrollment' => $enrollment,
            'result' => $report,
            'baseDisciplines' => array_unique($baseDisciplines), //função usada para evitar repetição
            'diversifiedDisciplines' => array_unique($diversifiedDisciplines), //função usada para evitar repetição
            'unities' => $unities
        );

        return $response;
    }

    private function calculateFrequency($diasLetivos, $totalFaltas) : float 
    {
        if ($diasLetivos === 0) {
            return 0; // Evitar divisão por zero
        }
    
        $frequencia = (($diasLetivos - $totalFaltas) / $diasLetivos) * 100;
        return number_format($frequencia, 2);
    }

    /**
     * Ficha Individual
     */
    public function getIndividualRecord($enrollmentId) : array 
    {
        $disciplines = array();
        $enrollment = StudentEnrollment::model()->findByPk($enrollmentId);
        $gradesResult = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $enrollmentId]); // medias do aluno na turma
        $curricularMatrix = CurricularMatrix::model()->findAllByAttributes(["stage_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk, "school_year" => $enrollment->classroomFk->school_year]); // matriz da turma
        $schedules = Schedule::model()->findAllByAttributes(["classroom_fk" => $enrollment->classroom_fk]);
        $gradeRules = GradeRules::model()->findByAttributes(["edcenso_stage_vs_modality_fk" => $enrollment->classroomFk->edcensoStageVsModalityFk->id]);
        $portuguese = array();
        $history = array();
        $geography = array();
        $mathematics = array();
        $sciences = array();

        $stage = isset($enrollment->edcenso_stage_vs_modality_fk) ? $enrollment->edcenso_stage_vs_modality_fk : $enrollment->classroomFk->edcenso_stage_vs_modality_fk;

        $minorFundamental = Yii::app()->utils->isStageMinorEducation($stage);

        $workload = 0;
        foreach ($curricularMatrix as $c) {
            $workload += $c->workload;
        }

        foreach ($curricularMatrix as $c) {
            foreach ($gradesResult as $g) {
                if($c->disciplineFk->id == $g->discipline_fk) {
                    if($c->disciplineFk->id == 6 && $minorFundamental) {
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
                    }else if ($c->disciplineFk->id == 12 && $minorFundamental) {
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
                    }else if ($c->disciplineFk->id == 13 && $minorFundamental) {
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
                    }else if ($c->disciplineFk->id == 3 && $minorFundamental) {
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
                    }else if ($c->disciplineFk->id == 5 && $minorFundamental) {
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
                    }else {
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

        $frequency = $this->calculateFrequency($workload, $totalFaults);

        $response = array(
            'gradeRules' => $gradeRules,
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
            'minorFundamental' => $minorFundamental
        );

        return $response;
    }

    /**
     * Relatório de Notas de Boquim
     */
    public function getEnrollmentGradesBoquim($enrollmentId) : array
    {
        $enrollment = StudentEnrollment::model()->findByPk($enrollmentId);
        $response = array('enrollment' => $enrollment);
        return $response;
    }

    /**
     * Relatório de Notas de Boquim (Ciclo)
     */
    public function getEnrollmentGradesBoquimCiclo($enrollmentId) : array
    {
        $enrollment = StudentEnrollment::model()->findByPk($enrollmentId);
        $response = array('enrollment'=>$enrollment);
        return $response;
    }

    /**
     * Declaração de Matrícula
     */
    public function getEnrollmentDeclaration($enrollmentId) : array
    {
        $sql = "SELECT si.sex gender, svm.stage stage, svm.id class 
                    FROM student_enrollment se
                JOIN student_identification si ON si.id = se.student_fk 
                JOIN classroom c ON se.classroom_fk = c.id 
                JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id
                WHERE se.id = :enrollment_id;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        $response = array('enrollment_id' => $enrollmentId, 'gender' => $result['gender'], 
                        'stage' => $result['stage'], 'class' => $result['class']);
        return $response;
    }

    /**
     * Carrega informações da declaração de matrícula
     */
    public function getEnrollmentDeclarationInformation($enrollmentId) : void
    {
        $sql = "SELECT si.name name, si.filiation_1 filiation_1, si.filiation_2 
                    filiation_2, si.birthday birthday, si.inep_id inep_id, 
                    sd.nis nis, ec.name city, c.school_year enrollment_date
                    FROM student_enrollment se 
                JOIN classroom c ON c.id = se.classroom_fk 
                JOIN student_identification si ON si.id = se.student_fk 
                JOIN student_documents_and_address sd ON si.id = sd.id 
                JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id
                WHERE se.id = :enrollment_id;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        echo json_encode($result);
    }

    /**
     * Requerimento de Transferência
     */
    public function getTransferRequirement($enrollmentId) : array
    {
        $sql = "SELECT si.sex gender, svm.stage stage, svm.id class
                    FROM student_identification si 
                JOIN student_enrollment se ON se.student_fk = si.id 
                JOIN classroom c on se.classroom_fk = c.id 
                JOIN edcenso_stage_vs_modality svm ON c.edcenso_stage_vs_modality_fk = svm.id
                WHERE se.id = :enrollment_id;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        $response = array('enrollment_id' => $enrollmentId, 'gender' => $result['gender'], 
                        'stage' => $result['stage'], 'class' => $result['class']);

        return $response;
    }

    /**
     * Carrega informações do Requerimento de Transferência
     */
    public function getTransferRequirementInformation($enrollmentId) : void
    {
        $sql = "SELECT si.name name, si.filiation_1 mother, si.filiation_2 father, si.birthday birthday, ec.name city, euf.acronym state, YEAR(se.create_date) enrollment_date
                    FROM student_enrollment se 
                JOIN student_identification si ON si.id = se.student_fk 
                JOIN student_documents_and_address sd ON si.id = sd.id 
                JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id 
                JOIN edcenso_uf euf ON si.edcenso_uf_fk = euf.id
                WHERE se.id = :enrollment_id;";
            
        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        echo json_encode($result);
    }

    /**
     * Comunicado de Matrícula
     */
    public function getEnrollmentNotification($enrollmentId) : array
    {
        $sql = "SELECT si.sex gender, cr.turn shift
                    FROM student_identification si 
                JOIN student_enrollment se ON se.student_fk = si.id 
                JOIN classroom cr ON se.classroom_fk = cr.id
                WHERE se.id = :enrollment_id;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        $response = array('enrollment_id' => $enrollmentId, 'gender' => $result['gender'], 'shift' => $result['shift']);

        return $response;
    }

    /**
     * Carrega informações do Comunicado de Matrícula
     */
    public function getEnrollmentNotificationInformation($enrollmentId) : void
    {
        $sql = "SELECT si.name name, YEAR(se.create_date) enrollment_date
                    FROM student_enrollment se 
                JOIN student_identification si ON si.id = se.student_fk
                WHERE se.id = :enrollment_id;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        echo json_encode($result);
    }

    /**
     * Declaração de Aluno
    */
    public function getStudentsDeclaration($enrollmentId) : array
    {
        $sql = "SELECT * FROM studentsdeclaration WHERE enrollment_id = :enrollment_id";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();
        
        $response = array('report' => $result);

        return $response;
    }

    /**
     * Carrega a Ficha de Matrícula
     */
    public function getStudentsFileInformation($enrollmentId) : void
    {
        $sql = "SELECT * FROM studentsfile WHERE enrollment_id = :enrollment_id;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        echo json_encode($result);
    }

    /**
     * Ata de Notas
     */
    public function getAtaSchoolPerformance($classroomId) : array
    {
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
                WHERE c.id = :classroom_id
                ORDER BY discipline_name;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':classroom_id', $classroomId)
                ->queryAll();

        setlocale(LC_ALL, NULL);
        setlocale(LC_ALL, "pt_BR.utf8", "pt_BR", "ptb", "ptb.utf8");

        $time = mktime(0, 0, 0, $result['month']);
        $result['month'] = strftime("%B", $time);

        $classroom = Classroom::model()->findByPk($classroomId);

        $sql = "SELECT ed.id AS 'discipline_id', ed.name AS 'discipline_name' 
                    FROM curricular_matrix cm 
                JOIN edcenso_discipline ed ON ed.id = cm.discipline_fk 
                WHERE stage_fk = :stage_fk AND school_year = :year 
                ORDER BY ed.name";

        $disciplines = Yii::app()->db->createCommand($sql)
                    ->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)
                    ->bindParam(":year", $this->currentYear)
                    ->queryAll();
        
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

        $response = array(
            'report' => $result,
            'classroom' => $classroom,
            'students' => $students,
            'disciplines' => $disciplines,
            'grades' => $grades
        );

        return $response;
    }

    /**
     * Ficha de Matrícula
     */
    public function getStudentFileForm($enrollmentId) : array
    {
        $enrollment = StudentEnrollment::model()->with("studentFk.edcensoUfFk")->findByPk($enrollmentId);
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);

        $response = array('enrollment' => $enrollment, 'school' => $school);

        return $response;
    }

    public function getStudentsFileForm($classroomId) : array
    {
        $school = SchoolIdentification::model()->findByPk($this->currentSchool);
        $classroom = Classroom::model()->with("studentEnrollments.studentFk.edcensoUfFk")->findByPk($classroomId);
        $enrollments = $classroom->studentEnrollments;

        $response = array(
            'classroom_id' => $classroomId,
            'enrollments' => $enrollments,
            'school' => $school 
        );

        return $response;
    }

    /**
     * Formulário de Transferência
     */
    public function getTransferForm($enrollmentId) : array
    {
        $sql = "SELECT si.nationality 
                    FROM student_identification si
                JOIN student_enrollment se ON se.student_fk = si.id
                WHERE se.id = :enrollment_id;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        $response = array('enrollment_id' => $enrollmentId, 'nationality' => $result['nationality']);

        return $response;
    }

    /**
     * Carrega as informações de Formulário de Transferência
     */
    public function getTransferFormInformation($enrollmentId) : void
    {
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
                WHERE se.id = :enrollment_id;";
                
        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        echo json_encode($result);
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

    /**
     * Termo de Advertência
     */
    public function getWarningTerm($enrollmentId) : array
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
        
        $sql = "SELECT * FROM studentsdeclaration WHERE enrollment_id = :enrollment_id";

        $data = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();
        
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

        $response = array('student' => $data,'turn' => $turn);

        return $response;
    }
}