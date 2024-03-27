<?php

class GradesController extends Controller
{

    public $layout = 'fullmenu';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(

                    'getmodalities',
                    'grades',
                    'getGrades',
                    'saveGrades',
                    'CheckEnrollmentDelete',
                    'getDisciplines',
                    'calculateFinalMedia',
                    'reportCard',
                    'getGradesRelease',
                    'getReportCardGrades',
                    'saveGradesReportCard',
                    'saveGradesRelease'
                ),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionGetModalities()
    {
        $stage = $_POST['Stage'];
        $where = ($stage == "0") ? "" : "stage = $stage";
        $data = EdcensoStageVsModality::model()->findAll($where);
        $data = CHtml::listData($data, 'id', 'name');

        foreach ($data as $value => $name) {
            echo htmlspecialchars(CHtml::tag('option', array('value' => $value), CHtml::encode($name), true));
        }
    }

    /**
     * Show the view
     */
    public function actionGrades()
    {
        $year = Yii::app()->user->year;
        $school = Yii::app()->user->school;

        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(':school_year' => $year, ':school_inep_fk' => $school, ':users_fk' => Yii::app()->user->loginInfos->id);

            $classroom = Classroom::model()->findAll($criteria);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        } else {
            $classroom = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => $year, 'school_inep_fk' => $school]);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        }

        $this->render('grades', ['classrooms' => $classroom]);
    }

    public function actionGetDisciplines()
    {
        $classroom = Classroom::model()->findByPk($_POST["classroom"]);
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                "select ed.id from teaching_matrixes tm
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name"
            )
                ->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":crid", $classroom->id)->queryAll();
            foreach ($disciplines as $discipline) {
                echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['id']), CHtml::encode($disciplinesLabels[$discipline['id']]), true));
            }
        } else {
            echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione...'), true);
            $classr = Yii::app()->db->createCommand(
                "select curricular_matrix.discipline_fk
                from curricular_matrix
                    join edcenso_discipline ed on ed.id = curricular_matrix.discipline_fk
                where stage_fk = :stage_fk and school_year = :year order by ed.name"
            )
                ->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)
                ->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($classr as $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }

    public function actionReportCard()
    {
        $year = Yii::app()->user->year;
        $school = Yii::app()->user->school;

        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(':school_year' => $year, ':school_inep_fk' => $school, ':users_fk' => Yii::app()->user->loginInfos->id);

            $classroom = Classroom::model()->findAll($criteria);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        } else {
            $classroom = Classroom::model()->findAll('school_year = :school_year and school_inep_fk = :school_inep_fk order by name', ['school_year' => $year, 'school_inep_fk' => $school]);
            $classroom = CHtml::listData($classroom, 'id', 'name');
        }

        $this->render('reportCard', ['classrooms' => $classroom]);
    }

    public function actionSaveGradesReportCard()
    {
        $discipline = $_POST['discipline'];
        $students = $_POST['students'];
        $rule = $_POST['rule'];

        foreach ($students as $std) {
            $mediaFinal = 0;
            $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $std['enrollmentId'], "discipline_fk" => $discipline]);
            if (!isset($gradeResult)) {
                $gradeResult = new GradeResults;
            }

            $gradeResult->enrollment_fk = $std['enrollmentId'];
            $gradeResult->discipline_fk = $discipline;
            $gradeResult->final_concept = $std["finalConcept"];

            $hasAllValues = true;
            for ($key = 0; $key < 3; $key++) {
                $index = $key + 1;
                if($rule == "C") {
                    $gradeResult->{"grade_concept_" . $index} = $std['grades'][$key]['value'];
                    $hasAllValues = $hasAllValues && (isset($gradeResult["grade_concept_" . $index]) && $gradeResult["grade_concept_" . $index] != "");
                } else {
                    $gradeResult->{"grade_" . $index} = $std['grades'][$key]['value'];
                    $mediaFinal += floatval($gradeResult->attributes["grade_" . $index] * ($index == 3 ? 2 : 1));
                }
                $gradeResult->{"grade_faults_" . $index} = $std['grades'][$key]['faults'];
                $gradeResult->{"given_classes_" . $index} = $std['grades'][$key]['givenClasses'];
            }

            if($rule == "C") {
                if($hasAllValues && (isset($std["finalConcept"]) && $std["finalConcept"] != "")) {
                    $gradeResult->situation = "APROVADO";
                } else {
                    $gradeResult->situation = "";
                }
            } else {
                $gradeResult->final_media = floor(($mediaFinal/4) * 10) / 10;
            }

            if (!$gradeResult->validate()) {
                die(print_r($gradeResult->getErrors()));
            }
            $gradeResult->save();
        }

        echo json_encode(["valid" => true]);
    }

    public function actionSaveGradesRelease()
    {
        $discipline = $_POST['discipline'];
        $classroomId = $_POST['classroom'];
        $students = $_POST['students'];
        $rule = $_POST['rule'];

        $classroom = Classroom::model()->findByPk($classroomId);

        $gradeRules = GradeRules::model()->findByAttributes([
            "edcenso_stage_vs_modality_fk" => $classroom->edcenso_stage_vs_modality_fk
        ]);

        foreach ($students as $std) {
            $start = microtime(true);
            $gradeResult = (new GetStudentGradesResultUsecase($std['enrollmentId'], $discipline))->exec();
            $gradeResult->enrollment_fk = $std['enrollmentId'];
            $gradeResult->discipline_fk = $discipline;
            $gradeResult->rec_final = $std["recFinal"];
            $gradeResult->rec_semianual_1 = $std["recSemianual1"];
            $gradeResult->rec_semianual_2 = $std["recSemianual2"];
            $gradeResult->final_concept = $std["finalConcept"];

            $hasAllValues = true;
            $totalFaults = 0;
            $givenClasses = 0;
            foreach ($std['grades'] as $key => $value) {
                $index = $key + 1;
                if($rule == "C") {
                    $gradeResult->{"grade_concept_" . $index} = $std['grades'][$key]['value'];
                    $hasAllValues = $hasAllValues && (isset($gradeResult["grade_concept_" . $index]) && $gradeResult["grade_concept_" . $index] != "");
                } else {
                    $gradeResult->{"grade_" . $index} = $std['grades'][$key]['value'];
                    $hasAllValues = $hasAllValues && (isset($gradeResult["grade_" . $index]) && $gradeResult["grade_" . $index] != "");
                }
                $gradeResult->{"grade_faults_" . $index} = $std['grades'][$key]['faults'];
                $totalFaults += (int) $std['grades'][$key]['faults'];
                $gradeResult->{"given_classes_" . $index} = $std['grades'][$key]['givenClasses'];
                $givenClasses += (int) $std['grades'][$key]['givenClasses'];
            }

            $frequency = (($givenClasses - $totalFaults) / $givenClasses)*100;


            if (!$gradeResult->validate()) {
                throw new CHttpException(
                    "400",
                    "Não foi possível validar as notas adicionadas: " . TagUtils::stringfyValidationErrors($gradeResult->getErrors())
                );
            }

            $gradeResult->save();

            if ($hasAllValues) {
                $usecaseFinalMedia = new CalculateFinalMediaUsecase(
                    $gradeResult,
                    $gradeRules,
                    count($std['grades'])
                );
                $usecaseFinalMedia->exec();

                $count = 2;
                $usecaseSemiMedia = new CalculateSemianualMediaUsecase(
                    $classroom->id,
                    $gradeResult,
                    $gradeRules,
                    $count
                );
                $usecaseSemiMedia->exec();

                if ($gradeResult->enrollmentFk->isActive()) {
                    $usecase = new ChageStudentStatusByGradeUsecase(
                        $gradeResult,
                        $gradeRules,
                        count($std['grades']),
                        $frequency
                    );
                    $usecase->exec();
                }
            } else {
                $gradeResult->situation = "MATRICULADO";
                $gradeResult->final_media = null;
            }

            if($hasAllValues && (isset($std["finalConcept"]) && $std["finalConcept"] != "")) {
                $gradeResult->situation = "APROVADO";
            }
            $gradeResult->save();

            $time_elapsed_secs = microtime(true) - $start;
            Yii::log($std['enrollmentId']." - ". $time_elapsed_secs/60, CLogger::LEVEL_INFO);
        }

        echo CJSON::encode(["valid" => true]);
    }

    public function actionGetReportCardGrades()
    {
        $criteria = new CDbCriteria;
        $criteria->alias = "se";
        $criteria->join = "join student_identification si on si.id = se.student_fk";
        $criteria->condition = "classroom_fk = :classroom_fk";
        $criteria->params = array(':classroom_fk' => $_POST["classroom"]);
        $criteria->order = "se.daily_order, si.name";
        $studentEnrollments = StudentEnrollment::model()->findAll($criteria);


        if ($studentEnrollments != null) {
            $result["students"] = [];
            foreach ($studentEnrollments as $studentEnrollment) {

                // TODO: Mudar lógica de criação de tabela para turmas multiseriadas
                // $stage = isset($studentEnrollment->edcenso_stage_vs_modality_fk)
                //     ? $studentEnrollment->edcenso_stage_vs_modality_fk :
                //     $studentEnrollment->classroomFk->edcenso_stage_vs_modality_fk;

                $unities = GradeUnity::model()->findAll(
                    "edcenso_stage_vs_modality_fk = :stageId and (type = :type or type = :type2 or type = :type3)",
                    [
                        ":stageId" => $studentEnrollment->classroomFk->edcenso_stage_vs_modality_fk,
                        ":type" => GradeUnity::TYPE_UNITY,
                        ":type2" => GradeUnity::TYPE_UNITY_WITH_RECOVERY,
                        ":type3" => GradeUnity::TYPE_UNITY_BY_CONCEPT,
                    ]
                );
                $rules = GradeRules::model()->find(
                    [
                        "select" => "rule_type",
                        "condition" => "edcenso_stage_vs_modality_fk = :stageId",
                        "params" => [":stageId" => $studentEnrollment->classroomFk->edcenso_stage_vs_modality_fk]
                    ]
                );
                if($rules->rule_type == "C") {
                    $concepts = GradeConcept::model()->findAll();
                    $result["concepts"] = CHtml::listData($concepts, 'id', 'name');
                }

                $arr = [];
                $arr["enrollmentId"] = $studentEnrollment->id;
                $arr["daily_order"] = $studentEnrollment->daily_order;
                $arr["studentName"] = $studentEnrollment->studentFk->name;
                $arr["grades"] = [];
                $arr["faults"] = [];

                $discipline = Yii::app()->request->getPost("discipline");

                $gradeResult = GradeResults::model()->find(
                    "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
                    ["enrollment_fk" => $studentEnrollment->id, "discipline_fk" => $discipline]
                );

                for ($key = 0; $key < 3; $key++) {
                    $index = $key + 1;
                    array_push($arr["grades"], [
                        "value" => $gradeResult["grade_" . $index],
                        "concept" => $gradeResult["grade_concept_" . $index],
                        "faults" => $gradeResult["grade_faults_" . $index],
                        "givenClasses" => $gradeResult["given_classes_" . $index]
                    ]);
                }

                $arr["finalMedia"] = $gradeResult->final_media ?? "";
                $arr["recFinal"] = $gradeResult->rec_final ?? "";
                $arr["finalConcept"] = $gradeResult->final_concept;

                $arr["situation"] = $studentEnrollment->getCurrentStatus();
                if ($studentEnrollment->isActive()) {
                    $arr["situation"] = ($gradeResult->situation == null) ? "" : $gradeResult->situation;
                }



                $result["unities"] = $unities;
                $result["rule"] = $rules->rule_type;
                array_push($result["students"], $arr);
            }

            $result["valid"] = true;
        } else {
            $result["valid"] = false;
            $result["message"] = "Não há estudantes matriculados na turma.";
        }
        echo CJSON::encode($result);
    }

    public function actionGetGradesRelease()
    {
        $criteria = new CDbCriteria;
        $criteria->alias = "se";
        $criteria->join = "join student_identification si on si.id = se.student_fk";
        $criteria->condition = "classroom_fk = :classroom_fk";
        $criteria->params = array(':classroom_fk' => $_POST["classroom"]);
        $criteria->order = "se.daily_order, si.name";
        $studentEnrollments = StudentEnrollment::model()->findAll($criteria);


        if ($studentEnrollments != null) {
            $result["students"] = [];
            foreach ($studentEnrollments as $studentEnrollment) {

                $unities = GradeUnity::model()->findAll(
                    "edcenso_stage_vs_modality_fk = :stageId and (type = :type or type = :type2 or type = :type3)",
                    [
                        ":stageId" => $studentEnrollment->classroomFk->edcenso_stage_vs_modality_fk,
                        ":type" => GradeUnity::TYPE_UNITY,
                        ":type2" => GradeUnity::TYPE_UNITY_WITH_RECOVERY,
                        ":type3" => GradeUnity::TYPE_UNITY_BY_CONCEPT,
                    ]
                );
                $rules = GradeRules::model()->find(
                    [
                        "select" => "rule_type, has_final_recovery",
                        "condition" => "edcenso_stage_vs_modality_fk = :stageId",
                        "params" => [":stageId" => $studentEnrollment->classroomFk->edcenso_stage_vs_modality_fk]
                    ]
                );
                $concepts = GradeConcept::model()->findAll();
                $result["concepts"] = CHtml::listData($concepts, 'id', 'name');

                $arr = [];
                $arr["enrollmentId"] = $studentEnrollment->id;
                $arr["daily_order"] = $studentEnrollment->daily_order;
                $arr["studentName"] = $studentEnrollment->studentFk->name;
                $arr["grades"] = [];
                $arr["faults"] = [];

                $discipline = Yii::app()->request->getPost("discipline");

                $gradeResult = GradeResults::model()->find(
                    "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
                    ["enrollment_fk" => $studentEnrollment->id, "discipline_fk" => $discipline]
                );

                foreach ($unities as $key => $value) {
                    $index = $key + 1;
                    array_push($arr["grades"], [
                        "value" => $gradeResult["grade_" . $index],
                        "concept" => $gradeResult["grade_concept_" . $index],
                        "faults" => $gradeResult["grade_faults_" . $index],
                        "givenClasses" => $gradeResult["given_classes_" . $index]
                    ]);
                }

                $arr["finalMedia"] = $gradeResult->final_media ?? "";
                $arr["recFinal"] = $gradeResult->rec_final ?? "";

                $arr["recSemianual1"] = $gradeResult->rec_semianual_1 ?? "";
                $arr["recSemianual2"] = $gradeResult->rec_semianual_2 ?? "";
                $arr["semianualMedia"] = $gradeResult->semianual_media ?? "";

                $arr["finalConcept"] = $gradeResult->final_concept;

                $arr["situation"] = $studentEnrollment->getCurrentStatus();
                if ($studentEnrollment->isActive()) {
                    $arr["situation"] = ($gradeResult->situation == null) ? "" : $gradeResult->situation;
                }



                $result["unities"] = $unities;
                $result["rule"] = $rules->rule_type;
                $result["hasRecovery"] = $rules->has_final_recovery;
                array_push($result["students"], $arr);
            }

            $result["valid"] = true;
        } else {
            $result["valid"] = false;
            $result["message"] = "Não há estudantes matriculados na turma.";
        }
        echo CJSON::encode($result);
    }

    public function actionSaveGrades()
    {
        $students = Yii::app()->request->getPost("students");
        $disciplineId = Yii::app()->request->getPost("discipline");
        $classroomId = Yii::app()->request->getPost("classroom");
        $isConcept = Yii::app()->request->getPost("isConcept");

        foreach ($students as $student) {
            foreach ($student["grades"] as $grade) {

                $gradeObject = Grade::model()->findByPk($grade["id"]);

                if ($gradeObject == null) {
                    $gradeObject = new Grade();
                    $gradeObject->enrollment_fk = $student["enrollmentId"];
                    $gradeObject->discipline_fk = $disciplineId;
                    $gradeObject->grade_unity_modality_fk = $grade["modalityId"];
                }
                if (!$isConcept) {
                    $gradeObject->grade = isset($grade["value"]) && $grade["value"] !== "" ? $grade["value"] : 0;
                } else {
                    $gradeObject->grade_concept_fk = $grade["concept"];
                }
                $gradeObject->save();

            }
        }

        self::saveGradeResults($classroomId, $disciplineId);
        echo json_encode(["valid" => true]);

    }

    public function actionGetGrades()
    {

        Yii::import("application.domain.grades.usecases.GetStudentGradesByDisciplineUsecase");

        $classroomId = Yii::app()->request->getPost("classroom");
        $disciplineId = Yii::app()->request->getPost("discipline");

        try {
            $usecase = new GetStudentGradesByDisciplineUsecase($classroomId, $disciplineId);
            $result = $usecase->exec();
            echo CJSON::encode($result);
        } catch (Exception $e) {
            header('HTTP/1.1 500 ' . $e->getMessage());
            echo json_encode(['valid' => false, 'message' => $e->getMessage()]);
        }

    }

    public function actionCalculateFinalMedia()
    {
        $classroomId = Yii::app()->request->getPost("classroom");
        $disciplineId = Yii::app()->request->getPost("discipline");

        $classroom = Classroom::model()->with("activeStudentEnrollments.studentFk")->findByPk($classroomId);

        $gradeRules = GradeRules::model()->findByAttributes([
            "edcenso_stage_vs_modality_fk" => $classroom->edcenso_stage_vs_modality_fk
        ]);


        foreach ($classroom->activeStudentEnrollments as $enrollment) {
            $gradeUnities = new GetGradeUnitiesByDisciplineUsecase($gradeRules->edcenso_stage_vs_modality_fk);
            $countUnities = $gradeUnities->execCount();

            $gradeResult = (new GetStudentGradesResultUsecase($enrollment->id, $disciplineId))->exec();
            (new CalculateFinalMediaUsecase($gradeResult, $gradeRules, $countUnities))->exec();
            (new ChageStudentStatusByGradeUsecase($gradeResult, $gradeRules, $countUnities))->exec();

        }

    }


    public static function saveGradeResults($classroomId, $disciplineId)
    {
        $usecase = new CalculateGradeResultsUsecase(
            $classroomId,
            $disciplineId
        );
        $usecase->exec();
    }
}
