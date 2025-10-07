<?php

Yii::import('application.modules.dashboard.usecases.*');
class DefaultController extends Controller
{
    public function actionIndex()
    {
        $getToken = new GetToken();
        $token = $getToken->exec('edacd1e1-74e0-4637-a2b8-780b9c244de0', 'd89e1bae-dbc9-45fe-afb6-ab0d067603cc');
        $this->render('index', [
            'token' => $token,
            'embedUrl' => 'https://app.powerbi.com/reportEmbed',
            'reportId' => 'd89e1bae-dbc9-45fe-afb6-ab0d067603cc'
        ]);
    }
}
