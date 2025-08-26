<?php

use Sentry\Tracing\TransactionContext;
use Sentry\SentrySdk;
use Sentry\State\Hub;
use Sentry\Event;

Yii::import('application.modules.enrollmentonline.repository.*');
class EnrollmentOnlineStudentIdentificationController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
            array(
                'allow',  // allow all users to perform 'index', 'view', and 'create' actions
                'actions' => array('index', 'view', 'create', 'getCities', 'getSchools'),
                'users' => array('*'),
            ),
            array(
                'allow', // allow authenticated user to perform 'update' actions
                'actions' => array('update', 'StudentStatus'),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            /*  array(
                 'deny',  // deny all users
                 'users' => array('*'),
             ), */
        );
    }

    public function init()
    {
        if (!Yii::app()->user->isGuest) {

            $authTimeout = Yii::app()->user->getState("authTimeout", SESSION_MAX_LIFETIME);
            Yii::app()->user->authTimeout = $authTimeout;

            Yii::app()->sentry->setUserContext([
                'id' => Yii::app()->user->loginInfos->id,
                'username' => Yii::app()->user->loginInfos->username,
                'role' => Yii::app()->authManager->getRoles(Yii::app()->user->loginInfos->id)
            ]);
        }
    }

    public function beforeAction($action)
    {
        $publicActions = ['getschools', 'getcities'];

        if (Yii::app()->user->isGuest && Yii::app()->request->isAjaxRequest && !in_array($action->id, $publicActions)) {
            // Se a sessão expirou e é uma requisição AJAX
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['redirect' => Yii::app()->createUrl('site/login')]);
            Yii::app()->end();
        }

        $transaction = SentrySdk::getCurrentHub()->startTransaction(new TransactionContext(
            Yii::app()->controller->id . '/' . $action->id,
        ));

        SentrySdk::getCurrentHub()->setSpan($transaction);

        if (parent::beforeAction($action)) {
            // Verifica o timeout com base na última atividade
            if (isset(Yii::app()->user->authTimeout)) {
                $lastActivity = Yii::app()->user->getState('last_activity');
                $timeout = Yii::app()->user->authTimeout;

                if ($lastActivity !== null && (time() - $lastActivity > $timeout)) {
                    Yii::app()->user->logout();
                    return false;
                }
            }

            // Atualiza a última atividade
            Yii::app()->user->setState('last_activity', time());
            return true;
        }
        return false;
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->layout = "webroot.app.modules.enrollmentonline.layouts.enrollmentonline";
        $model = new EnrollmentOnlineStudentIdentification;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['EnrollmentOnlineStudentIdentification'])) {
            $model->attributes = $_POST['EnrollmentOnlineStudentIdentification'];
            $repository = new EnrollmentonlinestudentidentificationRepository($model);
            $user = $repository->savePreEnrollment();
            Yii::app()->user->setFlash('success', Yii::t('default', 'Pre-matricula realizada om sucesso! Agora voê pode acompnhar o andamento no com seu login ' . $user->username . ''));

        }

        $this->render('create', array(
            'model' => $model,
        ));
    }
    public function actionStudentStatus()
    {
        $this->render('studentstatus');
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['EnrollmentOnlineStudentIdentification'])) {
            $model->attributes = $_POST['EnrollmentOnlineStudentIdentification'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
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
    public function actionDelete($id)
    {
        // $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        // if(!isset($_GET['ajax']))
        // 	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $schoolInepId = Yii::app()->user->school;
        $dataProvider = new CActiveDataProvider('EnrollmentOnlineStudentIdentification', [
            'criteria' => [
                'alias' => 'eosi',
                'with' => [
                    'enrollmentOnlineEnrollmentSolicitations' => [
                        'alias' => 'eoes',
                        'together' => true,
                        'joinType' => 'INNER JOIN',
                    ],
                ],
                'condition' => 'eoes.school_inep_id_fk = :schoolInepId',
                'params' => [':schoolInepId' => $schoolInepId],
            ],
        ]);



        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new EnrollmentOnlineStudentIdentification('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['EnrollmentOnlineStudentIdentification'])) {
            $model->attributes = $_GET['EnrollmentOnlineStudentIdentification'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return EnrollmentOnlineStudentIdentification the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = EnrollmentOnlineStudentIdentification::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param EnrollmentOnlineStudentIdentification $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'enrollment-online-student-identification-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetCities()
    {
        $uf = null;
        $uf = Yii::app()->request->getPost('state');
        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', array(':uf_id' => $uf));
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', array('value' => null), 'Selecione uma cidade', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }
    public function actionGetSchools()
    {
        $stage = null;
        $stage = Yii::app()->request->getPost('stage');


        $criteria = new CDbCriteria();
        $criteria->alias = 'si';
        $criteria->join = "
            INNER JOIN school_stages ss
            ON ss.school_fk = si.inep_id
        ";
        $criteria->condition = 'ss.edcenso_stage_vs_modality_fk = :stageModality';
        $criteria->params = array(':stageModality' => $stage);

        $schools = SchoolIdentification::model()->findAll($criteria);

        $data = CHtml::listData($schools, 'inep_id', 'name');

        echo CHtml::tag('option', array('value' => ""), 'Selecione uma opção de matrícula', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }
}
