<?php

class FormsRepository {

    private $currentSchool;
    private $currentYear;

    public function __construct()
    {
        $this->currentSchool = Yii::app()->user->school;
        $this->currentYear = Yii::app()->user->year;
    }

    private function contentsPerDisciplineCalculate($classroom, $disciplineId, $enrollmentId) {
        // calculando o total de aulas ministradas naquela turma na disciplina específica
        $totalContents = 0;

        //Prioriza o que está preenchido em gradeResults
        $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", [
            ":enrollment_fk" => $enrollmentId,
            ":discipline_fk" => $disciplineId
        ]);
        if ($gradeResult != null) {
            for ( $i = 1; $i <= 8; $i++ ) {
                $totalContents += $gradeResult['given_classes_'.$i];
            }
        }

        if ($totalContents == 0) {
            //Caso não haja preenchimento em gradeResults ou seja 0
            if (TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk)) {
                $condition = 'classroom_fk = :classroomId';
                $params = array(
                    ':classroomId' => $classroom->id,
                );
            } else {
                $condition = 'classroom_fk = :classroomId AND discipline_fk = :disciplineId';
                $params = array(
                    ':classroomId' => $classroom->id,
                    ':disciplineId' => $disciplineId,
                );
            }
            $schedulesWithContent = Schedule::model()->findAll(array(
                'alias' => "s",
                'join' => 'JOIN class_contents cc ON cc.schedule_fk = s.id',
                'condition' => $condition,
                'params' => $params,
            ));
            foreach($schedulesWithContent as $scheduleWithContent) {
                $schedules = Schedule::model()->findAll(array(
                    'condition' => 'classroom_fk = :classroomId AND discipline_fk = :disciplineId and day = :day and month = :month',
                    'params' => array(
                        ':day' => $scheduleWithContent->day,
                        ':month' => $scheduleWithContent->month,
                        ':classroomId' => $classroom->id,
                        ':disciplineId' => $disciplineId,
                    ),
                ));
                $totalContents += count($schedules);
            }
        }

        return $totalContents;
    }

    private function faultsPerDisciplineCalculate($schedulesPerUnityPeriods, $disciplineId, $classFaults, $enrollmentId) {
        // calculando o total de faltas na disciplina específica
        $totalFaults = 0;

        //Prioriza o que está preenchido em gradeResults
        $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", [
            ":enrollment_fk" => $enrollmentId,
            ":discipline_fk" => $disciplineId
        ]);
        if ($gradeResult != null) {
            for ( $i = 1; $i <= 8; $i++ ) {
                $totalFaults += $gradeResult['grade_faults_'.$i];
            }
        }

        if ($totalFaults == 0) {
            //Caso não haja preenchimento em gradeResults ou seja 0
            foreach ($schedulesPerUnityPeriods as $schedules) {
                foreach ($schedules as $schedule) {
                    foreach ($classFaults as $classFault) {
                        if ($schedule->discipline_fk == $disciplineId && $classFault->schedule_fk == $schedule->id) {
                            $totalFaults++;
                        }
                    }
                }
            }
        }
        return $totalFaults;
    }

    private function getSchedulesPerUnityPeriods($classroomFk, $unities) {
        // Retorna todos os schedules dentro do periodo das unidades
        $schedulesPerUnityPeriods = [];
        $unityDates = [];
        foreach($unities as $unity) {
            $unityPeriods = GradeUnityPeriods::model()->find("calendar_fk = :calendar_fk
                and grade_unity_fk = :grade_unity_fk", [
                ":calendar_fk" => $classroomFk->calendar_fk,
                ":grade_unity_fk" => $unity->id
            ]);
            if ($unityPeriods != null) {
                array_push($unityDates, $unityPeriods->initial_date);
            }
        }

        for ($i = 0; $i < count($unityDates); $i++) {
            if ($i < count($unityDates) - 1) {
                $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk
                and unavailable = 0
                and concat(:year,'-', LPAD(month, 2, '0'), '-', LPAD(day, 2, '0')) >= :initial_date
                and concat(:year,'-', LPAD(month, 2, '0'), '-', LPAD(day, 2, '0')) < :final_date", [
                    ":year" => $classroomFk->school_year,
                    ":classroom_fk" => $classroomFk->id,
                    ":initial_date" => $unityDates[$i],
                    ":final_date" => $unityDates[$i+1]
                ]);
            } else {
                $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk
                and unavailable = 0
                and concat(:year,'-', LPAD(month, 2, '0'), '-', LPAD(day, 2, '0')) >= :initial_date", [
                    ":year" => $classroomFk->school_year,
                    ":classroom_fk" => $classroomFk->id,
                    ":initial_date" => $unityDates[$i]
                ]);
            }

            array_push($schedulesPerUnityPeriods, $schedules);
        }

        return $schedulesPerUnityPeriods;
    }

    private function schoolDaysCalculate($schedulesPerUnityPeriods)
    {
        // calculando todos dias letivos no quadro de horário para a turma naquela disciplina
        $schoolDayPerUnity = [];
        foreach ($schedulesPerUnityPeriods as $schedules) {
            $days = [];
            foreach ($schedules as $schedule) {
                $day = $schedule->month . $schedule->day;
                if (!in_array($day, $days)) {
                    array_push($days, $day);
                }
            }
            array_push($schoolDayPerUnity, count($days));
        }

        return $schoolDayPerUnity;
    }

    private function faultsPerUnityCalculate($schedulesPerUnityPeriods, $classFaults, $classroom)
    {
        // Cálculo da faltas do aluno por unidade
        $faultsPerUnityPeriods = [];
        foreach ($schedulesPerUnityPeriods as $schedules) {
            $scheduleFaults = [];
            foreach ($schedules as $schedule) {
                foreach ($classFaults as $classFault) {
                    if ($classFault->schedule_fk == $schedule->id) {
                        array_push($scheduleFaults, $schedule);
                    }
                }
            }
            array_push($faultsPerUnityPeriods, $scheduleFaults);
        }

        if (TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk)) {
            $faultsPerUnityPeriods = $this->schoolDaysCalculate($faultsPerUnityPeriods);
        } else {
            $faultsPerUnityPeriods = $this->workloadsCalculate($faultsPerUnityPeriods);
        }
        return $faultsPerUnityPeriods;
    }

    private function workloadsCalculate($schedulesPerUnityPeriods)
    {
        // Cálculo da carga horária por unidade
        $workloadsPerUnity = [];
        foreach ($schedulesPerUnityPeriods as $schedules) {
            array_push($workloadsPerUnity, count($schedules));
        }

        return $workloadsPerUnity;
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
     * Ficha de Notas
     */
    public function getEnrollmentGrades($enrollmentId) : array
    {
        $result = array(); // array de notas
        $baseDisciplines = array(); // disciplinas da BNCC
        $diversifiedDisciplines = array(); //disciplinas diversas
        $enrollment = StudentEnrollment::model()->findByPk($enrollmentId);
        $gradesResult = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $enrollmentId]); // medias do aluno na turma
        $classFaults = ClassFaults::model()->findAllByAttributes(["student_fk" => $enrollment->studentFk->id]); // faltas do aluno na turma
        $curricularMatrix = CurricularMatrix::model()->findAllByAttributes(["stage_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk, "school_year" => $enrollment->classroomFk->school_year]); // matriz da turma
        $unities = GradeUnity::model()->findAllByAttributes(["edcenso_stage_vs_modality_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk]); // unidades da turma

        // Ajusta ordem das unidades se houver rec. Final
        $recFinalIndex = array_search('RF', array_column($unities, 'type'));
        $recFinalObject = $unities[$recFinalIndex];
        array_splice($unities, $recFinalIndex, 1);
        array_push($unities, $recFinalObject);

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

        // Retorna todos os schedules dentro do periodo das unidades
        $schedulesPerUnityPeriods = $this->getSchedulesPerUnityPeriods($enrollment->classroomFk, $unities);

        // Cálculo de dias de todas as unidades que possuem dias letivos
        $schoolDaysPerUnity = $this->schoolDaysCalculate($schedulesPerUnityPeriods);

        // Cálculo da carga horária por unidade
        $workloadPerUnity = $this->workloadsCalculate($schedulesPerUnityPeriods);

        // Cálculo de faltas de todas as unidades que possuem dias letivos
        $faultsPerUnity = $this->faultsPerUnityCalculate($schedulesPerUnityPeriods, $classFaults, $enrollment->classroomFk);

        foreach ($totalDisciplines as $discipline) { // aqui eu monto as notas das disciplinas, faltas, dias letivos e cargas horárias

            // verifica se o aluno tem notas para a disciplina
            $mediaExists = false;

            // cálculo de aulas dadas
            $totalContentsPerDiscipline = $this->contentsPerDisciplineCalculate($enrollment->classroomFk, $discipline, $enrollment->id);

            $totalFaultsPerDicipline = $this->faultsPerDisciplineCalculate($schedulesPerUnityPeriods, $discipline, $classFaults, $enrollment->id);

            foreach ($gradesResult as $gradeResult) {
                // se existe notas para essa disciplina
                if($gradeResult->disciplineFk->id == $discipline) {
                    array_push($result, [
                        "discipline_id" => $gradeResult->disciplineFk->id,
                        "final_media" => $gradeResult->final_media,
                        "grade_result" => $gradeResult,
                        "total_number_of_classes" => $totalContentsPerDiscipline,
                        "total_faults" => $totalFaultsPerDicipline,
                        "frequency_percentage" => (($totalContentsPerDiscipline - $totalFaultsPerDicipline) / $totalContentsPerDiscipline) * 100
                    ]);
                    $mediaExists = true;
                    break; // quebro o laço para diminuir a complexidade do algoritmo para O(log n)2
                }
            }

            if(!$mediaExists) { // o aluno não tem notas para a disciplina
                array_push($result, [
                    "discipline_id" => $discipline,
                    "final_media" => null,
                    "grade_result" => null,
                    "total_number_of_classes" => $totalContentsPerDiscipline,
                    "total_faults" => $totalFaultsPerDicipline,
                    "frequency_percentage" => (($totalContentsPerDiscipline - $totalFaultsPerDicipline) / $totalContentsPerDiscipline) * 100
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
            'unities' => $unities,
            "school_days" => $schoolDaysPerUnity,
            "faults" => $faultsPerUnity,
            "workload" => $workloadPerUnity,
        );

        return $response;
    }


    private function calculateFrequency($diasLetivos, $totalFaltas): int
    {
        if ($diasLetivos === 0) {
            return 0; // Evitar divisão por zero
        }

        $frequencia = (($diasLetivos - $totalFaltas) / $diasLetivos) * 100;

        return round($frequencia);
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
        $scheduleSql = "SELECT `month`, `day`c FROM schedule s JOIN classroom c on c.id = s.classroom_fk
        WHERE c.school_year = :year AND c.id = :classroom
        GROUP BY s.`month`, s.`day`";
        $scheduleParams = array(':year' => Yii::app()->user->year, ':classroom' => $enrollment->classroom_fk);
        $schedules = Schedule::model()->findAllBySql($scheduleSql, $scheduleParams);
        $gradeRules = GradeRules::model()->findByAttributes(["edcenso_stage_vs_modality_fk" => $enrollment->classroomFk->edcensoStageVsModalityFk->id]);
        $portuguese = array(); $history = array(); $geography = array(); $mathematics = array(); $sciences = array();
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
                            "givenClasses1" => $g->given_classes_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "givenClasses2" => $g->given_classes_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "givenClasses3" => $g->given_classes_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "givenClasses4" => $g->given_classes_4,
                            "final_media" => $g->final_media
                        ]);
                    }else if ($c->disciplineFk->id == 12 && $minorFundamental) {
                        array_push($history, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "givenClasses1" => $g->given_classes_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "givenClasses2" => $g->given_classes_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "givenClasses3" => $g->given_classes_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "givenClasses4" => $g->given_classes_4,
                            "final_media" => $g->final_media
                        ]);
                    }else if ($c->disciplineFk->id == 13 && $minorFundamental) {
                        array_push($geography, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "givenClasses1" => $g->given_classes_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "givenClasses2" => $g->given_classes_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "givenClasses3" => $g->given_classes_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "givenClasses4" => $g->given_classes_4,
                            "final_media" => $g->final_media
                        ]);
                    }else if ($c->disciplineFk->id == 3 && $minorFundamental) {
                        array_push($mathematics, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "givenClasses1" => $g->given_classes_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "givenClasses2" => $g->given_classes_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "givenClasses3" => $g->given_classes_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "givenClasses4" => $g->given_classes_4,
                            "final_media" => $g->final_media
                        ]);
                    }else if ($c->disciplineFk->id == 5 && $minorFundamental) {
                        array_push($sciences, [
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "givenClasses1" => $g->given_classes_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "givenClasses2" => $g->given_classes_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "givenClasses3" => $g->given_classes_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "givenClasses4" => $g->given_classes_4,
                            "final_media" => $g->final_media
                        ]);
                    }else {
                        array_push($disciplines, [
                            "name" => $c->disciplineFk->name,
                            "grade1" => $g->grade_1,
                            "faults1" => $g->grade_faults_1,
                            "givenClasses1" => $g->given_classes_1,
                            "grade2" => $g->grade_2,
                            "faults2" => $g->grade_faults_2,
                            "givenClasses2" => $g->given_classes_2,
                            "grade3" => $g->grade_3,
                            "faults3" => $g->grade_faults_3,
                            "givenClasses3" => $g->given_classes_3,
                            "grade4" => $g->grade_4,
                            "faults4" => $g->grade_faults_4,
                            "givenClasses4" => $g->given_classes_4,
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
    public function getEnrollmentDeclarationInformation($enrollmentId)
    {
        $sql = "SELECT si.name as name, si.filiation_1 filiation_1, si.filiation_2
                    filiation_2, si.birthday birthday, si.inep_id inep_id,
                    sd.nis nis, ec.name city, c.school_year enrollment_date
                    FROM student_enrollment se
                JOIN classroom c ON c.id = se.classroom_fk
                JOIN student_identification si ON si.id = se.student_fk
                JOIN student_documents_and_address sd ON si.id = sd.id
                JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id
                WHERE se.id = :enrollment_id;";

        return Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

    }

    /**
     * Requerimento de Transferência
     */
    public function getTransferRequirement($enrollmentId) : array
    {
        $sql = "SELECT
                    si.sex gender,
                    IFNULL(se.edcenso_stage_vs_modality_fk, c.edcenso_stage_vs_modality_fk) stage,
                    IFNULL(se.edcenso_stage_vs_modality_fk, c.edcenso_stage_vs_modality_fk) class
                    FROM student_identification si
                JOIN student_enrollment se ON se.student_fk = si.id
                JOIN classroom c on se.classroom_fk = c.id
                WHERE se.id = :enrollment_id;";

        $result = Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

        $response = array(
            'enrollment_id' => $enrollmentId,
            'gender' => $result['gender'],
            'stage' => $result['stage'], 'class' => $result['class']);

        return $response;
    }

    /**
     * Carrega informações do Requerimento de Transferência
     */
    public function getTransferRequirementInformation($enrollmentId)
    {
        $sql = "SELECT si.name as name, si.filiation_1 mother, si.filiation_2 father, si.birthday birthday, ec.name city, euf.acronym state, YEAR(se.create_date) enrollment_date
                    FROM student_enrollment se
                JOIN student_identification si ON si.id = se.student_fk
                JOIN student_documents_and_address sd ON si.id = sd.id
                JOIN edcenso_city ec ON si.edcenso_city_fk = ec.id
                LEFT JOIN edcenso_uf euf ON si.edcenso_uf_fk = euf.id
                WHERE se.id = :enrollment_id;";

        return Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();
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
    public function getEnrollmentNotificationInformation($enrollmentId)
    {
        $sql = "SELECT si.name name, YEAR(se.create_date) enrollment_date
                    FROM student_enrollment se
                JOIN student_identification si ON si.id = se.student_fk
                WHERE se.id = :enrollment_id;";

        return Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();
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
    public function getStudentsFileInformation($enrollmentId)
    {
        $sql = "SELECT * FROM studentsfile WHERE enrollment_id = :enrollment_id;";

        return Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();

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
                gr.final_media,
                gr.situation
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

        $studentEnrollments = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classroom->id));

        $grades = [];

        foreach ($studentEnrollments as $s) {
            $finalSituation = '';
            foreach ($disciplines as $d) {
                $finalMedia = '';
                foreach ($result as $r) {
                    if ($r['discipline_id'] == $d['discipline_id'] && $r['student_id'] == $s['student_fk']) {
                        $finalMedia = $r['final_media'];
                        $r['situation'] = mb_strtoupper($r['situation']);
                        if ($r['situation'] == 'REPROVADO') {
                            $finalSituation = 'REPROVADO';
                        } else if ($r['situation'] == 'RECUPERAÇÃO' && $finalSituation != 'REPROVADO') {
                            $finalSituation = 'RECUPERAÇÃO';
                        } else if ($r['situation'] == 'APROVADO' && $finalSituation != 'REPROVADO' && $finalSituation != 'RECUPERAÇÃO') {
                            $finalSituation = 'APROVADO';
                        }
                        break;
                    }
                }
                array_push($grades, array(
                        "discipline_id" => $d['discipline_id'],
                        "discipline_name" => $d['discipline_name'],
                        "student_name" => $s['studentFk']['name'],
                        "student_id" => $s['student_fk'],
                        "final_media" => $finalMedia,
                        "situation" => $finalSituation
                    )
                );
            }
        }

        $response = array(
            'report' => $result,
            'classroom' => $classroom,
            'students' => $studentEnrollments,
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
    public function getTransferFormInformation($enrollmentId)
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

        return Yii::app()->db->createCommand($sql)
                ->bindParam(':enrollment_id', $enrollmentId)
                ->queryRow();


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
