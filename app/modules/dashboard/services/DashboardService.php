<?php
class DashboardService
{
     public function getAccessToken() {
        $url = 'https://login.microsoftonline.com/common/oauth2/token';
        $username = 'ti.pauloh@ipti.org.br';
        $pss = '81260235Ph.';
        $clientId = 'fdc41ef1-199b-475a-adfd-408fb1a53937';

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );

        $formData = array(
            'grant_type' => 'password',
            'client_id' => $clientId,
            'resource' => 'https://analysis.windows.net/powerbi/api',
            'scope' => 'openid',
            'username' => $username,
            'password' => $pss
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($formData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            die('Erro ao obter o token de acesso: ' . $error);
        }

        $bodyObj = json_decode($response, true);
        // echo 'Access Token: ' . $bodyObj['access_token'];
        return $bodyObj['access_token'];
    }

    public function getReportEmbedToken($accessToken, $groupId, $reportId) {
        $url = 'https://api.powerbi.com/v1.0/myorg/groups/' . $groupId . '/reports/' . $reportId . '/GenerateToken';

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $accessToken
        );

        $formData = array(
            'accessLevel' => 'view'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($formData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            die('Erro ao obter o token de inserção do relatório: ' . $error);
        }

        $bodyObj = json_decode($response, true);
        // echo 'Embed Token: ' . $bodyObj['token'];
        return $bodyObj['token'];
    }

    public function embedReport($groupId, $reportId) {
        $accessToken = $this->getAccessToken();
        $embedToken = $this->getReportEmbedToken($accessToken, $groupId, $reportId);
        return $embedToken;
        // echo "Renderizar página com os parâmetros necessários.";
        // Lógica para renderizar a página com os parâmetros necessários
    }


}
