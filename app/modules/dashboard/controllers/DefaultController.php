<?php
Yii::import('application.modules.dashboard.usecases.*');
class DefaultController extends Controller
{
	public function actionIndex()
	{
		$getToken = new GetToken();
        $token = $getToken->exec('edacd1e1-74e0-4637-a2b8-780b9c244de0','22d50e93-debe-451b-9501-fed42c92df5e');
		$this->render('index',  array(
            'token' => $token,
			'embedUrl' =>'https://app.powerbi.com/reportEmbed',
			'reportId' => '22d50e93-debe-451b-9501-fed42c92df5e'
        ));
	}
}