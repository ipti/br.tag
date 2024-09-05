<?php
Yii::import('application.modules.foods.usecases.*');

use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class FoodNoticeController extends Controller
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
                'actions' => array(
                    'index',
                    'view',
                    'getTacoFoods',
                    'getNotice',
                    'activateNotice',
                    'toggleNoticeStatus',
                    'getNoticePdfUrl'
                ),
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
        $this->render(
            'view',
            array(
                'model' => $this->loadModel($id),
            )
        );
    }
    public function actionGetTacoFoods()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, description';
        $criteria->condition = 'alias_id = t.id';

        $foods = Food::model()->findAll($criteria);

        $resultArray = array();
        foreach ($foods as $food) {
            $resultArray[$food->id] = $food->description;
        }
        echo json_encode($resultArray);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new FoodNotice;
        $noticeData = json_decode(Yii::app()->request->getPost('notice'), true);

        if ($noticeData != null) {
            $date = date('Y-m-d', strtotime(str_replace('/', '-', $noticeData["date"])));
            $uuid = Uuid::uuid4();
            $model->name = $noticeData["name"];
            $model->date = $date;
            $model->file_name = $_FILES["noticePdf"]["name"];
            $model->reference_id = $uuid->toString();

            if ($model->save()) {
                $this->saveNoticeItems($noticeData["noticeItems"], $model->id);

                $fileUploaded = CUploadedFile::getInstanceByName("noticePdf");

                $this->uploadFile($fileUploaded, $uuid->toString(), $noticeData, $date);
            }
        } else {
            $this->render(
                'create',
                array(
                    'model' => $model,
                )
            );
        }
    }
    private $client;
    private function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client([
                'base_uri' => "https://southamerica-east1-br-nham-agrigultor.cloudfunctions.net",

                'timeout' => 30.0,
            ]);
        }
        return $this->client;
    }

    public function actionGetNoticePdfUrl() {
        $noticeId = Yii::app()->request->getPost('id');

        $criteria = new CDbCriteria();
        $criteria->condition = 't.id = :id';
        $criteria->params = array(':id' => $noticeId);

        $existingNotice = FoodNotice::model()->find($criteria);
        $pdfUrl = $this->fetchPdfUrl($existingNotice->reference_id);

        if(empty($pdfUrl)) {
            echo CJSON::encode(array('error' => 'Erro ao recuperar URL'));
            Yii::app()->end();
        }
        echo CJSON::encode($pdfUrl);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $noticeData = json_decode(Yii::app()->request->getPost('notice'), true);

        if ($noticeData != null) {

            $date = strtotime(str_replace('/', '-', $noticeData["date"]));

            $model->name = $noticeData["name"];
            $model->date = date('Y-m-d', $date);
            if($_FILES["noticePdf"]["name"]) {
                $model->file_name = $_FILES["noticePdf"]["name"];
            }

            $modelNoticeItems = FoodNoticeItem::model()->findAllByAttributes(
                ["foodNotice_fk" => $id]
            );
            foreach ($modelNoticeItems as $modelNoticeItem) {
                $modelNoticeItem->delete();
            }

            if ($model->save()) {
                $this->saveNoticeItems($noticeData["noticeItems"], $id);

                $pdfUrl = $this->fetchPdfUrl($model->reference_id);

                $this->updatePdfFile($noticeData, $model->reference_id, $pdfUrl);
            }
        }

        $this->render(
            'update',
            array(
                'model' => $model,
            )
        );
    }

    private function fetchPdfUrl($referenceId)
    {
        $pdfUrlPath = '/appNhamAgricultor/url/' . $referenceId;
        try {
            $result = $this->getClient()->request("GET", $pdfUrlPath);
            $pdfUrl = CJSON::decode($result->getBody()->getContents());
            return $pdfUrl["url"];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return '';
        }
    }
    private function saveNoticeItems($noticeItems, $id)
    {
        foreach ($noticeItems as $item) {
            $modelNoticeItem = new FoodNoticeItem;
            $modelNoticeItem->name = $item["name"];
            $modelNoticeItem->description = $item["description"];
            $modelNoticeItem->measurement = $item["measurement"];
            $modelNoticeItem->year_amount = $item["yearAmount"];
            $modelNoticeItem->food_id = $item["food_fk"];
            $modelNoticeItem->foodNotice_fk = $id;
            $modelNoticeItem->save();
        }
    }
    private function updatePdfFile($noticeData, $existingId, $pdfUrl)
    {
        $fileUploaded = CUploadedFile::getInstanceByName("noticePdf");
        $file = fopen($fileUploaded->tempName, 'r');
        $fileStream = \GuzzleHttp\Psr7\Utils::streamFor($file);

        try {
            $this->getClient()->put("/appNhamAgricultor/edit/pdf", [
                'headers' => [
                    'Authorization' => 'Bearer ' . '$2b$05$JjoO4oqoZeJF4ISTXvu/4ugg4KpdnjEAVgrdEXO9JBluQvu0vnck6'
                ],
                'multipart' => [
                    ['name' => 'notice_pdf', 'Content-Type' => 'multipart/form-data', 'contents' => $fileStream, 'filename' => $fileUploaded->name],
                    ['name' => 'id', 'contents' => $existingId],
                    ['name' => 'name', 'contents' => $noticeData["name"]],
                    ['name' => 'date', 'contents' => date('Y-m-d', strtotime(str_replace('/', '-', $noticeData["date"])))],
                    ['name' => 'url', 'contents' => $pdfUrl]
                ]
            ]);
            fclose($file);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $request = $e->getRequest();
            CVarDumper::dump($request, 10, true);
        } catch (Exception $e) {
            CVarDumper::dump($e, 10, true);
        }
    }
    private function uploadFile($fileUploaded, $id, $noticeData, $date)
    {
        $file = fopen($fileUploaded->tempName, 'r');
        $fileStream = \GuzzleHttp\Psr7\Utils::streamFor($file);

        try {
            $this->getClient()->post("/appNhamAgricultor/upload", [
                'headers' => [
                    'Authorization' => 'Bearer ' . '$2b$05$JjoO4oqoZeJF4ISTXvu/4ugg4KpdnjEAVgrdEXO9JBluQvu0vnck6'
                ],
                'multipart' => [
                    [
                        'name' => 'notice_pdf',
                        'Content-Type' => 'multipart/form-data',
                        'contents' => $fileStream,
                        'filename' => $fileUploaded->name
                    ],
                    [
                        'name' => 'id',
                        'contents' => $id
                    ],
                    [
                        'name' => 'name',
                        'contents' => $noticeData["name"]
                    ],
                    [
                        'name' => 'date',
                        'contents' => $date
                    ]
                ]
            ]);

            fclose($file);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $request = $e->getRequest();
            CVarDumper::dump($request, 10, true);
        } catch (Exception $e) {
            CVarDumper::dump($e, 10, true);
        }
    }

    public function actionGetNotice($id)
    {
        $model = $this->loadModel($id);

        $modelNoticeItem = FoodNoticeItem::model()->findAllByAttributes(
            ["foodNotice_fk" => $id]
        );

        $date = DateTime::createFromFormat("Y-m-d", $model->date);

        $result = array();
        $result["name"] = $model->name;
        $result["date"] = $date->format("d/m/Y");
        $result["noticeItems"] = array();
        foreach ($modelNoticeItem as $item) {
            array_push(
                $result["noticeItems"],
                [
                    0 => $item->name,
                    1 => $item->year_amount,
                    2 => $item->measurement,
                    3 => $item->description,
                    4 => $item->food_id,
                ]
            );
        }
        echo CJSON::encode($result);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'id=:id';
        $criteria->params = array(':id' => $id);

        $foodNotice = FoodNotice::model()->find($criteria);

        if ($foodNotice !== null) {
            $foodNotice->status = 'Inativo';
            $foodNotice->save();
            Yii::app()->user->setFlash('success', Yii::t('default', 'Edital inativado com sucesso!'));
        }
    }

    public function actionActivateNotice()
    {
        $notices = FoodNotice::model()->findAll();
        $this->render('activateNotice', array(
            'notices' => $notices
        ));
    }

    public function actionToggleNoticeStatus()
    {
        $id = Yii::app()->request->getPost('id');
        $status = Yii::app()->request->getPost('status');
        $notice = FoodNotice::model()->findByPk($id);

        $notice->status = $status == "Ativo" ? "Inativo" : "Ativo";

        if ($notice->save()) {
            $message = $status === "Ativo" ? 'Edital inativado com sucesso!' : 'Edital ativado com sucesso!';
            Yii::app()->user->setFlash('success', Yii::t('default', $message));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro. Tente novamente!'));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('status', "Ativo");

        $dataProvider = new CActiveDataProvider('FoodNotice');
        $dataProvider->setCriteria($criteria);
        $this->render(
            'index',
            array(
                'dataProvider' => $dataProvider,
            )
        );
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new FoodNotice('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['FoodNotice'])) {
            $model->attributes = $_GET['FoodNotice'];
        }

        $this->render(
            'admin',
            array(
                'model' => $model,
            )
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return FoodNotice the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = FoodNotice::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param FoodNotice $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'food-notice-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
