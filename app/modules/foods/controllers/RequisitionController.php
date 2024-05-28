<?php

use GuzzleHttp\Client;

// $school_name = "Nada com nada de Itai10";


class RequisitionController extends Controller
{
    private $client;

    public function actionIndex()
    {
        $itemId = Yii::app()->getRequest()->getQuery('item_code');
        $school_name = "Nada com nada de Itai10";
        // $school_name = Yii::app()->user->school;

        $result = $this->getData($itemId, $school_name);

        $studentCount = $this->getStudentCount();
        // CVarDumper::dump($studentCount, 12, true);

        $this->render(
            'index',
            array(
                "data" => $result,
                "studentCount" => $studentCount,
            )
        );
    }


    public function actionGetData()
    {
        $itemId = Yii::app()->getRequest()->getQuery('item_code');
        $school_name = "Nada com nada de Itai10";
        // $school_name = Yii::app()->user->school;

        $result = $this->getData($itemId, $school_name);

        echo CJSON::encode($result);
    }

    public function actionGetConsumptionData()
    {
        $itemId = Yii::app()->getRequest()->getQuery('item_code');
        $school_name = "Nada com nada de Itai10";
        // $school_name = Yii::app()->user->school;

        $result = $this->getConsumptionData($itemId, $school_name);

        echo CJSON::encode($result);
    }

    public function actionGetInputData()
    {
        $school_name = "Nada com nada de Itai10";
        // $school_name = Yii::app()->user->school;

        $result = $this->getInputData($school_name);

        echo CJSON::encode($result);
    }

    private function getData($itemCode, $school_name)
    {
        try {
            $result = $this->getClient()->request("GET", "/api/consultar_dados_output?school_code=$school_name&item_code=$itemCode");

            $resultArr = CJSON::decode($result->getBody()->getContents());

            return $resultArr;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getInputData($school_name)
    {
        try {
            $result = $this->getClient()->request("GET", "/api/consultar_dados_input?code_school=$school_name");

            $resultArr = CJSON::decode($result->getBody()->getContents());

            return $resultArr;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function getConsumptionData($itemCode, $school_name)
    {
        try {
            $result = $this->getClient()->request("GET", "/api/consultar_dados_input_consumo?code_school=$school_name&item_code=$itemCode");

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

    $finventoryReceived = FoodInventoryReceived::model()->findAllByAttributes([],['select' => 'previous_amount, date, amount, food_inventory_fk, food_fk, expiration_date']);
    $finventory = FoodInventory::model()->findAllByAttributes([],['select' => 'id, school_fk, food_fk, amount, expiration_date, previous_amount']);

    // $processedData = $this->processInventoryData($finventoryReceived, $finventory, $school_test);
// No método actionSendData()
    $studentCount = $this->getStudentCount();
    $processedData = $this->processInventoryData($finventoryReceived, $finventory, $school_test, $studentCount);

    $postData = array(
        "school" => $school_test,
        "data" => [],
        "item_nome" => [],
        "item" => [],
        ['categoria']=>[],
        "quantidade_em_estoque" => [],
        "quantidade_comprada" => [],
        "data_validade" => [],
        "tempo_entrega_dias" => []
    );



    foreach ($processedData as $item) {
        $postData['data'][] = $item['data previsao'];
        $postData['item_nome'][] = $item['nome do alimento'];
        $postData['item'][] = $item['id do alimento'];
        $postData['categoria'][] = $item['categoria']; // Adicionar a categoria aqui
        $postData['quantidade_em_estoque'][] = $item['qtd em estoque'];
        $postData['quantidade_comprada'][] = $item['quantidade recebida'];
        $postData['data_validade'][] = $item['data se validade'];
        $postData['tempo_entrega_dias'][] = 5;
        $postData['studentCount'] = intval($studentCount); // Adicionar o studentCount
    }



    try {
        $jsonData = json_encode($postData);

        // Enviar os dados pelo método POST
        $result = $this->getClient()->request("POST", "/api/input_data", [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => $jsonData
        ]);

        // Imprimir a resposta do POST
        echo $result->getBody()->getContents();

        // Imprimir os dados enviados pelo POST no console.log
        echo "<script>console.log(" . json_encode($postData) . ");</script>";
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


    private function processInventoryData($finventoryReceived, $finventory, $school_test, $studentCount)
    {
        $result = array();

        foreach ($finventory as $item) {
            if ($item->school_fk == $school_test) {
                $correspondingReceivedItems = array_filter($finventoryReceived, function ($receivedItem) use ($item) {
                    return $receivedItem->food_fk == $item->food_fk && $receivedItem->food_inventory_fk == $item->id;
                });

                foreach ($correspondingReceivedItems as $receivedItem) {
                    $food = Food::model()->findByPk($receivedItem->food_fk);
                    $food_description = ($food !== null) ? $food->description : 'Descrição não encontrada';
                    $date = date('Y-m-d', strtotime($receivedItem->date));
                    $expiration_date = date('Y-m-d', strtotime($receivedItem->expiration_date));

                    // Consulta para obter a categoria do alimento
                    $category = ($food !== null && isset($food->category)) ? $food->category : 'Categoria não encontrada';

                    // Convertendo para inteiros
                    $qtd_em_estoque = (intval($receivedItem->previous_amount) + intval($receivedItem->amount));
                    $quantidade_recebida = intval($receivedItem->amount);

                    $result[] = array(
                        'escola' => $item->school_fk,
                        'data previsao' =>  $date,
                        'nome do alimento' => $food_description,
                        'id do alimento' => $receivedItem->food_fk,
                        'categoria' => $category,
                        'qtd em estoque' => $qtd_em_estoque,
                        'quantidade recebida' => $quantidade_recebida,
                        'data se validade' => $expiration_date,
                        'id' => $item->id,
                        'studentCount' => $studentCount,
                    );
                }
            }
        }

        return $result;
    }

    private function getStudentCount()
    {
        try {
            $count = Yii::app()->db->createCommand()
                ->select('COUNT(*) as quant_students')
                ->from('student_identification si')
                ->join('student_enrollment se', 'se.student_fk = si.id')
                ->where('se.school_inep_id_fk = :school_inep_id_fk', array(':school_inep_id_fk' => '35245239'))
                ->queryScalar();

            return $count;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}


// $school_test = Yii::app()->user->school;
// $criteria = new CDbCriteria;
// $criteria->select = 't.food_fk';
// $criteria->join = 'JOIN food f ON f.id = t.food_fk';
// $finventory = FoodInventory::model()->findAll($criteria);

// $finventoryReceived = FoodInventoryReceived::model()->findAllByAttributes([],['select' => 'previous_amount, date, amount, food_inventory_fk, food_fk, expiration_date']);

// echo "Food Inventory Receiver <br>";
// foreach ($finventoryReceived as $item) {
//     $date = date('Y-m-d', strtotime($item->date));
//     $expiration_date = date('Y-m-d', strtotime($item->expiration_date));

//     $food = Food::model()->findByPk($item->food_fk);
//     $food_description = ($food !== null) ? $food->description : 'Descrição não encontrada';
// }

// $finventory = FoodInventory::model()->findAllByAttributes([],['select' => 'id, school_fk, food_fk, amount, expiration_date, previous_amount']);

// foreach ($finventory as $item) {
//     if ($item->school_fk == $school_test) {
//         // echo "----------------------------> id: {$item->id} -> school: {$item->school_fk} <br>";

//         $correspondingReceivedItems = array_filter($finventoryReceived, function($receivedItem) use ($item) {
//             return $receivedItem->food_fk == $item->food_fk && $receivedItem->food_inventory_fk == $item->id;
//         });

//         foreach ($correspondingReceivedItems as $receivedItem) {
//             // Buscar a descrição do alimento associado a food_fk
//             $food = Food::model()->findByPk($receivedItem->food_fk);
//             $food_description = ($food !== null) ? $food->description : 'Descrição não encontrada';
//             $date = date('Y-m-a', strtotime($receivedItem->date));
//             $expiration_date = date('Y-m-d', strtotime($receivedItem->expiration_date));

//             echo "school: {$item->school_fk} <br>";
//             echo "id: {$item->id}<br>";
//             echo "date: {$date}<br>";
//             echo "Descrição do alimento: {$food_description} <br>";
//             echo "food_fk: {$receivedItem->food_fk}<br>";
//             echo "amount: {$receivedItem->amount}<br>";
//             echo "previous_amount: {$receivedItem->previous_amount}<br>";
//             echo "expiration_date: {$expiration_date} <br>";
//         }
//     }
// }
