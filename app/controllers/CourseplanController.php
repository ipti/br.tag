<?php

class CourseplanController extends Controller {

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
            'postOnly + delete', // we only allow deletion via POST request
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
    public function actionSave($data, $id = null) {
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
                $logSituation = "C";
            }
            $newCoursePlan->attributes = $coursePlan;
            if ($newCoursePlan->validate()) {
                $saved = $saved && $newCoursePlan->save();
                foreach($newCoursePlan->courseClasses as $class){
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
    public function actionCreate($data = null) {
        if (isset($_POST['CoursePlan'])) {
            $this->actionSave($_POST);
        }
        if ($data == null) {
            $coursePlan = new CoursePlan;
            $courseClasses = [];
        } else {
            $coursePlan = $data['coursePlan'];
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

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id, $data = null) {
        if (isset($_POST['CoursePlan'])) {
            $this->actionSave($_POST, $id);
        }

        $coursePlan = $this->loadModel($id);
        if ($data == null) {
            $courseClasses = [];
            foreach($coursePlan->courseClasses as $courseClass){
                $order = $courseClass->order;
                $courseClasses[$order] = [];
                $courseClasses[$order]['objective'] = $courseClass->objective;
                $courseClasses[$order]['type'] = [];
                $courseClasses[$order]['content'] = [];
                $courseClasses[$order]['resource'] = [];
                $i=0;
                foreach ($courseClass->courseClassHasClassResources as $classHasResource){
                    $resource = ClassResources::model()->findByPk($classHasResource->class_resource_fk);
                    
                    if($resource->type == ClassResources::TYPE){
                        array_push($courseClasses[$order]['type'], $resource->id);
                    }
                    if($resource->type == ClassResources::CONTENT){
                        array_push($courseClasses[$order]['content'], $resource->id);
                    }
                    if($resource->type == ClassResources::RESOURCE){
                        if($i === 0) {
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

    /**
     * Delete model.
     */
    public function actionDelete($id) {
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
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('CoursePlan');
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
    public function loadModel($id) {
        $model = CoursePlan::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CoursePlan $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'course-plan-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
