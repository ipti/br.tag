<?php
use SagresEdu\SagresConsultModel;

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionCreate()
	{
		$model = new ProvisionAccounts;

		if (isset($_POST['ProvisionAccounts'])) {
			$model->attributes = $_POST['ProvisionAccounts'];

			if ($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('default', 'Unidade cadastrada com sucesso!'));
				$this->redirect(array('index', 'codigounidgestora' => $model->codigounidgestora));
			}
		}

		$this->render(
			'create',
			array(
				'model' => $model,
			)
		);
	}

	public function actionUpdate()
	{

	}

	public function actionExport($inep_id, $yearSagresConsult)
	{
		$sagres = new SagresConsultModel;
        $sagresEduXML = $sagres->generatesSagresEduXML($sagres->getEducacaoData($inep_id, $yearSagresConsult));
        $sagres->actionExportSagresXML($sagresEduXML);
	}
}