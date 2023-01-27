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
                    'getDisciplines', 'save'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Sabe the Course Plan, and yours course classes.
     */
    public function actionSave($data, $id = null)
    {
        $coursePlan = isset($data['CoursePlan']) ? $data['CoursePlan'] : null;
        $courseClasses = isset($data['course-class']) ? $data['course-class'] : [];
        $saved = true;

        if ($coursePlan != null && isset($coursePlan["modality_fk"], $coursePlan["discipline_fk"], $coursePlan["name"])) {

            if ($id !== null) {
                $newCoursePlan = CoursePlan::model()->findByPk($id);
                $logSituation = "U";
            } else {
                $newCoursePlan = new CoursePlan;
                $newCoursePlan->school_inep_fk = Yii::app()->user->school;
                $newCoursePlan->users_fk = Yii::app()->user->loginInfos->id;
                $logSituation = "C";
            }
            $newCoursePlan->attributes = $coursePlan;
            if ($newCoursePlan->validate()) {
                $saved = $saved && $newCoursePlan->save();
                foreach ($newCoursePlan->courseClasses as $class) {
                    $class->delete();
                }
                foreach ($courseClasses as $i => $courseClass) {
                    if (isset($courseClass['objective']) && !empty($courseClass['objective'])) {
                        $newCourseClass = new CourseClass;
                        $newCourseClass->course_plan_fk = $newCoursePlan->id;
                        $newCourseClass->order = $i;
                        $newCourseClass->objective = $courseClass['objective'];
                        $resources = [];

                        if (isset($courseClass['content'])) {
                            $resources = array_merge($courseClass['content'], $resources);
                        }

                        if (isset($courseClass['type'])) {
                            $resources = array_merge($courseClass['type'], $resources);
                        }

                        if ($newCourseClass->validate()) {
                            $saved = $saved && $newCourseClass->save();
                            foreach ($resources as $resource) {
                                $newCourseClassResource = new CourseClassHasClassResource;
                                $newCourseClassResource->course_class_fk = $newCourseClass->id;
                                $newCourseClassResource->class_resource_fk = $resource;
                                $saved = $saved && $newCourseClassResource->save();
                            }
                            if (isset($courseClass['resource'])) {
                                foreach ($courseClass['resource'] as $resource) {
                                    $newCourseClassResource = new CourseClassHasClassResource;
                                    $newCourseClassResource->course_class_fk = $newCourseClass->id;
                                    $newCourseClassResource->class_resource_fk = $resource['value'];
                                    $newCourseClassResource->amount = $resource['amount'];
                                    $saved = $saved && $newCourseClassResource->save();
                                }
                            }
                        } else {
                            $saved = false;
                        }
                    } else {
                        $saved = false;
                    }
                }
                if ($saved) {
                    Log::model()->saveAction("courseplan", $id, $logSituation, $newCoursePlan->name);
                    Yii::app()->user->setFlash('success', Yii::t('default', 'Plano de Curso salvo com Sucesso!'));
                    $this->redirect(array('index'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('default', 'Ouve algum erro ao salvar as aulas.'));
                    $this->actionUpdate($newCoursePlan->id, ['courseClasses' => $courseClasses]);
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Não foi possível salvar o plano de aula.'));
                $this->actionCreate(['coursePlan' => $newCoursePlan, 'courseClasses' => $courseClasses]);
            }
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Preencha o cabeçalho.'));
            $newCoursePlan = new CoursePlan;
            $newCoursePlan->attributes = $coursePlan;
            $newCoursePlan->school_inep_fk = Yii::app()->user->school;
            $newCoursePlan->validate();
            $this->actionCreate(['coursePlan' => $newCoursePlan, 'courseClasses' => $courseClasses]);
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $coursePlan = new CoursePlan;
        $courseClasses = [];

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

        $this->render('form', array(
            'coursePlan' => $coursePlan,
            'courseClasses' => $courseClasses,
            'stages' => $stages
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id, $data = null)
    {
        if (isset($_POST['CoursePlan'])) {
            $this->actionSave($_POST, $id);
        }

        $coursePlan = $this->loadModel($id);
        if ($data == null) {
            $courseClasses = [];
            foreach ($coursePlan->courseClasses as $courseClass) {
                $order = $courseClass->order;
                $courseClasses[$order] = [];
                $courseClasses[$order]['objective'] = $courseClass->objective;
                $courseClasses[$order]['type'] = [];
                $courseClasses[$order]['content'] = [];
                $courseClasses[$order]['resource'] = [];
                $i = 0;
                foreach ($courseClass->courseClassHasClassResources as $classHasResource) {
                    $resource = ClassResources::model()->findByPk($classHasResource->class_resource_fk);

                    if ($resource->type == ClassResources::TYPE) {
                        array_push($courseClasses[$order]['type'], $resource->id);
                    }
                    if ($resource->type == ClassResources::CONTENT) {
                        array_push($courseClasses[$order]['content'], $resource->id);
                    }
                    if ($resource->type == ClassResources::RESOURCE) {
                        if ($i === 0) {
                            $courseClasses[$order]['resource'][$i] = [];
                        }
                        $courseClasses[$order]['resource'][$i]['value'] = $resource->id;
                        $courseClasses[$order]['resource'][$i]['amount'] = $classHasResource->amount;
                        $i++;
                    }
                }

            }
        } else {
            $courseClasses = $data['courseClasses'];
        }
        $contents = ClassResources::model()->findAllByAttributes(['type' => ClassResources::CONTENT]);
        $resources = ClassResources::model()->findAllByAttributes(['type' => ClassResources::RESOURCE]);
        $types = ClassResources::model()->findAllByAttributes(['type' => ClassResources::TYPE]);

        $contents = CHtml::listData($contents, "id", "name");
        $resources = CHtml::listData($resources, "id", "name");
        $types = CHtml::listData($types, "id", "name");

        $contentsOptions = '';
        $resourcesOptions = '';
        $typesOptions = '';


        foreach ($contents as $id => $name) {
            $contentsOptions .= CHtml::tag('option', array('value' => $id), CHtml::encode($name), true);
        }
        foreach ($resources as $id => $name) {
            $resourcesOptions .= CHtml::tag('option', array('value' => $id), CHtml::encode($name), true);
        }
        foreach ($types as $id => $name) {
            $typesOptions .= CHtml::tag('option', array('value' => $id), CHtml::encode($name), true);
        }

        $this->render('form', array(
            'coursePlan' => $coursePlan,
            'courseClasses' => $courseClasses,
            'contents' => $contentsOptions,
            'resources' => $resourcesOptions,
            'types' => $typesOptions,
        ));
    }

    public function actionGetDisciplines()
    {
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
                echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['id']), CHtml::encode($disciplinesLabels[$discipline['id']]), true));
            }
        } else {
            echo CHtml::tag('option', array('value' => ""), CHtml::encode('Selecione a disciplina...'), true);
            $disciplines = Yii::app()->db->createCommand("select curricular_matrix.discipline_fk from curricular_matrix where stage_fk = :stage_fk and school_year = :year")->bindParam(":stage_fk", $_POST["stage"])->bindParam(":year", Yii::app()->user->year)->queryAll();
            foreach ($disciplines as $i => $discipline) {
                if (isset($discipline['discipline_fk'])) {
                    echo htmlspecialchars(CHtml::tag('option', array('value' => $discipline['discipline_fk']), CHtml::encode($disciplinesLabels[$discipline['discipline_fk']]), true));
                }
            }
        }
    }

    /**
     * Delete model.
     */
    public
    function actionDelete($id)
    {
        $coursePlan = $this->loadModel($id);
        if ($coursePlan->delete()) {
            Log::model()->saveAction("courseplan", $id, "D", $coursePlan->name);
            Yii::app()->user->setFlash('success', Yii::t('default', 'Plano de aula excluído com sucesso!'));
            $this->redirect(array('index'));
        } else {
            throw new CHttpException(404, 'A página requisitada não existe.');
        }
    }

    /**
     * Lists all models.
     */
    public
    function actionIndex()
    {
        if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $dataProvider = new CActiveDataProvider('CoursePlan', array(
                'criteria' => array(
                    'condition' => 'users_fk=' . Yii::app()->user->loginInfos->id,
                ),
            ));
        } else {
            $dataProvider = new CActiveDataProvider('CoursePlan');
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
