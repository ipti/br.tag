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
        return [
            [
                'allow',  // allow all users to perform 'index', 'view', and 'create' actions
                'actions' => ['index', 'view', 'create', 'getCities', 'getSchools'],
                'users' => ['*'],
            ],
            [
                'allow', // allow authenticated user to perform 'update' actions
                'actions' => ['update', 'StudentList', 'confirmEnrollment', 'rejectEnrollment'],
                'users' => ['@'],
            ],
            [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'],
                'users' => ['admin'],
            ],
            /*  array(
                'deny',  // deny all users
                'users' => array('*'),
            ), */
        ];
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

        if (parent::beforeAction(action: $action)) {
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->layout = 'webroot.app.modules.enrollmentonline.layouts.enrollmentonline';
        $model = new EnrollmentOnlineStudentIdentification();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['EnrollmentOnlineStudentIdentification'])) {
            $model->attributes = $_POST['EnrollmentOnlineStudentIdentification'];
            $model->cpf = preg_replace('/\D/', '', $model->cpf);
            $model->responsable_cpf = preg_replace('/\D/', '', $model->responsable_cpf);
            $model->responsable_telephone = preg_replace('/\D/', '', $model->responsable_telephone);
            $model->edcenso_nation_fk = $_POST['EnrollmentOnlineStudentIdentification']['edcenso_nation_fk'];
            $repository = new EnrollmentonlinestudentidentificationRepository($model);
            $user = $repository->savePreEnrollment();
            Yii::app()->user->setFlash('success', Yii::t('default', 'Pre-matricula realizada om sucesso! Agora voê pode acompnhar o andamento no com seu login ' . $user->username . ''));
            $this->render('login');
        }

        $this->render('create', [
        'model' => $model,
        ]);
    }

    public function actionStudentList()
    {
        $model = new EnrollmentOnlineStudentIdentification();
        $repository = new EnrollmentonlinestudentidentificationRepository($model);
        $studentList = $repository->getStudentList();
        $this->render('studentList', [
            'studentList' => $studentList,
        ]);
    }
    public function actionConfirmEnrollment()
    {

        $id = Yii::app()->request->getPost('enrollmentId');
        $model = $this->loadModel($id);
        $repository = new EnrollmentonlinestudentidentificationRepository($model);
        echo $repository->confirmEnrollment();
    }
    public function actionRejectEnrollment()
    {

        $id = Yii::app()->request->getPost('enrollmentId');
        $model = $this->loadModel($id);
        $repository = new EnrollmentonlinestudentidentificationRepository($model);
        echo $repository->rejectedEnrollment();
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
            $model->cpf = preg_replace('/\D/', '', $model->cpf);
            $model->responsable_cpf = preg_replace('/\D/', '', $model->responsable_cpf);
            $model->responsable_telephone = preg_replace('/\D/', '', $model->responsable_telephone);
            if ($model->save()) {
                if (
                    Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ||
                    Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)
                ) {
                    $this->redirect(['index']);
                } else {
                    $this->redirect(['StudentList']);
                }
            }
        }

        $solicitationsRepository = new EnrollmentonlinestudentidentificationRepository($model);
        $studentSolicitations = $solicitationsRepository->getStatus($model->id);

        $criteria = new CDbCriteria();
        $criteria->alias = 'si';
        $criteria->join = '
            INNER JOIN school_stages ss
            ON ss.school_fk = si.inep_id
        ';
        $criteria->condition = 'ss.edcenso_stage_vs_modality_fk = :stageModality';
        $criteria->params = [':stageModality' => $model->edcenso_stage_vs_modality_fk];

        $schools = SchoolIdentification::model()->findAll($criteria);

        $schools = CHtml::listData($schools, 'inep_id', 'name');




        $this->render('update', [
            'model' => $model,
            'studentSolicitations' => $studentSolicitations,
            'schools' => $schools,
        ]);
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
                'condition' => " eoes.school_inep_id_fk = :schoolInepId and NOT EXISTS ( SELECT 1 FROM enrollment_online_enrollment_solicitation eoes
                                    WHERE eoes.enrollment_online_student_identification_fk = eosi.id
                                    AND eoes.status = 2 ) and NOT EXISTS ( SELECT 1 FROM enrollment_online_enrollment_solicitation eoes
                                    WHERE eoes.enrollment_online_student_identification_fk = eosi.id and eoes.status = 3  and eoes.school_inep_id_fk = :schoolInepId) ",
                'params' => [':schoolInepId' => $schoolInepId],
            ],
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
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

        $this->render('admin', [
            'model' => $model,
        ]);
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
        $data = EdcensoCity::model()->findAll('edcenso_uf_fk=:uf_id', [':uf_id' => $uf]);
        $data = CHtml::listData($data, 'id', 'name');

        echo CHtml::tag('option', ['value' => null], 'Selecione uma cidade', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
        }
    }

    public function actionGetSchools()
    {
        $stage = null;
        $stage = Yii::app()->request->getPost('stage');

        $criteria = new CDbCriteria();
        $criteria->alias = 'si';
        $criteria->join = '
            INNER JOIN school_stages ss
            ON ss.school_fk = si.inep_id
        ';
        $criteria->condition = 'ss.edcenso_stage_vs_modality_fk = :stageModality';
        $criteria->params = [':stageModality' => $stage];

        $schools = SchoolIdentification::model()->findAll($criteria);

        $data = CHtml::listData($schools, 'inep_id', 'name');

        echo CHtml::tag('option', ['value' => ''], 'Selecione uma opção de matrícula', true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', ['value' => $value], CHtml::encode($name), true);
        }
    }
}
