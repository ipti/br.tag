Observe esse código:


código 01:

public function getStudentCertificate ($enrollment_id): array
    {
        $studentIdent = StudentIdentification::model()->findByPk($enrollment_id);

        if (!$studentIdent) {
            return ["student" => null];
        }

        $city = null;
        $uf_acronym = null;
        $uf_name = null;
        $class_name = null;
        $tipo_ensino = '';
        $ano = '';

        if ($cityObj = EdcensoCity::model()->findByPk($studentIdent->edcenso_city_fk)) {
            $city = $cityObj->name;
            if ($ufObj = EdcensoUf::model()->findByPk($cityObj->edcenso_uf_fk)) {
                $uf_acronym = $ufObj->acronym;
                $uf_name = $ufObj->name;
            }
        }

        $command = Yii::app()->db->createCommand("
            SELECT c.name, esv.name as etapa
            FROM classroom c
            JOIN student_enrollment se ON c.id = se.classroom_fk
            JOIN edcenso_stage_vs_modality esv ON c.edcenso_stage_vs_modality_fk = esv.id
            WHERE se.student_fk = :student_fk AND se.status = 1
            ORDER BY se.id DESC
            LIMIT 1
        ");
        $command->bindValue(':student_fk', $enrollment_id);
        $row = $command->queryRow();
        if ($row) {
            $class_name = $row['name'];
            $etapa = $row['etapa'];

            $etapa_parts = explode(' - ', $etapa);

            if (count($etapa_parts) == 2) {
                $tipo_ensino = $etapa_parts[0];
                $ano = $etapa_parts[1];
            }
        }

        $studentData = [
            'name' => $studentIdent->name,
            'civil_name' => $studentIdent->civil_name,
            'birthday' => $studentIdent->birthday,
            'sex' => $studentIdent->sex,
            'color_race' => $studentIdent->color_race,
            'filiation' => $studentIdent->filiation,
            'filiation_1' => $studentIdent->filiation_1,
            'filiation_2' => $studentIdent->filiation_2,
            'city' => $city,
            'uf_acronym' => $uf_acronym,
            'uf_name' => $uf_name,
            'class_name' => $class_name,
            'tipo_ensino' => $tipo_ensino,
            'ano' => $ano,
        ];
        return ["student" => $studentData];
    }


Como fazer para adicionar informações do código abaixo no código 01


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

