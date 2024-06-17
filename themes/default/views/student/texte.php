Observe esse código 
<?php
/* @var $this ReportsController */
/* @var $enrollment StudentEnrollment */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/IndividualReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$turn = "";
if($enrollment->classroomFk->turn == "M") {
    $turn = "Matutino";
}else if ($enrollment->classroomFk->turn == "T") {
    $turn = "Vespertino";
}else if ($enrollment->classroomFk->turn == "N") {
    $turn = "Noturno";
}

$gradeOneTotalFaults = 0;
$gradeTwoTotalFaults = 0;
$gradeThreeTotalFaults = 0;
$gradeTotalFaults = 0;

$portugueseCount = !empty($portuguese) ? 1 : 0;
$historyCount = !empty($history) ? 1 : 0;
$geographyCount = !empty($geography) ? 1 : 0;

$mathematicsCount = !empty($mathematics) ? 1 : 0;
$sciencesCount = !empty($sciences) ? 1 : 0;

$rOneDisiciplinesCount = $portugueseCount + $historyCount + $geographyCount;
$rTwoDisciplinesCount = $mathematicsCount + $sciencesCount;

?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" onclick="imprimirPagina()" class='btn btn-icon hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>

<div class="pageA4V">
    <?php
    if (TagUtils::isInstance("BUZIOS")){
        $this->renderPartial('../reports/buzios/headers/headBuziosVI');
    }

    if(!TagUtils::isInstance("BUZIOS") || TagUtils::isInstance("LOCALHOST")){
        $this->renderPartial('../reports/buzios/headers/head');
    }
    ?>

    <hr>
    <h3 style="text-align:center;">FICHA INDIVIDUAL DE ENSINO FUNDAMENTAL <?php echo $minorFundamental ? "1º" : "2º"?> SEGMENTO</h3>
    <hr>
    <div class="container-box">
        <p>
            <span><?= "Nome do aluno(a): <u>".$enrollment->studentFk->name?></u></span>
            <span style="float:right;margin-right:100px;"><?= "Ano Letivo(a): <u>".$enrollment->classroomFk->school_year ?></u></span>
        </p>
        <p>
            <p><?= "Filiação: ".$enrollment->studentFk->filiation_1 ?></p>
            <p style="margin-left:60px;"><?= $enrollment->studentFk->filiation_2 ?></p>
        </p>
        <p>
            <span><?= "Nascimento: ".$enrollment->studentFk->birthday?></span>
            <span style="float:right;margin-right:100px;"><?= "Naturalidade: ".$enrollment->studentFk->edcensoCityFk->name." - ".$enrollment->studentFk->edcensoUfFk->acronym?></span>
        </p>
        <p>
            <span><?= "Identidade: ".$enrollment->studentFk->documentsFk->rg_number ?></span>
            <span style="float:right;margin-right:100px;"><?= "Orgão Expedidor: ".$enrollment->studentFk->documentsFk->rgNumberEdcensoOrganIdEmitterFk->name?></span>
        </p>
        <?php
            if($enrollment->studentFk->deficiency) {
                echo "<p>Atendimento Educacional Especializado: &nbsp(&nbspX&nbsp)&nbspSim &nbsp(&nbsp&nbsp)&nbspNão</p>";
            }else {
                echo "<p>Atendimento Educacional Especializado: &nbsp(&nbsp&nbsp)&nbspSim &nbsp(&nbspX&nbsp)&nbspNão</p>";
            }
        ?>
        <p>Etapa: <?= $enrollment->classroomFk->edcensoStageVsModalityFk->name?></p>
    </div>
</div>

Agora observe o código 02:

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




    Como fazer para modificar o código abaixo, para pegar os dados da nacionalidade no código abaixo:


        public function getStudentCertificate($enrollment_id): array
    {
        $studentIdent = StudentIdentification::model()->findByPk($enrollment_id);

        if (!$studentIdent) {
            return array("student" => null);
        }


        $studentData = array(
            'name' => $studentIdent->name,
            'civil_name' => $studentIdent->civil_name,
            'birthday' => $studentIdent->birthday,
            'sex' => $studentIdent->sex,
            'color_race' => $studentIdent->color_race,
            'filiation' => $studentIdent->filiation,
            'filiation_1' => $studentIdent->filiation_1,
            'filiation_2' => $studentIdent->filiation_2,
        );

        return array("student" => $studentData);
    }