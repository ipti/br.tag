<?php

class EnrollmentController extends Controller {
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
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'view', 'create', 'update', "updatedependencies",
                    'delete', 'getmodalities', 'grades', 'getGrades', 'saveGrades'),
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
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionUpdateDependencies() {
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

    public function actionGetModalities() {
        $stage = $_POST['Stage'];
        $where = ($stage == "0") ? "" : "stage = $stage";
        $data = EdcensoStageVsModality::model()->findAll($where);
        $data = CHtml::listData($data, 'id', 'name');

        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new StudentEnrollment;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['StudentEnrollment'])) {
            $model->attributes = $_POST['StudentEnrollment'];
            if ($model->validate()) {
                $model->classroom_inep_id = Classroom::model()->findByPk($model->classroom_fk)->inep_id;
                $model->student_inep_id = StudentIdentification::model()->findByPk($model->student_fk)->inep_id;
                try {
                    if ($model->save()) {
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
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if ($model->student_fk == NULL && $model->classroom_fk == NULL) {
            $model->student_fk = StudentIdentification::model()->find('inep_id="' . $model->student_inep_id . '"')->id;
            $model->classroom_fk = Classroom::model()->find('inep_id="' . $model->classroom_inep_id . '"')->id;
        }

        if (isset($_POST['StudentEnrollment'])) {
            if ($model->validate()) {
                $model->attributes = $_POST['StudentEnrollment'];
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Matrícula alterada com sucesso!'));
                    $this->redirect(array('index'));
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {

        $name = $this->loadModel($id)->studentFk->name;
        if ($this->loadModel($id)->delete()) {
            Yii::app()->user->setFlash('success', Yii::t('default', "A Matrícula de $name foi excluída com sucesso!"));
            $this->redirect(Yii::app()->request->urlReferrer);
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
    public function actionIndex() {
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
                'pageSize' => 12,
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
    public function actionGrades() {
        $year = Yii::app()->user->year;
        $school = Yii::app()->user->school;
        $classroom = Classroom::model()->findAllByAttributes(['school_year' => $year, 'school_inep_fk' => $school]);

        $classroom = CHtml::listData($classroom, 'id', 'name');

        $this->render('grades', ['classrooms' => $classroom]);
    }

    /**
     * 
     * Sort enrollments by sutudent name
     * 
     * @param StudentEnrollment[] $enrollments
     * @return StudentEnrollment[]
     */
    private function sortEnrollments($enrollments) {

        function sort_objects_by_total($a, $b) {
            if ($a->studentFk->name == $b->studentFk->name) {
                return 0;
            }
            return ($a->studentFk->name < $b->studentFk->name) ? - 1 : 1;
        }

        usort($enrollments, 'sort_objects_by_total');
        return $enrollments;
    }

    /**
     * 
     * Save grades
     * 
     */
    public function actionSaveGrades() {
        if (isset($_POST['grade'])) {
            $saved = true;
            $grades = $_POST['grade'];
            foreach ($grades as $eid => $disciplines) {
                foreach ($disciplines as $id => $values) {
                    $grade = Grade::model()->findByAttributes([
                        "enrollment_fk" => $eid,
                        "discipline_fk" => $id
                    ]);
                    foreach ($values as $i => $value) {
                        if ($value !== "") {
                            if ($value[strlen($value) - 1] == "." || $value[strlen($value) - 1] == ",")
                                $values[$i] .= 0;
                        }
                    }
                    $grade->grade1 = $values[0] == "" ? null : $values[0];
                    $grade->grade2 = $values[1] == "" ? null : $values[1];
                    $grade->grade3 = $values[2] == "" ? null : $values[2];
                    $grade->grade4 = $values[3] == "" ? null : $values[3];
                    $grade->recovery_grade1 = $values[4] == "" ? null : $values[4];
                    $grade->recovery_grade2 = $values[5] == "" ? null : $values[5];
                    $grade->recovery_grade3 = $values[6] == "" ? null : $values[6];
                    $grade->recovery_grade4 = $values[7] == "" ? null : $values[7];
                    $grade->recovery_final_grade = $values[8] == "" ? null : $values[8];
                    $saved = $saved && $grade->save();
                }
            }
        }
        if ($saved) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Grades saved successfully!'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'We have got an error saving grades!'));
        }
        $this->redirect(array('grades'));
    }

    /**
     * 
     * Get grades by classroom
     * 
     */
    public function actionGetGrades() {
        if (isset($_POST['classroom']) && !empty($_POST['classroom'])) {
            $cid = $_POST['classroom'];

            $classroom = Classroom::model()->findByPk($cid);
            $enrollments = $classroom->studentEnrollments;

            $enrollments = $this->sortEnrollments($enrollments);

            $return = [];

            $disciplines = Yii::app()->db->createCommand(
                            "select * from ((select c.`id` as 'classroom_id', d.id as 'discipline_id', d.`name` as 'discipline_name'

                        from `edcenso_discipline` as `d`
                        JOIN `instructor_teaching_data` `t` ON 
                                (`t`.`discipline_1_fk` = `d`.`id` 
                                || `t`.`discipline_2_fk` = `d`.`id` 
                                || `t`.`discipline_3_fk` = `d`.`id`
                                || `t`.`discipline_4_fk` = `d`.`id`
                                || `t`.`discipline_5_fk` = `d`.`id`
                                || `t`.`discipline_6_fk` = `d`.`id`
                                || `t`.`discipline_7_fk` = `d`.`id`
                                || `t`.`discipline_8_fk` = `d`.`id`
                                || `t`.`discipline_9_fk` = `d`.`id`
                                || `t`.`discipline_10_fk` = `d`.`id`
                                || `t`.`discipline_11_fk` = `d`.`id`
                                || `t`.`discipline_12_fk` = `d`.`id`
                                || `t`.`discipline_13_fk` = `d`.`id`)
                        join `classroom` as `c` on (c.id = t.classroom_id_fk)
                    ) union (
                        select c.`id` as 'classroom_id', d.id as 'discipline_id', d.`name` as 'discipline_name'
                        from `classroom` as `c`
                                join `class_board` as `cb` on (c.id = cb.classroom_fk)
                                join `edcenso_discipline` as `d` on (d.id = cb.discipline_fk)
                    )) as classroom_disciplines
                    where classroom_id = " . $cid)->queryAll();

            foreach ($enrollments as $enrollment) {
                $studentName = $enrollment->studentFk->name;
                $studentEnrId = $enrollment->studentFk->id;
                $return[$studentName] = [];
                $return[$studentName]['enrollment_id'] = $studentEnrId;
                $return[$studentName]['disciplines'] = [];
                foreach ($disciplines as $discipline) {
                    $disciplineId = $discipline['discipline_id'];
                    $grades = Grade::model()->findByAttributes([
                        'discipline_fk' => $disciplineId,
                        'enrollment_fk' => $studentEnrId,
                    ]);
                    if ($grades == null) {
                        $grades = new Grade();
                        $grades->discipline_fk = $disciplineId;
                        $grades->enrollment_fk = $studentEnrId;
                        $grades->save();
                    }
                    $n1 = $grades->grade1 == null ? "" : $grades->grade1;
                    $n2 = $grades->grade2 == null ? "" : $grades->grade2;
                    $n3 = $grades->grade3 == null ? "" : $grades->grade3;
                    $n4 = $grades->grade4 == null ? "" : $grades->grade4;
                    $r1 = $grades->recovery_grade1 == null ? "" : $grades->recovery_grade1;
                    $r2 = $grades->recovery_grade2 == null ? "" : $grades->recovery_grade2;
                    $r3 = $grades->recovery_grade3 == null ? "" : $grades->recovery_grade3;
                    $r4 = $grades->recovery_grade4 == null ? "" : $grades->recovery_grade4;
                    $rf = $grades->recovery_final_grade == null ? "" : $grades->recovery_final_grade;

                    $return[$studentName]['disciplines'][$disciplineId] = [];
                    $return[$studentName]['disciplines'][$disciplineId]['name'] = $discipline['discipline_name'];
                    $return[$studentName]['disciplines'][$disciplineId]['n1'] = $n1;
                    $return[$studentName]['disciplines'][$disciplineId]['n2'] = $n2;
                    $return[$studentName]['disciplines'][$disciplineId]['n3'] = $n3;
                    $return[$studentName]['disciplines'][$disciplineId]['n4'] = $n4;
                    $return[$studentName]['disciplines'][$disciplineId]['r1'] = $r1;
                    $return[$studentName]['disciplines'][$disciplineId]['r2'] = $r2;
                    $return[$studentName]['disciplines'][$disciplineId]['r3'] = $r3;
                    $return[$studentName]['disciplines'][$disciplineId]['r4'] = $r4;
                    $return[$studentName]['disciplines'][$disciplineId]['rf'] = $rf;
                }
            }
            echo json_encode($return);
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = StudentEnrollment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'student-enrollment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
