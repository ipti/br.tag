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
				$this->redirect(array('view', 'codigounidgestora' => $model->codigounidgestora));
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
		$sagres = new SagresConsultModel();
		$sagres->actionExport($inep_id, $yearSagresConsult);
	}
}