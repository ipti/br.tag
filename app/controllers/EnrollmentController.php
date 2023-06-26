<?php

class EnrollmentController extends Controller
{
    //@done s1 - Validar Ano Letivo
    //@done s1 - Verificar erro - Ao matricular um aluno que acabou de ser cadastrado não está salvando eno bancoo e aparece a mensagem de 'Aluno ja matriculado'
    //@done s1 - Filtrar aluno e turma por escola

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', "updatedependencies",
                    'delete', 'getmodalities', 'grades', 'getGrades', 'saveGrades', 'getDisciplines', 'calculateFinalMedia'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionUpdateDependencies()
    {
        //$enrollment = new StudentEnrollment;
        //$enrollment->attributes = $_POST["StudentEnrollment"];
        //$students = StudentIdentification::model()->findAll('school_inep_id_fk=:id order by name ASC', array(':id' => $enrollment->school_inep_id_fk));
        //$students = CHtml::listData($students, 'id', 'name');

        $classrooms = Classroom::model()->findAllByAttributes(array("school_year" => Yii::app()->user->year, "school_inep_fk" => Yii::app()->user->school));
        //$classrooms = CHtml::listData($classrooms, 'id', 'name');

        /* $result['Students'] = CHtml::tag('option', array('value' => null), 'Selecione um Aluno', true);
          foreach ($students as $value => $name) {
          $result['Students'] .= CHtml::tag('option', array('value' => $value, ($enrollment->student_fk == $value ? "selected" : "deselected") => ($enrollment->student_fk == $value ? "selected" : "deselected")), CHtml::encode($name), true);
          } */
        $class = new Classroom();
        $result['Classrooms'] = CHtml::tag('option', array('value' => 0), 'Selecione uma Turma', true);
        foreach ($classrooms as $class) {
            if (strpos($class->edcensoStageVsModalityFk->name, 'Multi') !== false) {
                $multi = 1;
            } else {
                $multi = 0;
            }
            $result['Classrooms'] .= CHtml::tag('option', array('value' => $class->id, 'id' => $multi), CHtml::encode($class->name), true);
        }

        echo json_encode($result);
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new StudentEnrollment;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $classrooms = Classroom::model()->findAll(
            "school_year = :year AND school_inep_fk = :school order by name",
            [
                ':year' => Yii::app()->user->year,
                ':school' => Yii::app()->user->school,
            ]
        );

        if (isset($_POST['StudentEnrollment'])) {
            $model->attributes = $_POST['StudentEnrollment'];
            if ($model->validate()) {
                $model->classroom_inep_id = Classroom::model()->findByPk($model->classroom_fk)->inep_id;
                $model->student_inep_id = StudentIdentification::model()->findByPk($model->student_fk)->inep_id;
                try {
                    if ($model->save()) {
                        Log::model()->saveAction("enrollment", $model->id, "C", $model->studentFk->name . "|" . $model->classroomFk->name);
                        Yii::app()->user->setFlash('success', Yii::t('default', 'Aluno matriculado com sucesso!'));
                        $this->redirect(array('index'));
                    }
                } catch (Exception $exc) {
                    $model->addError('student_fk', Yii::t('default', 'Student Fk') . ' ' . Yii::t('default', 'already enrolled in this classroom.'));
                    $model->addError('classroom_fk', Yii::t('default', 'Classroom') . ' ' . Yii::t('default', 'already have in this student enrolled.'));
                    //echo $exc->getTraceAsString();

                }
            } else {
                unset($model->s);
            }
        }

        $this->render('create', array(
            'model' => $model,
            'classrooms' => $classrooms,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);        

        $modelStudentIdentification = StudentIdentification::model()->find('inep_id="' . $model->student_inep_id . '"');
        if ($model->student_fk == NULL && $model->classroom_fk == NULL) {
            $model->student_fk = $modelStudentIdentification->id;
            $model->classroom_fk = Classroom::model()->find('inep_id="' . $model->classroom_inep_id . '"')->id;
        }

        $isAdmin = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id);

        $classrooms = [];

        if ($isAdmin) {
            $classrooms = Classroom::model()->findAll(
                "school_year = :year order by name",
                [
                    ':year' => Yii::app()->user->year,
                ]
            );
        } else {
            $classrooms = Classroom::model()->findAll(
                "school_year = :year AND school_inep_fk = :school order by name",
                [
                    ':year' => Yii::app()->user->year,
                    ':school' => $model->school_inep_id_fk,
                ]
            );
        }

        if (isset($_POST['StudentEnrollment'])) {
            if ($model->validate()) {
                $model->attributes = $_POST['StudentEnrollment'];
                $model->school_inep_id_fk = Classroom::model()->findByPk([$_POST['StudentEnrollment']["classroom_fk"]])->school_inep_fk;
                if ($model->save()) {
                    Log::model()->saveAction("enrollment", $model->id, "U", $model->studentFk->name . "|" . $model->classroomFk->name);
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Matrícula alterada com sucesso!'));
                    // $this->redirect(array('student/'));
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
            'modelStudentIdentification' => $modelStudentIdentification,
            'classrooms' => $classrooms
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {

        $model = $this->loadModel($id);
        FrequencyAndMeanByDiscipline::model()->deleteAll("enrollment_fk = :enrollment_fk", ["enrollment_fk" => $id]);
        FrequencyByExam::model()->deleteAll("enrollment_fk = :enrollment_fk", ["enrollment_fk" => $id]);
        if ($model->delete()) {
            Log::model()->saveAction("enrollment", $model->id, "D", $model->studentFk->name . "|" . $model->classroomFk->name);
            Yii::app()->user->setFlash('success', Yii::t('default', "A Matrícula de " . $model->studentFk->name . " foi excluída com sucesso!"));
            $this->redirect(array('student/index'));
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

//		if(Yii::app()->request->isPostRequest)
//		{
//                    
//			// we only allow deletion via POST request
//			$this->loadModel($id)->delete();
//
//			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//			if(!isset($_GET['ajax']))
//				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//		}
//		else
//			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $query = StudentEnrollment::model()->findAll();
        $model = new StudentEnrollment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['StudentEnrollment'])) {
            $model->attributes = $_GET['StudentEnrollment'];
        }

        $school = Yii::app()->user->school;

        $criteria = new CDbCriteria;
        $criteria->compare('school_inep_id_fk', "'$school'");
        $dataProvider = new CActiveDataProvider('StudentEnrollment', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => count($query),
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'model' => $model
        ));
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
                where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name")
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
                where stage_fk = :stage_fk and school_year = :year order by ed.name")
                ->bindParam(":stage_fk", $classroom->edcenso_stage_vs_modality_fk)
                ->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($classr as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }

    public function actionSaveGrades()
    {
        // $hasFinalMediaCalculated = false;
        foreach ($_POST["students"] as $student) {
            foreach ($student["grades"] as $grade) {
                if ($grade["value"] != "" || ($_POST["isConcept"] == "1" && $grade["concept"] != "")) {

                    $gradeObject = Grade::model()->find("enrollment_fk = :enrollment and grade_unity_modality_fk = :modality and discipline_fk = :discipline_fk", [":enrollment" => $student["enrollmentId"], ":modality" => $grade["modalityId"], ":discipline_fk" => $_POST["discipline"]]);
                    if ($gradeObject == null) {
                        $gradeObject = new Grade();
                        $gradeObject->enrollment_fk = $student["enrollmentId"];
                        $gradeObject->discipline_fk = $_POST["discipline"];
                        $gradeObject->grade_unity_modality_fk = $grade["modalityId"];
                    }
                    if (!$_POST["isConcept"]) {
                        $gradeObject->grade = $grade["value"];
                    } else {
                        $gradeObject->grade_concept_fk = $grade["concept"];
                    }
                    $gradeObject->save();
                } else {
                    Grade::model()->deleteAll("enrollment_fk = :enrollment and grade_unity_modality_fk = :modality", [":enrollment" => $student["enrollmentId"], ":modality" => $grade["modalityId"]]);
                }
            }
            // $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $student["enrollmentId"], "discipline_fk" => $_POST["discipline"]]);
            // if ($gradeResult != null) {
            //     $hasFinalMediaCalculated = true;
            // }
        }
        // if ($hasFinalMediaCalculated) {
        //     $this->calculateFinalMedia();
        // }
        $this->saveGradeResults();
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
                    if (!$result["isUnityConcept"]) {
                        $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $studentEnrollment->id, "discipline_fk" => $_POST["discipline"]]);
                        $arr["finalMedia"] = $gradeResult != null ? $gradeResult->final_media : "";
                    }
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
        $this->saveGradeResults();
    }

    private function saveGradeResults()
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "gu";
        $criteria->join = "join edcenso_stage_vs_modality esvm on gu.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->join .= " join classroom c on c.edcenso_stage_vs_modality_fk = esvm.id";
        $criteria->condition = "c.id = :classroom";
        $criteria->params = array(":classroom" => $_POST["classroom"]);
        $gradeUnitiesByClassroom = GradeUnity::model()->findAll($criteria);

        $studentEnrollments = StudentEnrollment::model()->findAll("classroom_fk = :classroom_fk", ["classroom_fk" => $_POST["classroom"]]);
        foreach ($studentEnrollments as $studentEnrollment) {
            if ($gradeUnitiesByClassroom[0]->type != "UC") {
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
                $criteria->params = array(":discipline_fk" => $_POST["discipline"], ":enrollment_fk" => $studentEnrollment->id);
                $criteria->order = "gu.id";
                $gradeUnitiesByDiscipline = GradeUnity::model()->findAll($criteria);


                foreach ($gradeUnitiesByDiscipline as $gradeUnity) {
                    $key = array_search($gradeUnity->id, array_column($arr["grades"], 'unityId'));
                    $arr["grades"][$key] = $this->getUnidadeValues($gradeUnity, $studentEnrollment->id, $_POST["discipline"]);
                }

                $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $studentEnrollment->id, "discipline_fk" => $_POST["discipline"]]);
                if ($gradeResult == null) {
                    $gradeResult = new GradeResults();
                    $gradeResult->enrollment_fk = $studentEnrollment->id;
                    $gradeResult->discipline_fk = $_POST["discipline"];
                }

                //Cálculo da média final
                $arr["finalMedia"] = "";
                $sums = 0;
                $sumsCount = 0;
                $arr["semesterMedias"] = [];
                $hasRF = false;
                $rawUnitiesFilled = 0;
                $recSemIndex = 0;
                $gradeIndex = 0;
                foreach ($arr["grades"] as $grade) {
                    switch ($grade["gradeUnityType"]) {
                        case "U":
                            if ($grade["unityGrade"] != "") {
                                $sums += $grade["unityGrade"];
                                $rawUnitiesFilled++;
                            }
                            $sumsCount++;

                            $gradeResult["grade_" . ($gradeIndex + 1)] = $grade["unityGrade"] != "" ? $grade["unityGrade"] : null;
                            $gradeIndex++;
                            break;
                        case "UR":
                            if ($grade["unityGrade"] != "" || $grade["unityRecoverGrade"] != "") {
                                $sums += $grade["unityRecoverGrade"] > $grade["unityGrade"] ? $grade["unityRecoverGrade"] : $grade["unityGrade"];
                                $rawUnitiesFilled++;
                            }
                            $sumsCount++;

                            $gradeResult["grade_" . ($gradeIndex + 1)] = $grade["unityGrade"] != "" ? $grade["unityGrade"] : null;
                            $gradeResult["rec_bim_" . ($gradeIndex + 1)] = $grade["unityRecoverGrade"] != "" ? $grade["unityRecoverGrade"] : null;
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

                            $gradeResult["rec_sem_" . ($recSemIndex + 1)] = $grade["unityGrade"] != "" ? $grade["unityGrade"] : null;
                            $recSemIndex++;
                            break;
                        case "RF":
                            $hasRF = true;
                            if ($sums > 0) {
                                $media = $sums / $sumsCount;
                                array_push($arr["semesterMedias"], $media);
                            }
                            $finalMedia = array_sum($arr["semesterMedias"]) / count($arr["semesterMedias"]);
                            $finalRecoverMedia = ($finalMedia + $grade["unityGrade"]) / 2;
                            $finalMedia = number_format($finalMedia > $finalRecoverMedia ? $finalMedia : $finalRecoverMedia, 2);

                            $gradeResult["rec_final"] = $grade["unityGrade"] != "" ? $grade["unityGrade"] : null;
                            break;
                    }
                }
                if (!$hasRF) {
                    if ($sums > 0) {
                        $media = $sums / $sumsCount;
                        array_push($arr["semesterMedias"], $media);
                    }
                    $finalMedia = number_format(array_sum($arr["semesterMedias"]) / count($arr["semesterMedias"]), 2);
                }

                $gradeResult->final_media = $finalMedia;
                $gradeResult->save();
            } else {
                $gradeResult = GradeResults::model()->find("enrollment_fk = :enrollment_fk and discipline_fk = :discipline_fk", ["enrollment_fk" => $studentEnrollment->id, "discipline_fk" => $_POST["discipline"]]);
                if ($gradeResult == null) {
                    $gradeResult = new GradeResults();
                    $gradeResult->enrollment_fk = $studentEnrollment->id;
                    $gradeResult->discipline_fk = $_POST["discipline"];
                }
                $index = 0;
                foreach ($gradeUnitiesByClassroom as $gradeUnity) {
                    foreach ($gradeUnity->gradeUnityModalities as $gradeUnityModality) {
                        $enrollment_id = $studentEnrollment->id;
                        $discipline = $_POST["discipline"];
                        $student_grades = array_filter(
                            $gradeUnityModality->grades,
                            function ($grade) use ($enrollment_id, $discipline) {
                                return $grade->enrollment_fk === $enrollment_id && $grade->discipline_fk === $discipline;
                            }
                        );
                        foreach($student_grades as $grade) {
                            $gradeResult["grade_concept_" . ($index + 1)] = $grade->gradeConceptFk->acronym;
                            $index++;
                        }
                    }
                }
                $gradeResult->save();
            }
        }
    }

    private function getUnidadeValues($gradeUnity, $enrollment_id, $discipline)
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

            $student_grades = array_filter(
                $gradeUnityModality->grades,
                function ($grade) use ($enrollment_id, $discipline) {
                    return $grade->enrollment_fk === $enrollment_id && $grade->discipline_fk === $discipline;
                }
            );

            foreach ($student_grades as $grade) {
                if ($gradeUnityModality->type == "C") {
                    if (!$turnedEmptyToZero) {
                        $unityGrade = 0;
                        $turnedEmptyToZero = true;
                    }
                    $unityGrade += $gradeUnity->gradeCalculationFk->name === "Peso"
                        ? $grade->grade * $gradeUnityModality->weight
                        : $grade->grade;
                } else {
                    $unityRecoverGrade = (int)$grade->grade;
                }
            }
        }
        if ($unityGrade !== "") {
            if ($gradeUnity->gradeCalculationFk->name === "Média") {
                $unityGrade = number_format($unityGrade / $commonModalitiesCount, 2);
            } else if ($gradeUnity->gradeCalculationFk->name === "Peso") {
                $unityGrade = number_format($unityGrade / $weightsSum, 2);
            }
        }
        return $gradeUnity->type == "UR"
            ? ["unityId" => $gradeUnity->id, "unityGrade" => $unityGrade, "unityRecoverGrade" => $unityRecoverGrade, "gradeUnityType" => $gradeUnity->type]
            : ["unityId" => $gradeUnity->id, "unityGrade" => $unityGrade, "gradeUnityType" => $gradeUnity->type];
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = StudentEnrollment::model()->with("studentFk")->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'student-enrollment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
