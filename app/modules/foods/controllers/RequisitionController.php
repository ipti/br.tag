<?php


use GuzzleHttp\Client;


$school_test = yii::app()->user->school;


class RequisitionController extends Controller
{
    private $client;

    public function actionIndex()
{
    $itemId = Yii::app()->getRequest()->getQuery('item_code');
    $result = $this->getData($itemId);

    $this->render('index', array(
        "data" => $result
    ));
}

public function actionGetData()
{
    $itemId = Yii::app()->getRequest()->getQuery('item_code');
    $result = $this->getData($itemId);

    echo CJSON::encode($result);
}



public function actionGetConsumptionData()
{
    $itemId = Yii::app()->getRequest()->getQuery('item_code');
    $result = $this->getConsumptionData($itemId);

    echo CJSON::encode($result);
}


    public function actionGetInputData()
    {
        $result = $this->getInputData();

        echo CJSON::encode($result);
    }

        private function getData($itemCode)
    {
        try {
            $result = $this->getClient()->request("GET", "/api/consultar_dados_output?school_code=Nada com nada de Itai10&item_code=$itemCode");

            $resultArr = CJSON::decode($result->getBody()->getContents());

            return $resultArr;

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    private function getInputData()
    {
        try {
            $result = $this->getClient()->request("GET", "/api/consultar_dados_input?code_school=Nada com nada de Itai10");

            $resultArr = CJSON::decode($result->getBody()->getContents());

            return $resultArr;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

private function getConsumptionData($itemCode)
{
    try {
        $result = $this->getClient()->request("GET", "/api/consultar_dados_input_consumo?code_school=Nada com nada de Itai10&item_code=$itemCode");

        $resultArr = CJSON::decode($result->getBody()->getContents());

        return $resultArr;

    } catch (\Throwable $th) {
        throw $th;
    }
}

    private function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client([
                'base_uri' => "https://model-nham-consumo.azurewebsites.net",
                'headers' => [
                    'content-type' => 'application/json',
                ],
                'timeout' => 30.0,
            ]);
        }
        return $this->client;
    }
}



///////////////////////////////////////////////////////////////////////////////


// FoodInventoryReceived::model()->findByAttributes(["date"=>$dataquerida])

// $china = FoodInventoryReceived::model()->findAll();
// CVarDumper::dump($china, 12, True);


// $finventoryReceived = FoodInventoryReceived::model()->findAllByAttributes([],['select' => 'date, amount']);
// CVarDumper::dump($finventoryReceived, 12, True);


$finventory = FoodInventory::model()->findAllByAttributes([],['select' => 'id, amount, expiration_date']);
CVarDumper::dump($finventory, 12, True);


