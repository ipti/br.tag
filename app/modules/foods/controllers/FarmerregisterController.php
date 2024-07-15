<?php

class FarmerRegisterController extends Controller
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
                    'activateFarmers',
                    'toggleFarmerStatus',
                    'createFarmerRegister',
                    'updateFarmerRegister',
                    'getFarmerRegister',
                    'getFoodAlias',
                    'getFarmerFoods',
                    'getFoodNotice'
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

    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }


    public function actionCreateFarmerRegister()
    {
        $name = Yii::app()->request->getPost('name');
        $cpf = Yii::app()->request->getPost('cpf');
        $phone = Yii::app()->request->getPost('phone');
        $groupType = Yii::app()->request->getPost('groupType');
        $foodsRelation = Yii::app()->request->getPost('foodsRelation');

        if ($this->verifyFarmerCpf($cpf)) {
            $criteria = new CDbCriteria();
            $criteria->condition = 't.cpf = :cpf';
            $criteria->params = array(':cpf' => $cpf);
            $existingFarmer = FarmerRegister::model()->findAll($criteria);

            $existingFarmer->status = "Ativo";
            $existingFarmer->save();
            Yii::app()->user->setFlash('success', Yii::t('default', 'Agricultor reativado com sucesso!'));
        } else {
            $farmerRegister = new FarmerRegister();

            $farmerRegister->name = $name;
            $farmerRegister->cpf = $cpf;
            $farmerRegister->phone = $phone;
            $farmerRegister->group_type = $groupType;

            if ($farmerRegister->save()) {
                foreach ($foodsRelation as $foodData) {
                    $farmerFoods =  new FarmerFoods;

                    $farmerFoods->food_fk = $foodData['id'];
                    $farmerFoods->farmer_fk = $farmerRegister->id;
                    $farmerFoods->amount = $foodData['amount'];
                    $farmerFoods->measurementUnit = $foodData['measurementUnit'];
                    $farmerFoods->foodNotice_fk = $foodData['noticeId'];

                    $farmerFoods->save();
                }
                Yii::app()->user->setFlash('success', Yii::t('default', 'Cadastro do agricultor criado com sucesso!'));
                $getFarmerRegister = new GetFarmerRegister();
                $existingFarmer = $getFarmerRegister->exec($cpf);

                if (empty($existingFarmer)) {
                    $createFarmerRegister = new CreateFarmerRegister();
                    $farmerReferenceId = $createFarmerRegister->exec($name, $cpf, $phone, $groupType, $foodsRelation);

                    $farmerRegister->reference_id = $farmerReferenceId;
                    $farmerRegister->save();
                } else {
                    $updateFarmerRegister = new UpdateFarmerRegister();
                    $updateFarmerRegister->exec($existingFarmer["id"], $name, $cpf, $phone, $groupType, $foodsRelation);

                    $farmerRegister->reference_id = $existingFarmer["id"];
                    $farmerRegister->save();
                }
            }
        }
    }

    public function actionUpdateFarmerRegister()
    {
        $farmerId = Yii::app()->request->getPost('farmerId');
        $name = Yii::app()->request->getPost('name');
        $cpf = Yii::app()->request->getPost('cpf');
        $phone = Yii::app()->request->getPost('phone');
        $groupType = Yii::app()->request->getPost('groupType');
        $foodsRelation = Yii::app()->request->getPost('foodsRelation');

        if (!empty($name) && !empty($cpf) && !empty($phone) && !empty($groupType)) {
            $existingFarmer = FarmerRegister::model()->findByPk($farmerId);

            $existingFarmer->name = $name;
            $existingFarmer->cpf = $cpf;
            $existingFarmer->phone = $phone;
            $existingFarmer->group_type = $groupType;

            if ($existingFarmer->save()) {
                FarmerFoods::model()->deleteAll('farmer_fk = :id', array(':id' => $farmerId));

                foreach ($foodsRelation as $foodData) {
                    $farmerFoods =  new FarmerFoods;

                    $farmerFoods->food_fk = $foodData['id'];
                    $farmerFoods->farmer_fk = $existingFarmer->id;
                    $farmerFoods->amount = $foodData['amount'];
                    $farmerFoods->measurementUnit = $foodData['measurementUnit'];
                    $farmerFoods->foodNotice_fk = $foodData['noticeId'];

                    $farmerFoods->save();
                }

                $updateFarmerRegister = new UpdateFarmerRegister();
                $updateFarmerRegister->exec($existingFarmer->reference_id, $name, $cpf, $phone, $groupType, $foodsRelation);
            }
        }
    }

    private function verifyFarmerCpf($cpf)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.cpf = :cpf';
        $criteria->params = array(':cpf' => $cpf);
        $farmerRegister = FarmerRegister::model()->findAll($criteria);

        return !empty($farmerRegister);
    }

    private function verifyFarmerStatus($cpf)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.cpf = :cpf';
        $criteria->params = array(':cpf' => $cpf);
        $farmerRegister = FarmerRegister::model()->find($criteria);

        return $farmerRegister->status;
    }

    public function actionGetFarmerRegister()
    {
        $cpf = Yii::app()->request->getPost('farmerCpf');

        if ($this->verifyFarmerCpf($cpf) && $this->verifyFarmerStatus($cpf) == "Ativo") {
            echo json_encode(['error' => 'Existente ativo']);
        } elseif ($this->verifyFarmerCpf($cpf) && $this->verifyFarmerStatus($cpf) == "Inativo") {
            echo json_encode(['error' => 'Existente inativo']);
        } else {
            $getFarmerRegister = new GetFarmerRegister();
            $farmerRegister = $getFarmerRegister->exec($cpf);

            echo json_encode($farmerRegister);
        }
    }

    public function actionGetFarmerFoods()
    {
        $id = Yii::app()->request->getPost('id');

        $criteria = new CDbCriteria();
        $criteria->with = array('foodFk', 'foodNoticeFk');
        $criteria->condition = 't.farmer_fk = ' . $id;
        $farmerFoodsData = FarmerFoods::model()->findAll($criteria);

        $values = [];
        foreach ($farmerFoodsData as $foods) {
            $values[] = array(
                'id' => $foods->food_fk,
                'foodDescription' => preg_replace('/,|\b(cru[ao]?)\b/', '', $foods->foodFk->description),
                'amount' => $foods->amount,
                'measurementUnit' => $foods->measurementUnit,
                'notice' => $foods->foodNoticeFk ? $foods->foodNoticeFk->name : null,
                'noticeId' => $foods->foodNotice_fk,
            );
        }

        echo json_encode($values);
    }

    public function actionGetFoodAlias()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, description, measurementUnit';
        $criteria->condition = 'alias_id = t.id';

        $foodsDescription = Food::model()->findAll($criteria);

        $values = [];
        foreach ($foodsDescription as $food) {
            $values[$food->id] = (object) [
                'description' => $food->description,
                'measurementUnit' => $food->measurementUnit
            ];
        }

        echo json_encode($values);
    }

    public function actionGetFoodNotice()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, name';
        $criteria->condition = 't.status = :status';
        $criteria->params = array(':status' => "Ativo");

        $foodNotices =  FoodNotice::model()->findAll($criteria);

        $values = [];
        foreach ($foodNotices as $notice) {
            $values[$notice->id] = (object) [
                'name' => $notice->name
            ];
        }

        echo json_encode($values);
    }
    public function actionCreate()
    {
        $model = new FarmerRegister;
        $modelFarmerFoods = new FarmerFoods;

        if (isset($_POST['FarmerRegister'])) {
            $model->attributes = $_POST['FarmerRegister'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'model' => $model, 'modelFarmerFoods' => $modelFarmerFoods,
        ));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $modelFarmerFoods = $this->loadFarmerFoodsModel($id);


        if (isset($_POST['FarmerRegister'])) {
            $model->attributes = $_POST['FarmerRegister'];
            if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model, 'modelFarmerFoods' => $modelFarmerFoods,
        ));
    }

    public function actionDelete($id)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'id=:id';
        $criteria->params = array(':id' => $id);

        $farmerRegister = FarmerRegister::model()->find($criteria);

        if ($farmerRegister !== null) {
            $farmerRegister->status = 'Inativo';
            $farmerRegister->save();
            Yii::app()->user->setFlash('success', Yii::t('default', 'Agricultor inativado com sucesso!'));
        }

        $this->redirect(array('index'));
    }

    public function actionIndex()
    {
        $showAll = Yii::app()->request->getParam('showAll');
        $criteria=new CDbCriteria;
        if(!$showAll){
            $criteria->compare('status', "Ativo");
        }

        $dataProvider = new CActiveDataProvider('FarmerRegister');
        $dataProvider->setCriteria($criteria);
        $this->render('index', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionActivateFarmers()
    {
        $farmers = FarmerRegister::model()->findAll();
        $this->render('activateFarmers', array(
            'farmers' => $farmers
        ));
    }
    public function actionToggleFarmerStatus()
    {
        $id = Yii::app()->request->getPost('id');
        $status = Yii::app()->request->getPost('status');
        $farmer = FarmerRegister::model()->findByPk($id);

        $farmer->status = $status == "Ativo" ? "Inativo" : "Ativo";

        if ($farmer->save()) {
            $message = $status === "Ativo" ? 'Agricutor inativado com sucesso!' : 'Agricutor ativado com sucesso!';
            Yii::app()->user->setFlash('success', Yii::t('default', $message));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro. Tente novamente!'));
        }
    }


    public function actionAdmin()
    {
        $model = new FarmerRegister('search');
        $model->unsetAttributes();
        if (isset($_GET['FarmerRegister'])) {
            $model->attributes = $_GET['FarmerRegister'];
        }

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id)
    {
        $model = FarmerRegister::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function loadFarmerFoodsModel($id)
    {
        $modelFarmerFoods = FarmerFoods::model()->find(
            array(
                'condition' => 'farmer_fk = :id',
                'params' => array(':id' => $id),
            )
        );
        if ($modelFarmerFoods === null) {
            $modelFarmerFoods = new FarmerFoods;
        }
        return $modelFarmerFoods;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'farmer-register-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
