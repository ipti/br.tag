Observe esses códigos abaixo:


código 01:

public function getStudentCertificate($enrollment_id): array
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
        $commandMaxId = Yii::app()->db->createCommand("
            SELECT MAX(id) AS max_id
            FROM student_enrollment
            WHERE student_fk = :student_fk AND status = 1
        ");
        $commandMaxId->bindValue(':student_fk', $enrollment_id);   
        $maxId = $commandMaxId->queryScalar();
        
        $result = array(); // array de notas
        $baseDisciplines = array(); // disciplinas da BNCC
        $diversifiedDisciplines = array(); //disciplinas diversas
        $enrollment = StudentEnrollment::model()->findByPk($maxId);
        // CVarDumper::dump($enrollment, 10, true);

        $gradesResult = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $maxId]); // medias do aluno na turma
        $classFaults = ClassFaults::model()->findAllByAttributes(["student_fk" => $enrollment->studentFk->id]); // faltas do aluno na turma
        $curricularMatrix = CurricularMatrix::model()->findAllByAttributes(["stage_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk, "school_year" => $enrollment->classroomFk->school_year]); // matriz da turma
        $unities = GradeUnity::model()->findAllByAttributes(["edcenso_stage_vs_modality_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk]); // unidades da turma

        $recFinalIndex = array_search('RF', array_column($unities, 'type'));
        $recFinalObject = $unities[$recFinalIndex]; // obs
        array_splice($unities, $recFinalIndex, 1);
        array_push($unities, $recFinalObject);

        foreach ($curricularMatrix as $matrix) {
            if($this->separateBaseDisciplines($matrix->discipline_fk)) { // se for disciplina da BNCC
                array_push($baseDisciplines, $matrix->disciplineFk->id);
            } else { // se for disciplina diversa
                array_push($diversifiedDisciplines, $matrix->disciplineFk->id);
            }
        }

        $totalDisciplines = array_unique(array_merge($baseDisciplines, $diversifiedDisciplines));
        $schedulesPerUnityPeriods = $this->getSchedulesPerUnityPeriods($enrollment->classroomFk, $unities);
        $schoolDaysPerUnity = $this->schoolDaysCalculate($schedulesPerUnityPeriods);
        $workloadPerUnity = $this->workloadsCalculate($schedulesPerUnityPeriods);
        $faultsPerUnity = $this->faultsPerUnityCalculate($schedulesPerUnityPeriods, $classFaults, $enrollment->classroomFk);

        $sumFinalMedia = 0;
        $numFinalMedia = 0;

        foreach ($totalDisciplines as $discipline) { // aqui eu monto as notas das disciplinas, faltas, dias letivos e cargas horárias

            $mediaExists = false;
            $totalContentsPerDiscipline = $this->contentsPerDisciplineCalculate($enrollment->classroomFk, $discipline, $enrollment->id);
            $totalFaultsPerDicipline = $this->faultsPerDisciplineCalculate($schedulesPerUnityPeriods, $discipline, $classFaults, $enrollment->id);

            foreach ($gradesResult as $gradeResult) {
                if($gradeResult->disciplineFk->id == $discipline) {
                    $resultItem = [
                        "discipline_id" => $gradeResult->disciplineFk->id,
                        "final_media" => $gradeResult->final_media,
                        "grade_result" => $gradeResult,
                        "total_number_of_classes" => $totalContentsPerDiscipline,
                        "total_faults" => $totalFaultsPerDicipline,
                        "frequency_percentage" => (($totalContentsPerDiscipline - $totalFaultsPerDicipline) / $totalContentsPerDiscipline) * 100
                    ];
                    array_push($result, $resultItem);

                    if ($gradeResult->final_media !== null) {
                        $sumFinalMedia += $gradeResult->final_media;
                        $numFinalMedia++;
                    }

                    $mediaExists = true;
                    break;
                }
            }

            if(!$mediaExists) {
                $resultItem = [
                    "discipline_id" => $discipline,
                    "final_media" => null,
                    "grade_result" => null,
                    "total_number_of_classes" => $totalContentsPerDiscipline,
                    "total_faults" => $totalFaultsPerDicipline,
                    "frequency_percentage" => (($totalContentsPerDiscipline - $totalFaultsPerDicipline) / $totalContentsPerDiscipline) * 100
                ];
                array_push($result, $resultItem);
            }
        }

        $totalDisciplinesCount = count($totalDisciplines);
        $annualAverage = $totalDisciplinesCount > 0 ? round($sumFinalMedia / $totalDisciplinesCount, 1) : null;


        $report = [];
        foreach ($totalDisciplines as $disciplineId) {
            foreach ($result as $item) {
                if ($item['discipline_id'] === $disciplineId) {
                    $report[] = $item;
                    break;
                }
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
            'enrollment' => $enrollment,
            'result' => $report,
            'baseDisciplines' => array_unique($baseDisciplines), //função usada para evitar repetição
            'diversifiedDisciplines' => array_unique($diversifiedDisciplines), //função usada para evitar repetição
            'unities' => $unities,
            "school_days" => $schoolDaysPerUnity,
            "faults" => $faultsPerUnity,
            "workload" => $workloadPerUnity,
            "annual_average" => $annualAverage
        ];

        return ["student" => $studentData];
    }



Código 02:

<?php

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);

// $this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

if (!isset($school)) {
    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
}

list($day, $month, $year) = explode('/', $student['birthday']);

$months = array(
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
);
$monthName = $months[$month];

function classroomDisciplineLabelResumeArray($id) {
    $disciplinas = array(
        1 => 'Química',
        2 => 'Física',
        3 => 'Matemática',
        4 => 'Biologia',
        5 => 'Ciências',
        6 => 'Português',
        7 => 'Inglês',
        8 => 'Espanhol',
        9 => 'Outro Idioma',
        10 => 'Artes',
        11 => 'Educação Física',
        12 => 'História',
        13 => 'Geografia',
        14 => 'Filosofia',
        16 => 'Informática',
        17 => 'Disc. Profissionalizante',
        20 => 'Educação Especial',
        21 => 'Sociedade&nbspe Cultura',
        23 => 'Libras',
        25 => 'Disciplinas pedagógicas',
        26 => 'Ensino religioso',
        27 => 'Língua indígena',
        28 => 'Estudos Sociais',
        29 => 'Sociologia',
        30 => 'Francês',
        99 => 'Outras Disciplinas',
        10001 => 'Redação',
        10002 => 'Linguagem oral e escrita',
        10003 => 'Natureza e sociedade',
        10004 => 'Movimento',
        10005 => 'Música',
        10006 => 'Artes visuais',
        10007 => 'Acompanhamento Pedagógico',
        10008 => 'Teatro',
        10009 => 'Canteiro Sustentável',
        10010 => 'Dança',
        10011 => 'Cordel',
        10012 => 'Física'
    );

    if (array_key_exists($id, $disciplinas)) {
        return $disciplinas[$id];
    } else {
        return EdcensoDiscipline::model()->findByPk($id)->name;
    }
}



CVarDumper::dump($student['baseDisciplines'], 10, true);


Nos códigos acima são aresentadas na tela informações com oas abaixo:
    array
    (
        0 => '6'
        1 => '10'
        2 => '11'
        3 => '3'
        4 => '5'
        5 => '12'
        6 => '13'
        7 => '26'
        8 => '7'
    )
 que representa o numero de cada uma das matérias $student['baseDisciplines'].

 Agora observe o código 03 abaixo, que mostra mais diciplinas para cada uma das escolas:

 código 03:


 public function getStudentCertificate($enrollment_id): array
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
     $commandMaxId = Yii::app()->db->createCommand("
         SELECT id AS max_id
         FROM student_enrollment
         WHERE student_fk = :student_fk AND status = 2
     ");
 
     $commandMaxId->bindValue(':student_fk', $enrollment_id);
 
     $maxIds = $commandMaxId->queryAll();
     CVarDumper::dump($maxIds, 10, true);
 
     $result = array(); // array de notas
     $baseDisciplines = array(); // disciplinas da BNCC
     $diversifiedDisciplines = array(); // disciplinas diversas
 
     foreach ($maxIds as $idArray) {
         $maxId = $idArray['max_id'];
         $enrollment = StudentEnrollment::model()->findByPk($maxId);
         CVarDumper::dump($enrollment, 10, true);
         
         // $result[] = $enrollment;
     }
         // $enrollment = StudentEnrollment::model()->findByPk($maxId);
 
         // CVarDumper::dump($maxId, 10, true);
 
 
 
         $gradesResult = GradeResults::model()->findAllByAttributes(["enrollment_fk" => $maxId]);
         $classFaults = ClassFaults::model()->findAllByAttributes(["student_fk" => $enrollment->studentFk->id]); // faltas do aluno na turma
         $curricularMatrix = CurricularMatrix::model()->findAllByAttributes(["stage_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk, "school_year" => $enrollment->classroomFk->school_year]); // matriz da turma
         $unities = GradeUnity::model()->findAllByAttributes(["edcenso_stage_vs_modality_fk" => $enrollment->classroomFk->edcenso_stage_vs_modality_fk]); // unidades da turma
 
         $recFinalIndex = array_search('RF', array_column($unities, 'type'));
         $recFinalObject = $unities[$recFinalIndex]; // obs
         array_splice($unities, $recFinalIndex, 1);
         array_push($unities, $recFinalObject);
 
         foreach ($curricularMatrix as $matrix) {
             if($this->separateBaseDisciplines($matrix->discipline_fk)) { // se for disciplina da BNCC
                 array_push($baseDisciplines, $matrix->disciplineFk->id);
             } else { // se for disciplina diversa
                 array_push($diversifiedDisciplines, $matrix->disciplineFk->id);
             }
         }
 
         $totalDisciplines = array_unique(array_merge($baseDisciplines, $diversifiedDisciplines));
         $schedulesPerUnityPeriods = $this->getSchedulesPerUnityPeriods($enrollment->classroomFk, $unities);
         $schoolDaysPerUnity = $this->schoolDaysCalculate($schedulesPerUnityPeriods);
         $workloadPerUnity = $this->workloadsCalculate($schedulesPerUnityPeriods);
         $faultsPerUnity = $this->faultsPerUnityCalculate($schedulesPerUnityPeriods, $classFaults, $enrollment->classroomFk);
 
         $sumFinalMedia = 0;
         $numFinalMedia = 0;
 
         foreach ($totalDisciplines as $discipline) { // aqui eu monto as notas das disciplinas, faltas, dias letivos e cargas horárias
 
             $mediaExists = false;
             $totalContentsPerDiscipline = $this->contentsPerDisciplineCalculate($enrollment->classroomFk, $discipline, $enrollment->id);
             $totalFaultsPerDicipline = $this->faultsPerDisciplineCalculate($schedulesPerUnityPeriods, $discipline, $classFaults, $enrollment->id);
 
             foreach ($gradesResult as $gradeResult) {
                 if($gradeResult->disciplineFk->id == $discipline) {
                     $resultItem = [
                         "discipline_id" => $gradeResult->disciplineFk->id,
                         "final_media" => $gradeResult->final_media,
                         "grade_result" => $gradeResult,
                         "total_number_of_classes" => $totalContentsPerDiscipline,
                         "total_faults" => $totalFaultsPerDicipline,
                         "frequency_percentage" => (($totalContentsPerDiscipline - $totalFaultsPerDicipline) / $totalContentsPerDiscipline) * 100
                     ];
                     array_push($result, $resultItem);
 
                     if ($gradeResult->final_media !== null) {
                         $sumFinalMedia += $gradeResult->final_media;
                         $numFinalMedia++;
                     }
 
                     $mediaExists = true;
                     break;
                 }
             }
 
             if(!$mediaExists) {
                 $resultItem = [
                     "discipline_id" => $discipline,
                     "final_media" => null,
                     "grade_result" => null,
                     "total_number_of_classes" => $totalContentsPerDiscipline,
                     "total_faults" => $totalFaultsPerDicipline,
                     "frequency_percentage" => (($totalContentsPerDiscipline - $totalFaultsPerDicipline) / $totalContentsPerDiscipline) * 100
                 ];
                 array_push($result, $resultItem);
             }
         }
 
         $totalDisciplinesCount = count($totalDisciplines);
         $annualAverage = $totalDisciplinesCount > 0 ? round($sumFinalMedia / $totalDisciplinesCount, 1) : null;
 
 
         $report = [];
         foreach ($totalDisciplines as $disciplineId) {
             foreach ($result as $item) {
                 if ($item['discipline_id'] === $disciplineId) {
                     $report[] = $item;
                     break;
                 }
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
             'enrollment' => $enrollment,
             'result' => $report,
             'baseDisciplines' => array_unique($baseDisciplines), //função usada para evitar repetição
             'diversifiedDisciplines' => array_unique($diversifiedDisciplines), //função usada para evitar repetição
             'unities' => $unities,
             "school_days" => $schoolDaysPerUnity,
             "faults" => $faultsPerUnity,
             "workload" => $workloadPerUnity,
             "annual_average" => $annualAverage
         ];
 
         return ["student" => $studentData];
     }


 Código 04:

Como fazer para pegar c