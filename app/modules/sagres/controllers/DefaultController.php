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
				$this->redirect(array('index', 'codigoUnidGestora' => $model->codigoUnidGestora));
			}
		}

		$this->render(
			'create',
			array(
				'model' => $model,
			)
		);
	}

	public function actionUpdate($id)
	{
		$model = ProvisionAccounts::model()->findByPk($id);

		if (!$model) {
			throw new CHttpException(404, 'A unidade gestora solicitada não existe.');
		}

		if (isset($_POST['ProvisionAccounts'])) {
			$model->attributes = $_POST['ProvisionAccounts'];

			if ($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('default', 'Unidade atualizada com sucesso!'));
				$this->redirect(array('index', 'codigoUnidGestora' => $model->codigoUnidGestora));
			}
		}

		$this->render(
			'update',
			array(
				'model' => $model,
			)
		);
	}

	public function actionExport($year, $data_inicio, $data_final)
	{
		$sagres = new SagresConsultModel;
        $sagresEduXML = $sagres->generatesSagresEduXML($sagres->getEducacaoData($year, $data_inicio, $data_final));
        print_r($sagres->actionExportSagresXML($sagresEduXML));
	}
}