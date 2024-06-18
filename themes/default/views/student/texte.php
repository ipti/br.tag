Observe esse código abaixo:
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




ele é chamado nessa parte <p>Etapa: <?= $enrollment->classroomFk->edcensoStageVsModalityFk->name?></p>


me ajude a itendificar os bancos correspondentes para encontrar toda essa parte, quais são os bancos e atributos envolvimos na busca