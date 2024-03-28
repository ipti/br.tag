<?php
Yii::import('application.modules.foods.usecases.*');
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
                'actions' => array('index', 'view', 'getTacoFoods', 'getNotice'),
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
        $request = Yii::app()->request->getPost('notice');

        if ($request != null) {
            $date = strtotime(str_replace('/', '-', $request["date"]));

            $model->name = $request["name"];
            $model->date = date('Y-m-d', $date);
            if ($model->save()) {
                foreach ($request["noticeItems"] as $item) {
                    $modelNoticeItem = new FoodNoticeItem;
                    $modelNoticeItem->name = $item["name"];
                    $modelNoticeItem->description = $item["description"];
                    $modelNoticeItem->measurement = $item["measurement"];
                    $modelNoticeItem->year_amount = $item["yearAmount"];
                    $modelNoticeItem->food_id = $item["food_fk"];

                    if ($modelNoticeItem->save()) {
                        $modelNoticeVsNoticeItem = new FoodNoticeVsFoodNoticeItem;
                        $modelNoticeVsNoticeItem->food_notice_id = $model->id;
                        $modelNoticeVsNoticeItem->food_notice_item_id = $modelNoticeItem->id;
                        $modelNoticeVsNoticeItem->save();
                    }
                }
            }


            $createNotice = new CreateNotice($request["pdf"]);
            $createNotice->exec();
        }

        $this->render('create', array(
            'model' => $model,
        )
        );
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $request = Yii::app()->request->getPost('notice');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if ($request != null) {

            $date = strtotime(str_replace('/', '-', $request["date"]));

            $model->name = $request["name"];
            $model->date = date('Y-m-d', $date);

            $noticeVsNoticeItem = FoodNoticeVsFoodNoticeItem::model()->findAll(
                "food_notice_id = :id",
                ["id" => $id]
            );
            foreach ($noticeVsNoticeItem as $item) {
                $modelNoticeItem = FoodNoticeItem::model()->findByAttributes(
                    ["id" => $item->food_notice_item_id,]
                );
                $item->delete();
                $modelNoticeItem->delete();
            }

            if ($model->save()) {
                foreach ($request["noticeItems"] as $item) {
                    $modelNoticeItem = new FoodNoticeItem;
                    $modelNoticeItem->name = $item["name"];
                    $modelNoticeItem->description = $item["description"];
                    $modelNoticeItem->measurement = $item["measurement"];
                    $modelNoticeItem->year_amount = $item["yearAmount"];
                    $modelNoticeItem->food_id = $item["food_fk"];

                    if ($modelNoticeItem->save()) {
                        $modelNoticeVsNoticeItem = new FoodNoticeVsFoodNoticeItem;
                        $modelNoticeVsNoticeItem->food_notice_id = $model->id;
                        $modelNoticeVsNoticeItem->food_notice_item_id = $modelNoticeItem->id;
                        $modelNoticeVsNoticeItem->save();
                    }
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
        )
        );
    }
    public function actionGetNotice($id)
    {
        $model = $this->loadModel($id);
        $noticeVsNoticeItem = FoodNoticeVsFoodNoticeItem::model()->findAll(
            "food_notice_id = :id",
            ["id" => $id]
        );

        $date = DateTime::createFromFormat("Y-m-d", $model->date);

        $result = array();
        $result["name"] = $model->name;
        $result["date"] = $date->format("d/m/Y");
        $result["noticeItems"] = array();
        foreach ($noticeVsNoticeItem as $item) {
            $modelNoticeItem = FoodNoticeItem::model()->findByAttributes(
                ["id" => $item->food_notice_item_id,]
            );
            array_push(
                $result["noticeItems"],
                [
                    0 => $modelNoticeItem->name,
                    1 => $modelNoticeItem->year_amount,
                    2 => $modelNoticeItem->measurement,
                    3 => $modelNoticeItem->description,
                    4 => $modelNoticeItem->food_id,
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
        $model = $this->loadModel($id);

        $noticeVsNoticeItem = FoodNoticeVsFoodNoticeItem::model()->findAll(
            "food_notice_id = :id",
            ["id" => $id]
        );
        foreach ($noticeVsNoticeItem as $item) {
            $modelNoticeItem = FoodNoticeItem::model()->findByAttributes(
                ["id" => $item->food_notice_item_id,]
            );
            $item->delete();
            $modelNoticeItem->delete();
        }
        $model->delete();

        $this->redirect(array('index'));

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('FoodNotice');
        $this->render('index', array(
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
        if (isset($_GET['FoodNotice'])){
            $model->attributes = $_GET['FoodNotice'];
        }

        $this->render('admin', array(
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
        if ($model === null){
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
