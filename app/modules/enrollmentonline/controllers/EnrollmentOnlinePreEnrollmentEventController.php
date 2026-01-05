<?php

class EnrollmentOnlinePreEnrollmentEventController extends Controller
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
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array(
                'deny',  // deny all users
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new EnrollmentOnlinePreEnrollmentEvent;
        $request = Yii::app()->request->getPost('EnrollmentOnlinePreEnrollmentEvent');

        if ($request) {

            $transaction = Yii::app()->db->beginTransaction();

            try {
                $model->attributes = $request;
                $model->name = $request['name'];
                $model->start_date = DateTime::createFromFormat('d/m/Y', $request['start_date'])->format('Y-m-d');

                $model->end_date = DateTime::createFromFormat('d/m/Y', $request['end_date'])->format('Y-m-d');

                if (!$model->save()) {
                    throw new Exception('Erro ao salvar o evento de pré-matrícula.');
                }

                $stages = Yii::app()->request->getPost(
                    'edcenso_stage_vs_modality_fk',
                    []
                );

                foreach ($stages as $stageId) {
                    $modelEventVsEdcensoStageNew = new EnrollmentOnlineEventVsEdcensoStage();
                    $modelEventVsEdcensoStageNew->edcenso_stage_fk = $stageId;
                    $modelEventVsEdcensoStageNew->pre_enrollment_event_fk = $model->id;


                    if (!$modelEventVsEdcensoStageNew->save()) {
                        throw new Exception('Erro ao salvar vínculo com etapa.');
                    }
                }

                $transaction->commit();

                $this->redirect(array('index'));
            } catch (Exception $e) {

                $transaction->rollback();

                Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);

                Yii::app()->user->setFlash(
                    'error',
                    'Ocorreu um erro ao salvar o registro.'
                );
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
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $request = Yii::app()->request->getPost('EnrollmentOnlinePreEnrollmentEvent');

        if ($request) {

            $transaction = Yii::app()->db->beginTransaction();

            try {
                $model->attributes = $request;
                $model->name = $request['name'];
                $model->start_date = DateTime::createFromFormat('d/m/Y', $request['start_date'])->format('Y-m-d');

                $model->end_date = DateTime::createFromFormat('d/m/Y', $request['end_date'])->format('Y-m-d');

                if (!$model->save()) {
                    throw new Exception('Erro ao salvar o evento de pré-matrícula.');
                }

                EnrollmentOnlineEventVsEdcensoStage::model()->deleteAll(
                    'pre_enrollment_event_fk = :eventId',
                    [':eventId' => $model->id]
                );

                $stages = Yii::app()->request->getPost(
                    'edcenso_stage_vs_modality_fk',
                    []
                );

                foreach ($stages as $stageId) {
                    $modelEventVsEdcensoStageNew = new EnrollmentOnlineEventVsEdcensoStage();
                    $modelEventVsEdcensoStageNew->edcenso_stage_fk = $stageId;
                    $modelEventVsEdcensoStageNew->pre_enrollment_event_fk = $model->id;
                    if (!$modelEventVsEdcensoStageNew->save()) {
                        throw new Exception('Erro ao salvar vínculo com etapa.');
                    }
                }
                $transaction->commit();
                $this->redirect(array('index'));
            } catch (Exception $e) {
                $transaction->rollback();

                Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);

                Yii::app()->user->setFlash(
                    'error',
                    'Ocorreu um erro ao salvar o registro.'
                );
            }
        }

        $model->start_date = (new DateTime($model->start_date))->format('d/m/Y');
        $model->end_date = (new DateTime($model->end_date))->format('d/m/Y');

        $this->render('update', array(
            'model' => $model
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $event = $this->loadModel($id);

        $transaction = Yii::app()->db->beginTransaction();

        try {
            // Deleting related records in EnrollmentOnlineEventVsEdcensoStage
            EnrollmentOnlineEventVsEdcensoStage::model()->deleteAll(
                'pre_enrollment_event_fk = :eventId',
                [':eventId' => $event->id]
            );

            $event->delete();

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();

            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);

            Yii::app()->user->setFlash(
                'error',
                'Ocorreu um erro ao deletar o registro.'
            );
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('EnrollmentOnlinePreEnrollmentEvent');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new EnrollmentOnlinePreEnrollmentEvent('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['EnrollmentOnlinePreEnrollmentEvent']))
            $model->attributes = $_GET['EnrollmentOnlinePreEnrollmentEvent'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return EnrollmentOnlinePreEnrollmentEvent the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = EnrollmentOnlinePreEnrollmentEvent::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param EnrollmentOnlinePreEnrollmentEvent $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'enrollment-online-pre-enrollment-event-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
