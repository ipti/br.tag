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
                    'getReportCardGrades',
                    'saveGradesReportCard'
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

        foreach ($students as $std) {
            $mediaFinal = 0;
            $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $std['enrollmentId'], "discipline_fk" => $discipline]);
            if (!isset($gradeResult)) {
                $gradeResult = new GradeResults;
            }

            $gradeResult->enrollment_fk = $std['enrollmentId'];
            $gradeResult->discipline_fk = $discipline;

            foreach ($std['grades'] as $key => $value) {
                $index = $key + 1;
                $gradeResult->{"grade_" . $index} = $std['grades'][$key]['value'];
                $gradeResult->{"grade_faults_" . $index} = $std['grades'][$key]['faults'];
                $gradeResult->{"given_classes_" . $index} = $std['grades'][$key]['givenClasses'];


                $mediaFinal += floatval($gradeResult->attributes["grade_" . $index] * ($index == 3 ? 2 : 1));
            }

            $gradeResult->final_media = number_format($mediaFinal / 4, 1);
            if (!$gradeResult->validate()) {
                die(print_r($gradeResult->getErrors()));
            }
            $gradeResult->save();
        }

        echo json_encode(["valid" => true]);
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
            $result = [];
            $result["students"] = [];
            $arr = [];
            foreach ($studentEnrollments as $studentEnrollment) {
                $arr["enrollmentId"] = $studentEnrollment->id;
                $arr["daily_order"] = $studentEnrollment->daily_order;
                $arr["studentName"] = $studentEnrollment->studentFk->name;
                $arr["grades"] = [];
                $arr["faults"] = [];

                $gradeResult = GradeResults::model()->find(
                    "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
                    ["enrollment_fk" => $studentEnrollment->id, "discipline_fk" => $_POST["discipline"]]
                );

                for ($index = 1; $index <= 3; $index++) {
                    array_push(
                        $arr["grades"],
                        [
                            "value" => $gradeResult["grade_" . $index],
                            "faults" => $gradeResult["grade_faults_" . $index],
                            "givenClasses" => $gradeResult["given_classes_" . $index]
                        ]
                    );
                }

                $arr["finalMedia"] = $gradeResult != null ? $gradeResult->final_media : "";
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

                $gradeObject = Grade::model()->find('id = :id', [":id" => $grade->id]);

                if ($gradeObject == null) {
                    $gradeObject = new Grade();
                    $gradeObject->enrollment_fk = $student["enrollmentId"];
                    $gradeObject->discipline_fk = $disciplineId;
                    $gradeObject->grade_unity_modality_fk = $grade["modalityId"];
                }
                if (!$isConcept) {
                    $gradeObject->grade = $grade["value"] ?? "";
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
        $criteria = new CDbCriteria;
        $criteria->alias = "se";
        $criteria->join = "join student_identification si on si.id = se.student_fk";
        $criteria->condition = "classroom_fk = :classroom_fk";
        $criteria->params = array(':classroom_fk' => $_POST["classroom"]);
        $criteria->order = "si.name";
        $studentEnrollments = StudentEnrollment::model()->findAll($criteria);

        if ($studentEnrollments != null) {
            $criteria = new CDbCriteria;
            $criteria->alias = "gum";
            $criteria->join = "join grade_unity gu on gu.id = gum.grade_unity_fk";
            $criteria->condition = "edcenso_stage_vs_modality_fk = :stage";
            $criteria->params = array(':stage' => $studentEnrollments[0]->classroomFk->edcenso_stage_vs_modality_fk);
            $gradeModalities = GradeUnityModality::model()->findAll($criteria);

            if ($gradeModalities != null) {
                $conceptOptions = GradeConcept::model()->findAll();
                foreach ($conceptOptions as $conceptOption) {
                    $result["conceptOptions"][$conceptOption->id] = $conceptOption->name;
                }
                $result["isUnityConcept"] = $gradeModalities[0]->gradeUnityFk->type == "UC";
                $unityName = $gradeModalities[0]->gradeUnityFk->name;
                $unityColspan = 0;
                $result["unityColumns"] = [];
                $result["modalityColumns"] = [];
                foreach ($gradeModalities as $index => $gradeModality) {
                    array_push($result["modalityColumns"], $gradeModality->name);
                    if ($unityName == $gradeModality->gradeUnityFk->name) {
                        $unityColspan++;
                    } else {
                        array_push($result["unityColumns"], ["name" => $unityName, "colspan" => $unityColspan]);
                        $unityName = $gradeModality->gradeUnityFk->name;
                        $unityColspan = 1;
                    }
                    if ($index == count($gradeModalities) - 1) {
                        array_push($result["unityColumns"], ["name" => $unityName, "colspan" => $unityColspan]);
                    }
                }

                $result["students"] = [];
                foreach ($studentEnrollments as $studentEnrollment) {
                    $arr["enrollmentId"] = $studentEnrollment->id;
                    $arr["studentName"] = $studentEnrollment->studentFk->name;
                    $arr["grades"] = [];
                    foreach ($gradeModalities as $gradeModality) {
                        $gradeValue = "";
                        $gradeConcept = "";
                        $modalityId = $gradeModality->id;
                        foreach ($gradeModality->grades as $grade) {
                            if ($grade->enrollment_fk == $studentEnrollment->id && $grade->discipline_fk == $_POST["discipline"]) {
                                $gradeValue = $grade->grade;
                                $gradeConcept = $grade->grade_concept_fk;
                                break;
                            }
                        }
                        array_push($arr["grades"], ["value" => $gradeValue, "concept" => $gradeConcept, "modalityId" => $modalityId]);
                    }
                    $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $studentEnrollment->id, "discipline_fk" => $_POST["discipline"]]);
                    if (!$result["isUnityConcept"]) {
                        $arr["finalMedia"] = $gradeResult != null ? $gradeResult->final_media : "";
                    }
                    $arr["situation"] = $gradeResult != null ? ($gradeResult->situation != null ? $gradeResult->situation : "") : "";
                    array_push($result["students"], $arr);
                }

                $result["valid"] = true;
            } else {
                $result["valid"] = false;
                $result["message"] = "Ainda não foi construída uma estrutura de unidades e avaliações para esta turma.";
            }
        } else {
            $result["valid"] = false;
            $result["message"] = "Não há estudantes matriculados na turma.";
        }
        echo json_encode($result);
    }

    public function actionCalculateFinalMedia()
    {
        self::saveGradeResults($_POST["classroom"], $_POST["discipline"]);
    }

    public static function saveGradeResults($classroom, $discipline)
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->join = "join edcenso_stage_vs_modality esvm on gu.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->join .= " join classroom c on c.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->condition = "c.id = :classroom";
        $criteria->params = array(":classroom" => $classroom); // $gradeUnitiesByClassroom = GradeUnity::model()->findAll();

        $gradeUnitiesByClassroom = GradeUnity::model()->findAll($criteria);


        $studentEnrollments = StudentEnrollment::model()->findAll("classroom_fk = :classroom_fk", ["classroom_fk" => $classroom]);

        foreach ($studentEnrollments as $studentEnrollment) {

            $gradeResult = GradeResults::model()->find(
                "enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk",
                [
                    "enrollment_fk" => $studentEnrollment->id,
                    "discipline_fk" => $discipline
                ]
            );
            if ($gradeResult == null) {
                $gradeResult = new GradeResults();
                $gradeResult->enrollment_fk = $studentEnrollment->id;
                $gradeResult->discipline_fk = $discipline;
            }

            if ($gradeUnitiesByClassroom[0]->type != "UC") {
                //notas sem ser conceito

                $arr["grades"] = [];
                foreach ($gradeUnitiesByClassroom as $gradeUnity) {
                    array_push($arr["grades"], $gradeUnity->type == "UR"
                        ? ["unityId" => $gradeUnity->id, "unityGrade" => "", "unityRecoverGrade" => "", "gradeUnityType" => $gradeUnity->type]
                        : ["unityId" => $gradeUnity->id, "unityGrade" => "", "gradeUnityType" => $gradeUnity->type]);
                }

                $criteria->select = "distinct gu.id, gu.*";
                $criteria->join = "join grade_unity_modality gum on gum.grade_unity_fk = gu.id";
                $criteria->join .= " join grade g on g.grade_unity_modality_fk = gum.id";
                $criteria->condition = "g.discipline_fk = :discipline_fk and enrollment_fk = :enrollment_fk";
                $criteria->params = array(":discipline_fk" => $discipline, ":enrollment_fk" => $studentEnrollment->id);
                $criteria->order = "gu.id";
                $gradeUnitiesByDiscipline = GradeUnity::model()->findAll($criteria);


                foreach ($gradeUnitiesByDiscipline as $gradeUnity) {
                    $key = array_search($gradeUnity->id, array_column($arr["grades"], 'unityId'));
                    $arr["grades"][$key] = self::getUnidadeValues($gradeUnity, $studentEnrollment->id, $discipline);
                }

                //Cálculo da média final
                $arr["finalMedia"] = "";
                $sums = 0;
                $sumsCount = 0;
                $arr["semesterMedias"] = [];
                $hasRF = false;
                $recSemIndex = 0;
                $gradeIndex = 0;
                $allNormalUnitiesFilled = true;
                $lastRSFilledGrade = "";
                $rfFilled = true;
                foreach ($arr["grades"] as $grade) {
                    switch ($grade["gradeUnityType"]) {

                        case "UR":
                            if ($grade["unityGrade"] != "" || $grade["unityRecoverGrade"] != "") {
                                $sums += $grade["unityRecoverGrade"] > $grade["unityGrade"] ? $grade["unityRecoverGrade"] : $grade["unityGrade"];
                            } else {
                                $allNormalUnitiesFilled = false;
                            }
                            $sumsCount++;

                            $resultGradeResult = $grade["unityGrade"] != "" ? number_format($grade["unityGrade"], 1) : null;

                            $gradeResult["grade_" . ($gradeIndex + 1)] = $resultGradeResult <= 10.0 ? $resultGradeResult : 10.0;

                            $gradeResult["rec_bim_" . ($gradeIndex + 1)] = $grade["unityRecoverGrade"] != "" ? number_format($grade["unityRecoverGrade"], 1) : null;
                            $gradeIndex++;
                            break;
                        case "RS":
                            if ($sums > 0) {
                                $semesterMedia = $sums / $sumsCount;
                                $semesterRecoverMedia = ($semesterMedia + $grade["unityGrade"]) / 2;
                                array_push($arr["semesterMedias"], $semesterMedia > $semesterRecoverMedia ? $semesterMedia : $semesterRecoverMedia);
                            } else {
                                array_push($arr["semesterMedias"], 0);
                            }
                            $sums = 0;
                            $sumsCount = 0;

                            $lastRSFilledGrade = $grade["unityGrade"];

                            $gradeResult["rec_sem_" . ($recSemIndex + 1)] = $grade["unityGrade"] != "" ? number_format($grade["unityGrade"], 1) : null;
                            $recSemIndex++;
                            break;
                        case "RF":
                            $hasRF = true;
                            if ($sums > 0) {
                                $media = $sums / $sumsCount;
                                array_push($arr["semesterMedias"], $media);
                            }

                            $finalMedia = array_sum($arr["semesterMedias"]) / count($arr["semesterMedias"]);
                            if ($grade["unityGrade"] != "") {
                                $finalRecoverMedia = ($finalMedia + $grade["unityGrade"]) / 2;
                                $finalMedia = number_format($finalRecoverMedia, 1);
                            } else {
                                $finalMedia = number_format($finalMedia, 1);
                                $rfFilled = false;
                            }

                            $gradeResult["rec_final"] = $grade["unityGrade"] != "" ? number_format($grade["unityGrade"], 1) : null;
                            break;
                        case "U":
                        default:
                            if ($grade["unityGrade"] != "") {
                                $sums += $grade["unityGrade"];
                            } else {
                                $allNormalUnitiesFilled = false;
                            }
                            $sumsCount++;

                            $resultGradeResult = $grade["unityGrade"] != "" ? number_format($grade["unityGrade"], 1) : null;

                            $gradeResult["grade_" . ($gradeIndex + 1)] = $resultGradeResult <= 10.0 ? $resultGradeResult : 10.0;

                            $gradeIndex++;
                            break;
                    }
                }
                if (!$hasRF) {
                    if ($sums > 0) {
                        $media = $sums / $sumsCount;
                        array_push($arr["semesterMedias"], $media);
                    }
                    $finalMedia = number_format(array_sum($arr["semesterMedias"]) / count($arr["semesterMedias"]), 1);
                }

                //traz a situação do aluno (se null, aprovado, recuperação ou reprovado)
                $gradeRules = GradeRules::model()->find("edcenso_stage_vs_modality_fk = :stage", [":stage" => $gradeUnitiesByClassroom[0]->edcenso_stage_vs_modality_fk]);
                $situation = null;
                if ($allNormalUnitiesFilled) {
                    if ($finalMedia >= $gradeRules->approvation_media) {
                        $situation = "Aprovado";
                    } else {
                        if ($hasRF) {
                            if ($rfFilled) {
                                if ($finalMedia >= $gradeRules->final_recover_media) {
                                    $situation = "Aprovado";
                                } else {
                                    $situation = "Reprovado";
                                }
                            } else {
                                $situation = "Recuperação";
                            }
                        } elseif ($recSemIndex > 0) {
                            if ($lastRSFilledGrade !== "") {
                                $situation = "Reprovado";
                            } else {
                                $situation = "Recuperação";
                            }
                        } else {
                            $situation = "Reprovado";
                        }
                    }
                }
                $gradeResult->situation = $situation;

                $gradeResult->final_media = $finalMedia;
                $gradeResult->save();
            } else {
                //notas por conceito
                $index = 0;
                foreach ($gradeUnitiesByClassroom as $gradeUnity) {
                    foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
                        $enrollmentId = $studentEnrollment->id;
                        $disciplineId = $discipline;
                        $studentGrades = array_filter(
                            $gradeUnityModality->grades,
                            function ($grade) use ($enrollmentId, $disciplineId) {
                                return $grade->enrollment_fk === $enrollmentId && $grade->discipline_fk === $disciplineId;
                            }
                        );
                        foreach ($studentGrades as $grade) {
                            $gradeResult["grade_concept_" . ($index + 1)] = $grade->gradeConceptFk->acronym;
                            $index++;
                        }
                    }
                }
                $gradeResult->situation = "Aprovado";
                $gradeResult->save();
            }


            //Mudar status da matrícula
            //1 = Matriculado; 6 = Aprovado; 8 = Reprovado
            if ($studentEnrollment->status == "1" || $studentEnrollment->status == "6" || $studentEnrollment->status == "8") {
                $allGradesFilled = true;
                $situation = "Aprovado";
                foreach ($studentEnrollment->gradeResults as $gradeResult) {
                    if ($gradeResult->situation == null) {
                        $allGradesFilled = false;
                    } elseif ($gradeResult->situation == "Reprovado") {
                        $situation = "Reprovado";
                        break;
                    } elseif ($gradeResult->situation == "Recuperação") {
                        $situation = "Matriculado";
                        break;
                    }
                }
                if ($allGradesFilled && $situation == "Aprovado") {
                    $studentEnrollment->status = 6;
                } elseif ($situation == "Reprovado") {
                    $studentEnrollment->status = 8;
                } else {
                    $studentEnrollment->status = 1;
                }
                $studentEnrollment->save();
            }

        }
    }

    public static function getUnidadeValues($gradeUnity, $enrollmentId, $discipline)
    {
        $unityGrade = "";
        $unityRecoverGrade = "";
        $turnedEmptyToZero = false;
        $weightsSum = 0;
        $commonModalitiesCount = 0;

        foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
            if ($gradeUnityModality->type == "C") {
                $commonModalitiesCount++;
                $weightsSum += $gradeUnityModality->weight;
            }

            $studentGrades = array_filter(
                $gradeUnityModality->grades,
                function ($grade) use ($enrollmentId, $discipline) {
                    return $grade->enrollment_fk === $enrollmentId && $grade->discipline_fk === $discipline;
                }
            );

            foreach ($studentGrades as $grade) {
                if ($gradeUnityModality->type == "C") {
                    if (!$turnedEmptyToZero) {
                        $unityGrade = 0;
                        $turnedEmptyToZero = true;
                    }
                    $unityGrade += $gradeUnity->gradeCalculationFk->name === "Peso"
                        ? $grade->grade * $gradeUnityModality->weight
                        : $grade->grade;
                } else {
                    $unityRecoverGrade = (float) $grade->grade;
                }
            }
        }
        if ($unityGrade !== "") {
            if ($gradeUnity->gradeCalculationFk->name === "Média") {
                $unityGrade = number_format($unityGrade / $commonModalitiesCount, 2);
            } elseif ($gradeUnity->gradeCalculationFk->name === "Peso") {
                $unityGrade = number_format($unityGrade / $weightsSum, 2);
            }
        }
        return $gradeUnity->type == "UR"
            ? ["unityId" => $gradeUnity->id, "unityGrade" => $unityGrade, "unityRecoverGrade" => $unityRecoverGrade, "gradeUnityType" => $gradeUnity->type]
            : ["unityId" => $gradeUnity->id, "unityGrade" => $unityGrade, "gradeUnityType" => $gradeUnity->type];
    }
}
