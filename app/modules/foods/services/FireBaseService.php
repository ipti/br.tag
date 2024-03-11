<?php

class FireBaseService {
    public $baseUrl = "https://br-nham-agriculto.firebaseio.com";
    public $token = "AIzaSyAf7EefR1VXllpmE60kiQwl6xictSDO-Tc ";



public function createNotice()
{
        // Dados a serem enviados para o Firebase (por exemplo, para adicionar um novo item)
        $data = [
            'name' => 'teste nome',
            'date' => '10/03/2024',
            // adicione mais campos conforme necessário
        ];

        // Converte os dados para formato JSON
        $jsonData = json_encode($data);

        // Inicia a sessão cURL
        $ch = curl_init();

        // Configura as opções da solicitação cURL
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl."/Edital"); // Substitua 'seu-caminho' pelo caminho específico no seu banco de dados
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Use 'PUT' para atualizações ou 'GET' para consultas
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $token));

        // Executa a solicitação cURL e obtém a resposta
        $response = curl_exec($ch);

        // Verifica erros
        if (curl_errno($ch)) {
            echo 'Erro ao se conectar ao Firebase: ' . curl_error($ch);
        } else {
            // Manipule a resposta conforme necessário (pode ser um JSON)
            echo 'Resposta do Firebase: ' . $response;
        }

        var_dump($response);
        // Fecha a sessão cURL
        curl_close($ch);
    }

}
