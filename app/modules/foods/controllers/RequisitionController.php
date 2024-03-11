<?php

use GuzzleHttp\Client;

class RequisitionController extends Controller
{
    private $client;

    public function actionIndex()
    {
        $itemId = Yii::app()->getRequest()->getQuery('item_code');
        $result = $this->getData($itemId);

        $this->render(
            'index',
            array(
                "data" => $result
            )
        );
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


    public function actionSendData()
    {
        
        $school_test = Yii::app()->user->school;
        $tempo_entrega = 5;
        try {
            $postData = array(
                "school" => $school_test,
                "data" => [
                    "2023-01-01",
                    "2023-01-02",
                    "2023-01-03",
                    "2023-01-04",
                    "2023-01-05",
                    "2023-01-06",
                    "2023-01-07",
                    "2023-01-08",
                    "2023-01-09",
                    "2023-01-10",
                    "2023-02-25",
                    "2023-11-08",
                    "2023-05-17",
                    "2023-06-04",
                    "2023-04-23",
                    "2023-08-23",
                    "2023-03-13",
                    "2023-03-18",
                    "2023-05-27",
                    "2023-08-01",
                    "2023-08-10",
                    "2023-06-13",
                    "2023-02-16",
                    "2023-10-06",
                    "2023-06-10",
                    "2023-08-17",
                    "2023-02-24",
                    "2023-06-23",
                    "2023-05-16",
                    "2023-07-29",
                    "2023-08-29",
                    "2023-04-08",
                    "2023-11-22",
                    "2023-03-20",
                    "2023-06-20",
                    "2023-03-27",
                    "2023-08-07",
                    "2023-05-03",
                    "2023-06-18",
                    "2023-12-16",
                    "2023-11-25",
                    "2023-01-06",
                    "2023-02-22",
                    "2023-01-07",
                    "2023-12-05",
                    "2023-02-24",
                    "2023-08-16",
                    "2023-04-30",
                    "2023-01-10",
                    "2023-12-28",
                    "2023-01-16",
                    "2023-11-21",
                    "2023-07-05",
                    "2023-09-12",
                    "2023-04-17",
                    "2023-04-07",
                    "2023-02-18",
                    "2023-07-12",
                    "2023-02-23",
                    "2023-06-02",
                    "2023-09-26",
                    "2023-03-09",
                    "2023-05-15",
                    "2023-07-24",
                    "2023-02-01",
                    "2023-04-17",
                    "2023-12-29",
                    "2023-05-26",
                    "2023-09-27",
                    "2023-09-23",
                    "2023-07-06",
                    "2023-01-17",
                    "2023-01-22",
                    "2023-01-20",
                    "2023-04-13",
                    "2023-10-14",
                    "2023-06-21",
                    "2023-05-09",
                    "2023-11-08",
                    "2023-03-10",
                    "2023-04-11",
                    "2023-05-03",
                    "2023-05-03",
                    "2023-01-05",
                    "2023-01-23",
                    "2023-11-11",
                    "2023-06-02",
                    "2023-06-27",
                    "2023-04-26",
                    "2023-07-23",
                    "2023-09-11",
                    "2023-06-28",
                    "2023-04-18",
                    "2023-11-23",
                    "2023-04-19",
                    "2023-12-04",
                    "2023-08-18",
                    "2023-03-09",
                    "2023-03-16",
                    "2023-04-02",
                    "2023-08-01",
                    "2023-04-03",
                    "2023-05-09",
                    "2023-02-05",
                    "2023-07-29",
                    "2023-11-22",
                    "2023-01-02",
                    "2023-07-28",
                    "2023-08-12",
                    "2023-04-14"
                ],
                "item_nome" => [
                    "Macarrão",
                    "Tomate",
                    "Pão",
                    "Banana",
                    "Cebola",
                    "Abacaxi",
                    "Cenoura",
                    "Leite",
                    "Ovos",
                    "Arroz",
                    "Macarrão",
                    "Banana",
                    "Tomate",
                    "Pão",
                    "Abacaxi",
                    "Arroz",
                    "Abacaxi",
                    "Banana",
                    "Cenoura",
                    "Banana",
                    "Pão",
                    "Abacaxi",
                    "Arroz",
                    "Cenoura",
                    "Tomate",
                    "Leite",
                    "Cenoura",
                    "Arroz",
                    "Arroz",
                    "Pão",
                    "Abacaxi",
                    "Cebola",
                    "Pão",
                    "Cenoura",
                    "Banana",
                    "Abacaxi",
                    "Banana",
                    "Abacaxi",
                    "Abacaxi",
                    "Cenoura",
                    "Pão",
                    "Macarrão",
                    "Macarrão",
                    "Ovos",
                    "Pão",
                    "Cenoura",
                    "Ovos",
                    "Arroz",
                    "Arroz",
                    "Cebola",
                    "Leite",
                    "Leite",
                    "Ovos",
                    "Abacaxi",
                    "Cenoura",
                    "Pão",
                    "Macarrão",
                    "Cebola",
                    "Cebola",
                    "Leite",
                    "Ovos",
                    "Leite",
                    "Leite",
                    "Arroz",
                    "Abacaxi",
                    "Cenoura",
                    "Cenoura",
                    "Cenoura",
                    "Cebola",
                    "Abacaxi",
                    "Ovos",
                    "Arroz",
                    "Abacaxi",
                    "Abacaxi",
                    "Cenoura",
                    "Ovos",
                    "Cenoura",
                    "Tomate",
                    "Leite",
                    "Arroz",
                    "Abacaxi",
                    "Tomate",
                    "Arroz",
                    "Leite",
                    "Abacaxi",
                    "Macarrão",
                    "Leite",
                    "Ovos",
                    "Arroz",
                    "Arroz",
                    "Cenoura",
                    "Leite",
                    "Cenoura",
                    "Macarrão",
                    "Tomate",
                    "Tomate",
                    "Ovos",
                    "Cenoura",
                    "Arroz",
                    "Ovos",
                    "Arroz",
                    "Pão",
                    "Cenoura",
                    "Cebola",
                    "Pão",
                    "Leite",
                    "Macarrão",
                    "Abacaxi",
                    "Abacaxi",
                    "Banana"
                ],
                "item" => [
                    "001",
                    "002",
                    "003",
                    "004",
                    "005",
                    "006",
                    "007",
                    "008",
                    "009",
                    "010",
                    "001",
                    "004",
                    "002",
                    "003",
                    "006",
                    "010",
                    "006",
                    "004",
                    "007",
                    "004",
                    "003",
                    "006",
                    "010",
                    "007",
                    "002",
                    "008",
                    "007",
                    "010",
                    "010",
                    "003",
                    "006",
                    "005",
                    "003",
                    "007",
                    "004",
                    "006",
                    "004",
                    "006",
                    "006",
                    "007",
                    "003",
                    "001",
                    "001",
                    "009",
                    "003",
                    "007",
                    "009",
                    "010",
                    "010",
                    "005",
                    "008",
                    "008",
                    "009",
                    "006",
                    "007",
                    "003",
                    "001",
                    "005",
                    "005",
                    "008",
                    "009",
                    "008",
                    "008",
                    "010",
                    "006",
                    "007",
                    "007",
                    "007",
                    "005",
                    "006",
                    "009",
                    "010",
                    "006",
                    "006",
                    "007",
                    "009",
                    "007",
                    "002",
                    "008",
                    "010",
                    "006",
                    "002",
                    "010",
                    "008",
                    "006",
                    "001",
                    "008",
                    "009",
                    "010",
                    "010",
                    "007",
                    "008",
                    "007",
                    "001",
                    "002",
                    "002",
                    "009",
                    "007",
                    "010",
                    "009",
                    "010",
                    "003",
                    "007",
                    "005",
                    "003",
                    "008",
                    "001",
                    "006",
                    "006",
                    "004"
                ],
                "quantidade_em_estoque" => [
                    100,
                    80,
                    50,
                    60,
                    40,
                    60,
                    40,
                    80,
                    70,
                    30,
                    70,
                    75,
                    66,
                    48,
                    57,
                    83,
                    34,
                    45,
                    99,
                    58,
                    52,
                    40,
                    70,
                    89,
                    80,
                    53,
                    43,
                    78,
                    44,
                    95,
                    95,
                    59,
                    52,
                    79,
                    90,
                    24,
                    93,
                    97,
                    44,
                    67,
                    39,
                    44,
                    99,
                    78,
                    82,
                    62,
                    77,
                    33,
                    26,
                    51,
                    91,
                    81,
                    46,
                    96,
                    26,
                    61,
                    49,
                    42,
                    30,
                    87,
                    60,
                    36,
                    42,
                    41,
                    26,
                    31,
                    50,
                    26,
                    100,
                    45,
                    65,
                    87,
                    94,
                    60,
                    31,
                    92,
                    35,
                    94,
                    22,
                    30,
                    72,
                    61,
                    92,
                    43,
                    57,
                    49,
                    73,
                    82,
                    67,
                    62,
                    96,
                    51,
                    75,
                    69,
                    73,
                    78,
                    64,
                    46,
                    93,
                    63,
                    99,
                    60,
                    76,
                    83,
                    42,
                    68,
                    65,
                    83,
                    27,
                    48
                ],
                "quantidade_comprada" => [
                    50,
                    40,
                    150,
                    30,
                    30,
                    60,
                    40,
                    80,
                    70,
                    30,
                    13,
                    26,
                    28,
                    14,
                    30,
                    33,
                    22,
                    24,
                    37,
                    41,
                    40,
                    34,
                    43,
                    32,
                    15,
                    34,
                    37,
                    18,
                    25,
                    40,
                    26,
                    11,
                    47,
                    40,
                    11,
                    28,
                    33,
                    44,
                    25,
                    10,
                    22,
                    20,
                    35,
                    50,
                    34,
                    13,
                    35,
                    20,
                    13,
                    17,
                    36,
                    37,
                    18,
                    11,
                    19,
                    20,
                    48,
                    12,
                    19,
                    15,
                    13,
                    35,
                    11,
                    27,
                    44,
                    34,
                    36,
                    18,
                    46,
                    31,
                    10,
                    30,
                    18,
                    11,
                    50,
                    10,
                    29,
                    17,
                    37,
                    31,
                    43,
                    30,
                    41,
                    32,
                    48,
                    26,
                    14,
                    34,
                    21,
                    30,
                    26,
                    29,
                    36,
                    29,
                    35,
                    30,
                    30,
                    26,
                    40,
                    26,
                    16,
                    41,
                    30,
                    13,
                    36,
                    10,
                    21,
                    27,
                    41,
                    14
                ],
                "data_validade" => [
                    "2023-01-10",
                    "2023-01-15",
                    "2023-02-1",
                    "2023-01-12",
                    "2023-01-18",
                    "2023-01-15",
                    "2023-01-14",
                    "2023-01-20",
                    "2023-01-18",
                    "2023-02-05",
                    "2023-03-16",
                    "2023-08-24",
                    "2023-08-01",
                    "2023-11-20",
                    "2023-06-25",
                    "2023-06-05",
                    "2023-02-10",
                    "2023-11-28",
                    "2023-07-06",
                    "2023-11-18",
                    "2023-12-17",
                    "2023-09-28",
                    "2023-08-28",
                    "2023-01-04",
                    "2023-08-11",
                    "2023-07-19",
                    "2023-09-10",
                    "2023-07-04",
                    "2023-09-30",
                    "2023-08-25",
                    "2023-02-15",
                    "2023-02-19",
                    "2023-07-25",
                    "2023-08-05",
                    "2023-01-09",
                    "2023-09-14",
                    "2023-02-27",
                    "2023-03-28",
                    "2023-02-07",
                    "2023-04-11",
                    "2023-08-09",
                    "2023-07-11",
                    "2023-03-01",
                    "2023-08-30",
                    "2023-06-14",
                    "2023-02-10",
                    "2023-06-04",
                    "2023-03-31",
                    "2023-07-08",
                    "2023-01-10",
                    "2023-12-20",
                    "2023-10-31",
                    "2023-03-15",
                    "2023-09-11",
                    "2023-05-10",
                    "2023-06-22",
                    "2023-03-07",
                    "2023-12-31",
                    "2023-06-28",
                    "2023-09-11",
                    "2023-08-15",
                    "2023-01-23",
                    "2023-12-24",
                    "2023-08-18",
                    "2023-10-02",
                    "2023-04-21",
                    "2023-02-08",
                    "2023-08-03",
                    "2023-07-18",
                    "2023-07-07",
                    "2023-09-16",
                    "2023-01-27",
                    "2023-03-01",
                    "2023-06-22",
                    "2023-04-04",
                    "2023-07-20",
                    "2023-12-31",
                    "2023-04-13",
                    "2023-05-23",
                    "2023-11-13",
                    "2023-09-12",
                    "2023-11-01",
                    "2023-01-17",
                    "2023-05-11",
                    "2023-12-05",
                    "2023-04-07",
                    "2023-06-17",
                    "2023-02-09",
                    "2023-04-09",
                    "2023-09-18",
                    "2023-07-24",
                    "2023-10-30",
                    "2023-10-12",
                    "2023-12-16",
                    "2023-10-17",
                    "2023-10-17",
                    "2023-05-06",
                    "2023-04-05",
                    "2023-02-25",
                    "2023-08-03",
                    "2023-10-30",
                    "2023-12-11",
                    "2023-05-03",
                    "2023-06-21",
                    "2023-08-31",
                    "2023-02-26",
                    "2023-05-17",
                    "2023-06-25",
                    "2023-07-27",
                    "2023-09-14"
                ],
                "tempo_entrega_dias" => $tempo_entrega
            );

            $jsonData = json_encode($postData);

            $result = $this->getClient()->request("POST", "/api/input_data", [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => $jsonData
            ]);

            echo $result->getBody()->getContents();

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




$school_test = Yii::app()->user->school;

$criteria = new CDbCriteria;
$criteria->select = 't.food_fk';
$criteria->join = 'JOIN food f ON f.id = t.food_fk';
$finventory = FoodInventory::model()->findAll($criteria);

$finventoryReceived = FoodInventoryReceived::model()->findAllByAttributes([],['select' => 'previous_amount, date, amount, food_inventory_fk, food_fk, expiration_date']);

echo "Food Inventory Receiver <br>";
foreach ($finventoryReceived as $item) {
    $date = date('Y-m-d', strtotime($item->date));
    $expiration_date = date('Y-m-d', strtotime($item->expiration_date));

    $food = Food::model()->findByPk($item->food_fk);
    $food_description = ($food !== null) ? $food->description : 'Descrição não encontrada';
}

$finventory = FoodInventory::model()->findAllByAttributes([],['select' => 'id, school_fk, food_fk, amount, expiration_date, previous_amount']);

foreach ($finventory as $item) {
    if ($item->school_fk == $school_test) {
        // echo "----------------------------> id: {$item->id} -> school: {$item->school_fk} <br>";

        $correspondingReceivedItems = array_filter($finventoryReceived, function($receivedItem) use ($item) {
            return $receivedItem->food_fk == $item->food_fk && $receivedItem->food_inventory_fk == $item->id;
        });

        foreach ($correspondingReceivedItems as $receivedItem) {
            // Buscar a descrição do alimento associado a food_fk
            $food = Food::model()->findByPk($receivedItem->food_fk);
            $food_description = ($food !== null) ? $food->description : 'Descrição não encontrada';
            $date = date('Y-m-a', strtotime($receivedItem->date));
            $expiration_date = date('Y-m-d', strtotime($receivedItem->expiration_date));

            echo "school: {$item->school_fk} <br>";
            echo "id: {$item->id}<br>";
            echo "date: {$date}<br>";
            // echo "Descrição do alimento: {$food_description} <br>";
            // echo "food_fk: {$receivedItem->food_fk}<br>";
            // echo "amount: {$receivedItem->amount}<br>";
            // echo "previous_amount: {$receivedItem->previous_amount}<br>";
            // echo "expiration_date: {$expiration_date} <br>";
        }
    }
}