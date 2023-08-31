<?php

class CourseplanController extends Controller
{

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
            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('create', 'update', 'index', 'delete',
                    'getDisciplines', 'save', 'getCourseClasses', 'getAbilitiesInitialStructure', 'getAbilitiesNextStructure'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        if (isset($_POST['CoursePlan'])) {
            $this->actionSave();
        } else {

            $resources = CourseClassResources::model()->findAll(array('order'=>'name'));
            $types = CourseClassTypes::model()->findAll(array('order'=>'name'));

            $this->render('form', array(
                'coursePlan' => new CoursePlan(),
                'stages' => $this->getStages(),
                'resources' => $resources,
                'types' => $types,
            ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        if (isset($_POST['CoursePlan'])) {
            $this->actionSave($id);
        } else {
            $coursePlan = $this->loadModel($id);

            $resources = CourseClassResources::model()->findAll(array('order'=>'name'));
            $types = CourseClassTypes::model()->findAll(array('order'=>'name'));

            $this->render('form', array(
                'coursePlan' => $coursePlan,
                'stages' => $this->getStages(),
                'resources' => $resources,
                'types' => $types,
            ));
        }
    }

    private function getStages()
    {
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $stages = Yii::app()->db->createCommand(
                "select esvm.id, esvm.name from edcenso_stage_vs_modality esvm 
                join curricular_matrix cm on cm.stage_fk = esvm.id 
                join teaching_matrixes tm on tm.curricular_matrix_fk = cm.id
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk  
                join instructor_identification ii on ii.id = itd.instructor_fk
                where ii.users_fk = :userid and school_year = :year order by esvm.name"
            )->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":year", Yii::app()->user->year)->queryAll();
        } else {
            $stages = Yii::app()->db->createCommand("select esvm.id, esvm.name from edcenso_stage_vs_modality esvm join curricular_matrix cm on cm.stage_fk = esvm.id where school_year = :year order by esvm.name")->bindParam(":year", Yii::app()->user->year)->queryAll();
        }
        return $stages;
    }

    public function actionGetCourseClasses()
    {
        $coursePlan = CoursePlan::model()->findByPk($_POST["coursePlanId"]);
        $courseClasses = [];
        foreach ($coursePlan->courseClasses as $courseClass) {
            $order = $courseClass->order - 1;
            $courseClasses[$order] = [];
            $courseClasses[$order]["class"] = $courseClass->order;
            $courseClasses[$order]['courseClassId'] = $courseClass->id;
            $courseClasses[$order]['objective'] = $courseClass->objective;
            $courseClasses[$order]['types'] = [];
            $courseClasses[$order]['resources'] = [];
            $courseClasses[$order]['abilities'] = [];
            foreach ($courseClass->courseClassHasClassResources as $courseClassHasClassResource) {
                $resource["id"] = $courseClassHasClassResource->id;
                $resource["value"] = $courseClassHasClassResource->course_class_resource_fk;
                $resource["description"] = $courseClassHasClassResource->courseClassResourceFk->name;
                $resource["amount"] = $courseClassHasClassResource->amount;
                array_push($courseClasses[$order]['resources'], $resource);
            }
            foreach ($courseClass->courseClassHasClassTypes as $courseClassHasClassType) {
                array_push($courseClasses[$order]['types'], $courseClassHasClassType->course_class_type_fk);
            }
            foreach ($courseClass->courseClassHasClassAbilities as $courseClassHasClassAbility) {
                $ability["id"] = $courseClassHasClassAbility->courseClassAbilityFk->id;
                $ability["code"] = $courseClassHasClassAbility->courseClassAbilityFk->code;
                $ability["description"] = $courseClassHasClassAbility->courseClassAbilityFk->description;
                array_push($courseClasses[$order]['abilities'], $ability);
            }
                $courseClasses[$order]["deleteButton"] = empty($courseClass->classContents) ? "" : "js-unavailable";
            }
        echo json_encode(["data" => $courseClasses]);
    }

    public function actionGetDisciplines()
    {
        $result = [];
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $disciplines = Yii::app()->db->createCommand(
                "select ed.id from teaching_matrixes tm 
                join instructor_teaching_data itd on itd.id = tm.teaching_data_fk 
                join instructor_identification ii on ii.id = itd.instructor_fk
                join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                join edcenso_discipline ed on ed.id = cm.discipline_fk
                where ii.users_fk = :userid and cm.stage_fk = :stage_fk and school_year = :year order by ed.name")
                ->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":stage_fk", $_POST["stage"])->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($disciplines as $discipline) {
                array_push($result, ["id" => $discipline['id'], "name" => CHtml::encode($disciplinesLabels[$discipline['id']])]);
            }
        } else {
            $disciplines = Yii::app()->db->createCommand("select curricular_matrix.discipline_fk from curricular_matrix join edcenso_discipline ed on ed.id = curricular_matrix.discipline_fk where stage_fk = :stage_fk and school_year = :year order by ed.name")->bindParam(":stage_fk", $_POST["stage"])->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($disciplines as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    array_push($result, ["id" => $discipline['discipline_fk'], "name" => CHtml::encode($disciplinesLabels[$discipline['discipline_fk']])]);
                }
            }
        }
        echo json_encode($result);
    }

    public function actionGetAbilitiesInitialStructure()
    {
        $criteria = new CDbCriteria();
        $criteria->alias = "cca";
        $criteria->join = "join edcenso_stage_vs_modality esvm on esvm.id = cca.edcenso_stage_vs_modality_fk";
        if ($_POST["discipline"] !== "") {
            $criteria->condition = "cca.edcenso_discipline_fk = :discipline and parent_fk is null";
            $criteria->params = ["discipline" => $_POST["discipline"]];
            $abilities = CourseClassAbilities::model()->findAll($criteria);
        }
        $result["options"] = [];
        foreach($abilities as $i => $ability) {
            if ($i == 0) {
                $result["selectTitle"] = $ability["type"];
            }
            array_push($result["options"], ["id" => $ability->id, "code" => $ability->code, "description" => $ability->description]);
        }
        echo json_encode($result);
    }

    public function actionGetAbilitiesNextStructure()
    {
        $abilities = CourseClassAbilities::model()->findAll("parent_fk = :parent_fk", ["parent_fk" => $_POST["id"]]);
        $result["options"] = [];
        foreach($abilities as $i => $ability) {
            if ($i == 0) {
                $result["selectTitle"] = $ability["type"];
            }
            array_push($result["options"], ["id" => $ability->id, "code" => $ability->code, "description" => $ability->description]);
        }
        echo json_encode($result);
    }

    /**
     * Sabe the Course Plan, and yours course classes.
     */
    public function actionSave($id = null)
    {
        if ($id !== null) {
            $coursePlan = CoursePlan::model()->findByPk($id);
            $logSituation = "U";
        } else {
            $coursePlan = new CoursePlan;
            $coursePlan->school_inep_fk = Yii::app()->user->school;
            $coursePlan->users_fk = Yii::app()->user->loginInfos->id;
            $logSituation = "C";
        }
        $coursePlan->attributes = $_POST["CoursePlan"];
        $coursePlan->save();
        $courseClassIds = [];
        $i = 1;
        foreach ($_POST["course-class"] as $cc) {
            if ($cc["id"] == "") {
                $courseClass = new CourseClass;
                $courseClass->course_plan_fk = $coursePlan->id;
            } else {
                $courseClass = CourseClass::model()->findByPk($cc["id"]);
            }
            $courseClass->order = $i++;
            $courseClass->objective = $cc['objective'];
            $courseClass->save();

            
            array_push($courseClassIds, $courseClass->id);

            CourseClassHasClassAbility::model()->deleteAll("course_class_fk = :course_class_fk and course_class_ability_fk not in ( '" . implode("', '", $cc['ability']) . "' )", [":course_class_fk" => $courseClass->id]);
            foreach ($cc["ability"] as $abilityId) {
                $courseClassHasClassAbility = CourseClassHasClassAbility::model()->find("course_class_fk = :course_class_fk and course_class_ability_fk = :course_class_ability_fk", ["course_class_fk" => $courseClass->id, "course_class_ability_fk" => $abilityId]);
                if ($courseClassHasClassAbility == null) {
                    $courseClassHasClassAbility = new CourseClassHasClassAbility();
                    $courseClassHasClassAbility->course_class_fk = $courseClass->id;
                    $courseClassHasClassAbility->course_class_ability_fk = $abilityId;
                    $courseClassHasClassAbility->save();
                }
            }

            CourseClassHasClassType::model()->deleteAll("course_class_fk = :course_class_fk and course_class_type_fk not in ( '" . implode("', '", $cc['type']) . "' )", [":course_class_fk" => $courseClass->id]);
            foreach ($cc["type"] as $typeId) {
                $courseClassHasClassType = CourseClassHasClassType::model()->find("course_class_fk = :course_class_fk and course_class_type_fk = :course_class_type_fk", ["course_class_fk" => $courseClass->id, "course_class_type_fk" => $typeId]);
                if ($courseClassHasClassType == null) {
                    $courseClassHasClassType = new CourseClassHasClassType();
                    $courseClassHasClassType->course_class_fk = $courseClass->id;
                    $courseClassHasClassType->course_class_type_fk = $typeId;
                    $courseClassHasClassType->save();
                }
            }

            if ($cc["resource"] != null) {
                $idsArray = [];
                foreach ($cc["resource"] as $r) {
                    $courseClassHasClassResource = CourseClassHasClassResource::model()->find("id = :id", ["id" => $r["id"]]);
                    if ($courseClassHasClassResource == null) {
                        $courseClassHasClassResource = new CourseClassHasClassResource();
                        $courseClassHasClassResource->course_class_fk = $courseClass->id;
                        $courseClassHasClassResource->course_class_resource_fk = $r["value"];
                    }
                    $courseClassHasClassResource->amount = $r["amount"];
                    $courseClassHasClassResource->save();
                    array_push($idsArray, $courseClassHasClassResource->id);
                }
                CourseClassHasClassResource::model()->deleteAll("course_class_fk = :course_class_fk and id not in ( '" . implode("', '", $idsArray) . "' )", [":course_class_fk" => $courseClass->id]);
            } else {
                CourseClassHasClassResource::model()->deleteAll("course_class_fk = :course_class_fk", [":course_class_fk" => $courseClass->id]);
            }
        }

        if (empty($courseClassIds)) {
            CourseClass::model()->deleteAll("course_plan_fk = :course_plan_fk", [":course_plan_fk" => $coursePlan->id]);
        } else {
            CourseClass::model()->deleteAll("course_plan_fk = :course_plan_fk and id not in ( '" . implode("', '", $courseClassIds) . "' )", [":course_plan_fk" => $coursePlan->id]);
        }    
        Log::model()->saveAction("courseplan", $id, $logSituation, $coursePlan->name);
        Yii::app()->user->setFlash('success', Yii::t('default', 'Plano de Curso salvo com sucesso!'));
        $this->redirect(array('index'));
    }

    /**
     * Delete model.
     */
    public
    public function actionDelete($id)
    {
        $coursePlan = $this->loadModel($id);
        $isUsed = false;
        foreach ($coursePlan->courseClasses as $courseClass) {
            if (!empty($courseClass->classContents)) {
                $isUsed = true;
                break;
            }
        }
        if (!$isUsed) {
            $coursePlan->delete();
            Log::model()->saveAction("courseplan", $id, "D", $coursePlan->name);
            echo json_encode(["valid" => true, "message" => "Plano de aula excluído com sucesso!"]);
        } else {
            echo json_encode(["valid" => false, "message" => "Não se pode remover plano de aula utilizado em alguma turma."]);
        }
    }

    /**
     * Lists all models.
     */
    public
    public function actionIndex()
    {
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $dataProvider = new CActiveDataProvider('CoursePlan', array(
                'criteria' => array(
                    'condition' => 'users_fk=' . Yii::app()->user->loginInfos->id,
                ),
                'pagination' => false
            ));
        } else {
            $dataProvider = new CActiveDataProvider('CoursePlan', array(
                'pagination' => false
            ));
        }
        
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return CoursePlan the loaded model
     * @throws CHttpException
     */
    public
    function loadModel($id)
    {
        $model = CoursePlan::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CoursePlan $model the model to be validated
     */
    protected
    function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'course-plan-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
