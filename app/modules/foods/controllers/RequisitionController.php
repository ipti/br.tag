<?php


use GuzzleHttp\Client;

class RequisitionController extends Controller
{
    private $client;

    public function actionIndex()
    {
        $result = $this->getData();

        $this->render('index', array(
            "data" => $result
        ));
    }

    public function actionGetData()
    {
        $result = $this->getData();

        echo CJSON::encode($result);
    }



    public function actionGetConsumptionData()
{
    $result = $this->getConsumptionData();

    echo CJSON::encode($result);
}



    public function actionGetInputData()
    {
        $result = $this->getInputData();

        echo CJSON::encode($result);
    }

    private function getData()
    {
        try {
            $result = $this->getClient()->request("GET", "/api/consultar_dados_output?school_code=Nada com nada de Itai10&item_code=001");

            $resultArr = CJSON::decode($result->getBody()->getContents());

            return $resultArr;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getInputData()
    {
        try {
            $result = $this->getClient()->request("GET", "/api/consultar_dados_input?code_school=Nada com nada de Itai10&item_code=005");

            $resultArr = CJSON::decode($result->getBody()->getContents());

            return $resultArr;

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    private function getConsumptionData()
{
    try {
        $result = $this->getClient()->request("GET", "/api/consultar_dados_input_consumo?code_school=Nada com nada de Itai10&item_code=005");

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









