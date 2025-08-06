<?php

class FoodrequestController extends Controller
{
    public function accessRules()
    {
        return [
            [
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => [
                    'index',
                    'view',
                    'getFoodRequest',
                    'getFarmerRegister',
                    'getFoodAlias',
                    'getFarmerFoods',
                    'getFoodNotice',
                    'getFoodNoticeItems',
                    'updateReceivedFoods',
                    'updateRequestStatus',
                    'updateAcceptedFoods',
                ],
                'users' => ['*'],
            ],
            [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create', 'update'],
                'users' => ['@'],
            ],
            [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['delete'],
                'users' => ['admin'],
            ],
        ];
    }

    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function actionGetFoodAlias()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, description, measurementUnit, category';
        $criteria->condition = 'alias_id = t.id';

        $foodsDescription = Food::model()->findAll($criteria);

        $values = [];
        foreach ($foodsDescription as $food) {
            $values[$food->id] = (object) [
                'description' => $food->description,
                'measurementUnit' => $food->measurementUnit,
                'category' => $food->category,
            ];
        }

        echo json_encode($values);
    }

    public function actionGetFoodNotice()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, name';

        $foodNotices = FoodNotice::model()->findAll($criteria);

        $values = [];
        foreach ($foodNotices as $notice) {
            $values[$notice->id] = (object) [
                'name' => $notice->name,
            ];
        }

        echo json_encode($values);
    }

    public function actionGetFoodNoticeItems()
    {
        $notice = Yii::app()->request->getPost('notice');

        $criteria = new CDbCriteria();
        $criteria->condition = 't.foodNotice_fk = :notice';
        $criteria->params = [':notice' => $notice];
        $criteria->with = ['food'];

        $foodNoticeItems = FoodNoticeItem::model()->findAll($criteria);

        $values = [];
        foreach ($foodNoticeItems as $item) {
            $values[] = [
                'id' => $item->id,
                'foodId' => $item->food_id,
                'foodName' => $item->name,
                'yearAmount' => $item->year_amount,
                'measurementUnit' => $item->measurement,
            ];
        }

        echo json_encode($values);
    }

    public function actionGetFarmerRegister()
    {
        $farmerRegisters = FarmerRegister::model()->findAll();

        $values = [];
        foreach ($farmerRegisters as $farmer) {
            $values[$farmer->id] = (object) [
                'name' => $farmer->name,
            ];
        }

        echo json_encode($values);
    }

    private function saveRequestSchools($requestSchools, $foodRequest)
    {
        foreach ($requestSchools as $school) {
            $requestSchool = new FoodRequestVsSchoolIdentification();

            $requestSchool->school_fk = $school['id'];
            $requestSchool->food_request_fk = $foodRequest->id;

            if (!$requestSchool->save()) {
                return false;
            }
        }

        return true;
    }

    private function saveRequestFarmers($requestFarmers, $foodRequest)
    {
        foreach ($requestFarmers as $farmer) {
            $requestFarmer = new FoodRequestVsFarmerRegister();

            $requestFarmer->farmer_fk = $farmer;
            $requestFarmer->food_request_fk = $foodRequest->id;

            if (!$requestFarmer->save()) {
                return false;
            }
        }

        return true;
    }

    private function saveRequestItems($requestItems, $foodRequest)
    {
        foreach ($requestItems as $item) {
            $requestItem = new FoodRequestItem();

            $requestItem->food_fk = $item['food_id'];
            $requestItem->amount = $item['amount'];
            $requestItem->measurementUnit = $item['measurementUnit'];
            $requestItem->food_request_fk = $foodRequest->id;

            if (!$requestItem->save()) {
                return false;
            }
        }

        return true;
    }

    public function actionUpdateAcceptedFoods()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];

        if (!$authHeader) {
            echo json_encode(['error' => 'token nao informado']);
            Yii::app()->end();
        }

        if ('Bearer $2b$05$JjoO4oqoZeJF4ISTXvu/4ugg4KpdnjEAVgrdEXO9JBluQvu0vnck6' != $authHeader) {
            echo json_encode(['error' => 'token nao autorizado']);
            Yii::app()->end();
        }

        $referenceId = Yii::app()->request->getPost('requestId');
        $foodId = Yii::app()->request->getPost('foodId');
        $amount = Yii::app()->request->getPost('amount');
        $measurementUnit = Yii::app()->request->getPost('measurementUnit');
        $farmerCpf = Yii::app()->request->getPost('farmerCpf');

        $criteria = new CDbCriteria();
        $criteria->condition = 't.reference_id = :reference_id';
        $criteria->params = [':reference_id' => $referenceId];

        $request = FoodRequest::model()->find($criteria);
        if (!$request) {
            echo json_encode(['error' => 'Nao foi possivel encontrar uma solicitacao com esse id']);
            Yii::app()->end();
        }
        $requestId = $request->id;
        $itemSaveStatus = $this->saveItemAccepted($requestId, $foodId, $farmerCpf, $amount, $measurementUnit);

        if (!$itemSaveStatus) {
            echo json_encode(['error' => 'Não foi possível registrar que o alimento foi aceito']);
            Yii::app()->end();
        }

        $request->status = 'Aceita por agricultor';
        if ($request->save()) {
            echo json_encode(['success' => 'Status da solicitacao modificado com sucesso']);
            Yii::app()->end();
        }
        $errors = $request->getErrors();
        echo json_encode([
            'error' => 'Não foi possível modificar o status da solicitação',
            'details' => $errors,
        ]);
        Yii::app()->end();
    }

    public function actionUpdateReceivedFoods()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];

        if (!$authHeader) {
            echo json_encode(['error' => 'token nao informado']);
            Yii::app()->end();
        }

        if ('Bearer $2b$05$JjoO4oqoZeJF4ISTXvu/4ugg4KpdnjEAVgrdEXO9JBluQvu0vnck6' != $authHeader) {
            echo json_encode(['error' => 'token nao autorizado']);
            Yii::app()->end();
        }
        $referenceId = Yii::app()->request->getPost('requestId');
        $foodId = Yii::app()->request->getPost('foodId');
        $amount = Yii::app()->request->getPost('amount');
        $measurementUnit = Yii::app()->request->getPost('measurementUnit');
        $farmerCpf = Yii::app()->request->getPost('farmerCpf');

        $criteria = new CDbCriteria();
        $criteria->condition = 't.reference_id = :reference_id';
        $criteria->params = [':reference_id' => $referenceId];

        $request = FoodRequest::model()->find($criteria);
        if (!$request) {
            echo json_encode(['error' => 'Nao foi possivel encontrar uma solicitacao com esse id']);
            Yii::app()->end();
        }
        $requestId = $request->id;
        $itemSaveStatus = $this->saveItemReceived($requestId, $foodId, $farmerCpf, $amount, $measurementUnit);
        $requestIsFinished = $this->checkItemsReceived($requestId);

        if ($requestIsFinished) {
            Yii::app()->user->setState('loginInfos', null);
            $request->status = 'Finalizada';

            if ($request->save()) {
                echo json_encode(['success' => 'Status da solicitacao modificado com sucesso']);
                Yii::app()->end();
            }
            $errors = $request->getErrors();
            echo json_encode([
                'error' => 'Não foi possível modificar o status da solicitação',
                'details' => $errors,
            ]);
            Yii::app()->end();
        }

        if ('Erro' != $itemSaveStatus) {
            echo json_encode(['success' => 'Alimento registrado com sucesso']);
            Yii::app()->end();
        }
        echo json_encode(['error' => 'Não foi possível registrar o alimento']);
        Yii::app()->end();
    }

    private function saveItemReceived($requestId, $foodId, $farmerCpf, $amount, $measurementUnit)
    {
        $farmer = FarmerRegister::model()->findByAttributes(['cpf' => $farmerCpf]);
        $message = 'Erro';

        $itemsReceived = new FoodRequestItemReceived();
        $itemsReceived->food_fk = $foodId;
        $itemsReceived->farmer_fk = $farmer->id;
        $itemsReceived->food_request_fk = $requestId;
        $itemsReceived->amount = $amount;
        $itemsReceived->measurementUnit = $measurementUnit;
        if ($itemsReceived->save()) {
            $message = 'Item salvo';
        }

        return $message;
    }

    private function saveItemAccepted($requestId, $foodId, $farmerCpf, $amount, $measurementUnit)
    {
        $farmer = FarmerRegister::model()->findByAttributes(['cpf' => $farmerCpf]);

        $itemAccepted = new FoodRequestItemAccepted();
        $itemAccepted->food_fk = $foodId;
        $itemAccepted->farmer_fk = $farmer->id;
        $itemAccepted->food_request_fk = $requestId;
        $itemAccepted->amount = $amount;
        $itemAccepted->measurementUnit = $measurementUnit;
        if ($itemAccepted->save()) {
            return true;
        }

        return false;
    }

    private function checkItemsReceived($requestId)
    {
        $itemsReceived = FoodRequestItemReceived::model()->findAllByAttributes(
            ['food_request_fk' => $requestId],
            ['order' => 'food_fk ASC']
        );

        $groupedItems = [];

        foreach ($itemsReceived as $item) {
            $foodFk = $item->food_fk;

            if (!isset($groupedItems[$foodFk])) {
                $groupedItems[$foodFk] = [
                    'foodFk' => $foodFk,
                    'foodRequestFk' => $item->food_request_fk,
                    'measurementUnit' => $item->measurementUnit,
                    'totalAmount' => 0,
                ];
            }

            $groupedItems[$foodFk]['totalAmount'] += $item->amount;
        }

        $groupedItems = array_values($groupedItems);

        $items = FoodRequestItem::model()->findAllByAttributes(
            ['food_request_fk' => $requestId],
            ['order' => 'food_fk ASC']
        );

        if ((!empty($groupedItems) && !empty($items)) && count($items) == count($groupedItems)) {
            for ($i = 0; $i < count($items); ++$i) {
                if ($items[$i]->amount != $groupedItems[$i]['totalAmount'] && $items[$i]->measurementUnit != $groupedItems[$i]['measurementUnit']) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function actionGetFoodRequest()
    {
        $foodRequestData = FoodRequest::model()->with([
            'noticeFk' => [
                'select' => 'name',
            ],
        ])->findAll();

        $requestsList = [];
        foreach ($foodRequestData as $request) {
            $requestData = [
                'requestInfo' => [
                    'id' => $request->id,
                    'status' => $request->status,
                    'date' => date('d/m/Y', strtotime($request->date)),
                    'notice' => $request->noticeFk->name,
                ],
                'items' => [],
                'farmers' => [],
                'schools' => [],
            ];

            $this->getFoodRequestItems($requestData, $request->id);
            $this->getFoodRequestItemsReceived($requestData, $request->id);
            $this->getFoodRequestAcceptedItems($requestData, $request->id);
            $this->getFoodRequestFarmers($requestData, $request->id);
            $this->getFoodRequestSchools($requestData, $request->id);

            $requestsList[] = $requestData;
        }

        usort($requestsList, function ($date1, $date2) {
            $dateA = DateTime::createFromFormat('d/m/Y', $date1['requestInfo']['date']);
            $dateB = DateTime::createFromFormat('d/m/Y', $date2['requestInfo']['date']);

            return $dateB <=> $dateA;
        });

        echo json_encode($requestsList);
    }

    private function getFoodRequestItems(&$requestDataArray, $requestId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.food_request_fk = :requestId';
        $criteria->params = [':requestId' => $requestId];

        $requestItems = FoodRequestItem::model()->with([
            'foodFk' => [
                'select' => 'description',
            ],
        ])->findAll($criteria);

        foreach ($requestItems as $item) {
            $requestDataArray['items'][] = [
                'id' => $item->id,
                'foodId' => $item->food_fk,
                'foodName' => $item->foodFk->description,
                'amount' => $item->amount,
                'measurementUnit' => $item->measurementUnit,
            ];
        }
    }

    private function getFoodRequestItemsReceived(&$requestDataArray, $requestId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.food_request_fk = :requestId';
        $criteria->params = [':requestId' => $requestId];

        $requestItemsReceived = FoodRequestItemReceived::model()->with([
            'foodFk' => [
                'select' => 'description',
            ],
            'farmerFk' => [
                'select' => 'name',
            ],
        ])->findAll($criteria);

        foreach ($requestItemsReceived as $item) {
            $requestDataArray['itemsReceived'][] = [
                'id' => $item->id,
                'foodId' => $item->food_fk,
                'foodName' => $item->foodFk->description,
                'farmerId' => $item->farmer_fk,
                'farmerName' => $item->farmerFk->name,
                'amount' => $item->amount,
                'measurementUnit' => $item->measurementUnit,
                'date' => date('d/m/Y', strtotime($item->date)),
            ];
        }
    }

    private function getFoodRequestAcceptedItems(&$requestDataArray, $requestId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.food_request_fk = :requestId';
        $criteria->params = [':requestId' => $requestId];

        $requestAcceptedItems = FoodRequestItemAccepted::model()->with([
            'foodFk' => [
                'select' => 'description',
            ],
            'farmerFk' => [
                'select' => 'name',
            ],
        ])->findAll($criteria);

        foreach ($requestAcceptedItems as $item) {
            $requestDataArray['acceptedItems'][] = [
                'id' => $item->id,
                'foodId' => $item->food_fk,
                'foodName' => $item->foodFk->description,
                'farmerId' => $item->farmer_fk,
                'farmerName' => $item->farmerFk->name,
                'amount' => $item->amount,
                'measurementUnit' => $item->measurementUnit,
                'date' => date('d/m/Y', strtotime($item->date)),
            ];
        }
    }

    private function getFoodRequestFarmers(&$requestDataArray, $requestId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.food_request_fk = :requestId';
        $criteria->params = [':requestId' => $requestId];
        $requestFarmers = FoodRequestVsFarmerRegister::model()->with([
            'farmerFk' => [
                'select' => 'name',
            ],
        ])->findAll($criteria);

        foreach ($requestFarmers as $farmer) {
            $requestDataArray['farmers'][] = [
                'id' => $farmer->farmer_fk,
                'name' => $farmer->farmerFk->name,
            ];
        }
    }

    public function getFoodRequestSchools(&$requestDataArray, $requestId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.food_request_fk = :requestId';
        $criteria->params = [':requestId' => $requestId];
        $requestSchools = FoodRequestVsSchoolIdentification::model()->with([
            'schoolFk' => [
                'select' => 'name',
            ],
        ])->findAll($criteria);

        foreach ($requestSchools as $school) {
            $requestDataArray['schools'][] = [
                'id' => $school->school_fk,
                'name' => $school->schoolFk->name,
            ];
        }
    }

    public function actionCreate()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $noticeId = Yii::app()->request->getPost('noticeId');
            $requestSchools = Yii::app()->request->getPost('requestSchools');
            $requestFarmers = Yii::app()->request->getPost('requestFarmers');
            $requestItems = Yii::app()->request->getPost('requestItems');
            $requestTitle = Yii::app()->request->getPost('requestTitle');

            $foodRequest = new FoodRequest();
            $foodRequest->notice_fk = $noticeId;
            $foodRequest->status = 'Enviada para agricultores';

            if ($foodRequest->save() && $this->saveRequestSchools($requestSchools, $foodRequest) &&
            $this->saveRequestFarmers($requestFarmers, $foodRequest) && $this->saveRequestItems($requestItems, $foodRequest)) {
                $requestSchoolNames = array_map(function ($school) {
                    return $school['name'];
                }, $requestSchools);

                $criteria = new CDbCriteria();
                $criteria->addInCondition('id', $requestFarmers);
                $farmers = FarmerRegister::model()->findAll($criteria);

                $farmersCpfs = [];

                foreach ($farmers as $farmer) {
                    $farmersCpfs[] = $farmer->cpf;
                }

                $createFoodRequest = new CreateFoodRequest();
                $requestReferenceId = $createFoodRequest->exec($requestTitle, $requestSchoolNames, $farmersCpfs, $requestItems);

                $foodRequest->reference_id = $requestReferenceId;
                $foodRequest->save();

                Yii::app()->user->setFlash('success', Yii::t('default', 'Solicitação foi gerada com sucesso!'));
            }
        }
        $model = new FoodRequest();
        $requestFarmerModel = new FoodRequestVsFarmerRegister();
        $requestSchoolModel = new FoodRequestVsSchoolIdentification();
        $requestItemModel = new FoodRequestItem();

        $this->render('create', [
            'model' => $model,
            'requestFarmerModel' => $requestFarmerModel,
            'requestSchoolModel' => $requestSchoolModel,
            'requestItemModel' => $requestItemModel,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['FoodRequest'])) {
            $model->attributes = $_POST['FoodRequest'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax'])) {
            $url = Yii::app()->createUrl(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
            $this->redirect($url);
        }
    }

    public function actionIndex()
    {
        $model = new FoodRequest();

        if (isset($_POST['FoodRequest'])) {
            $model->attributes = $_POST['FoodRequest'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $dataProvider = new CActiveDataProvider('FoodRequest');
        $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAdmin()
    {
        $model = new FoodRequest('search');
        $model->unsetAttributes();
        if (isset($_GET['FoodRequest'])) {
            $model->attributes = $_GET['FoodRequest'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    public function loadModel($id)
    {
        $model = FoodRequest::model()->findByPk($id);
        if (null === $model) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && 'food-request-form' === $_POST['ajax']) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
