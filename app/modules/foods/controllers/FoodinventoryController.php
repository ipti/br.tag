<?php

class FoodinventoryController extends Controller
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
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
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
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => [
                    'index',
                    'getFoodAlias',
                    'saveStock',
                    'getFoodInventory',
                    'saveStockSpent',
                    'saveStockReceived',
                    'deleteStockSpent',
                    'checkFoodInventorySpent',
                    'getStockMovement',
                    'updateFoodInventoryStatus',
                    'GetIsNutritionist',
                ],
                'users' => ['@'],
            ],
            ['allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'view'],
                'users' => ['*'],
            ],
            ['allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create', 'update'],
                'users' => ['@'],
            ],
            ['allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'],
                'users' => ['admin'],
            ],
            ['deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     * @param int $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function actionGetFoodAlias()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, description, measurementUnit';
        $criteria->condition = 'alias_id = t.id';

        $foods_description = Food::model()->findAll($criteria);

        $values = [];
        foreach ($foods_description as $food) {
            $values[$food->id] = (object) [
                'description' => $food->description,
                'measurementUnit' => $food->measurementUnit,
            ];
        }

        echo json_encode($values);
    }

    public function actionSaveStock()
    {
        $foodsOnStock = Yii::app()->request->getPost('foodsOnStock');

        if (!empty($foodsOnStock)) {
            foreach ($foodsOnStock as $foodData) {
                $existingFood = FoodInventory::model()->findByAttributes(['food_fk' => $foodData['id'], 'school_fk' => Yii::app()->user->school]);
                $expirationDate = null;
                if ($foodData['expiration_date'] != '') {
                    $expirationDate = date('Y-m-d', strtotime(str_replace('/', '-', $foodData['expiration_date'])));
                }

                if (!$existingFood) {
                    $FoodInventory = new FoodInventory();

                    $FoodInventory->food_fk = $foodData['id'];
                    $FoodInventory->school_fk = Yii::app()->user->school;
                    $FoodInventory->amount = $foodData['amount'];
                    $FoodInventory->measurementUnit = $foodData['measurementUnit'];
                    $FoodInventory->expiration_date = $expirationDate;

                    if ($FoodInventory->save()) {
                        $FoodInventoryReceived = new FoodInventoryReceived();
                        $FoodInventoryReceived->food_fk = $foodData['id'];
                        $FoodInventoryReceived->food_inventory_fk = $FoodInventory->id;
                        $FoodInventoryReceived->amount = $foodData['amount'];

                        $FoodInventoryReceived->save();
                    } else {
                        Yii::app()->request->sendStatusCode(400);
                        $errors = $FoodInventory->getErrors();
                        echo json_encode(['error' => 'Ocorreu um erro ao salvar: ' . reset($errors)[0]]);
                    }
                } else {
                    $FoodInventoryReceived = new FoodInventoryReceived();
                    $FoodInventoryReceived->food_fk = $foodData['id'];
                    $FoodInventoryReceived->food_inventory_fk = $existingFood->id;
                    $FoodInventoryReceived->amount = $foodData['amount'];

                    $FoodInventoryReceived->save();

                    if ($existingFood->measurementUnit == 'Kg' && $foodData['measurementUnit'] == 'g') {
                        $existingFood->amount += $foodData['amount'] / 1000;
                    } elseif ($existingFood->measurementUnit == 'g' && $foodData['measurementUnit'] == 'Kg') {
                        $existingFood->amount += $foodData['amount'] * 1000;
                    } else {
                        $existingFood->amount += $foodData['amount'];
                    }
                    $existingFood->expiration_date = $expirationDate;
                    $existingFood->status = 'Disponivel';
                    $existingFood->save();
                }
            }
        }
    }

    public function actionGetIsNutritionist()
    {
        echo Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id);
    }

    public function actionSaveStockReceived()
    {
        $foodInventoryId = Yii::app()->request->getPost('foodInventoryId');
        $amount = Yii::app()->request->getPost('amount');

        $FoodInventoryReceived = new FoodInventoryReceived();

        $FoodInventoryReceived->food_inventory_fk = $foodInventoryId;
        $FoodInventoryReceived->amount = $amount;

        $FoodInventoryReceived->save();
    }

    public function actionSaveStockSpent()
    {
        $foodInventoryId = Yii::app()->request->getPost('foodInventoryId');
        $amount = Yii::app()->request->getPost('amount');

        $existingFood = FoodInventory::model()->findByAttributes(['id' => $foodInventoryId]);

        $existingFood->amount -= $amount;
        $existingFood->status = 'Emfalta';
        $existingFood->save();

        $FoodInventorySpent = new FoodInventorySpent();

        $FoodInventorySpent->amount = $amount;
        $FoodInventorySpent->food_inventory_fk = $foodInventoryId;

        if (!$FoodInventorySpent->save()) {
            Yii::app()->request->sendStatusCode(400);
            $errors = $FoodInventorySpent->getErrors();
            echo json_encode(['error' => 'Ocorreu um erro ao salvar: ' . reset($errors)[0]]);
        }
    }

    public function actionDeleteStockSpent()
    {
        $foodInventoryId = Yii::app()->request->getPost('foodInventoryId');

        $criteria = new CDbCriteria();
        $criteria->condition = 'food_inventory_fk = :foodInventoryId';
        $criteria->params = [':foodInventoryId' => $foodInventoryId];

        // Deletar os registros que atendem ao critério
        FoodInventorySpent::model()->deleteAll($criteria);
    }

    public function actionGetStockMovement()
    {
        $foodInventoryFoodId = Yii::app()->request->getPost('foodInventoryFoodId');

        $criteria = new CDbCriteria();
        $criteria->select = 'amount, date';
        $criteria->with = ['foodInventoryFk'];
        $criteria->together = true;
        $criteria->condition = 'foodInventoryFk.food_fk = :foodInventoryFoodId';
        $criteria->params = [':foodInventoryFoodId' => $foodInventoryFoodId];

        $receivedData = FoodInventoryReceived::model()->findAll($criteria);
        $spentData = FoodInventorySpent::model()->findAll($criteria);

        $values = [];

        foreach ($receivedData as $data) {
            array_push($values, ['type' => 'Entrada', 'amount' => $data->amount, 'date' => date('d/m/Y', strtotime($data->date)), 'measurementUnit' => $data->foodInventoryFk->measurementUnit]);
        }
        foreach ($spentData as $data) {
            array_push($values, ['type' => 'Saída', 'amount' => $data->amount, 'date' => date('d/m/Y', strtotime($data->date)), 'measurementUnit' => $data->foodInventoryFk->measurementUnit]);
        }

        echo json_encode($values);
    }

    public function actionGetFoodInventory()
    {
        $schoolFk = Yii::app()->user->school;

        $criteria = new CDbCriteria();
        $criteria->with = ['foodRelation'];
        $criteria->compare('school_fk', $schoolFk);

        $foodInventoryData = FoodInventory::model()->findAll($criteria);

        $values = [];
        foreach ($foodInventoryData as $stock) {
            $values[] = [
                'id' => $stock->id,
                'foodId' => $stock->food_fk,
                'description' => $stock->foodRelation->description,
                'amount' => $stock->amount,
                'measurementUnit' => $stock->measurementUnit,
                'expiration_date' => ($stock->expiration_date != null) ? date('d/m/Y', strtotime($stock->expiration_date)) : 'Não informada',
                'status' => $stock->status,
                'spent' => ($stock->amount > 0) ? false : true,
            ];
        }

        echo json_encode($values);
    }

    public function actionUpdateFoodInventoryStatus()
    {
        $foodInventoryId = Yii::app()->request->getPost('foodInventoryId');
        $status = Yii::app()->request->getPost('status');
        $foodInventory = FoodInventory::model()->findByAttributes(['id' => $foodInventoryId]);

        $foodInventory->status = $status;
        $foodInventory->save();
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new FoodInventory();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['FoodInventory'])) {
            $model->attributes = $_POST['FoodInventory'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['FoodInventory'])) {
            $model->attributes = $_POST['FoodInventory'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param int $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $url = Yii::app()->createUrl(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
            $this->redirect($url);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $model = new FoodInventory();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['FoodInventory'])) {
            $model->attributes = $_POST['FoodInventory'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new FoodInventory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['FoodInventory'])) {
            $model->attributes = $_GET['FoodInventory'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param int $id the ID of the model to be loaded
     * @return FoodInventory the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = FoodInventory::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param FoodInventory $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'food-inventory-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
