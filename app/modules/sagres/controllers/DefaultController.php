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

		$model = ProvisionAccounts::model()->findByPk(1);

		if ($model) {
			Yii::app()->user->setFlash('error', Yii::t('default', 'Unidade gestora já está cadastrada!'));
			$this->redirect(array('index', 'cod_unidade_gestora' => $model->cod_unidade_gestora));
		}

		$model = new ProvisionAccounts;

		if (isset($_POST['ProvisionAccounts'])) {
			$model->attributes = $_POST['ProvisionAccounts'];
			if ($model->validate() && $model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('default', 'Unidade cadastrada com sucesso!'));
				$this->redirect(array('index', 'cod_unidade_gestora' => $model->cod_unidade_gestora));
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
			Yii::app()->user->setFlash('error', Yii::t('default', 'Unidade gestora solicitada não existe!'));
			$this->redirect(array('index'));
		}

		if (isset($_POST['ProvisionAccounts'])) {
			$model->attributes = $_POST['ProvisionAccounts'];

			if ($model->validate() && $model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('default', 'Unidade atualizada com sucesso!'));
				$this->redirect(array('index', 'cod_unidade_gestora' => $model->cod_unidade_gestora));
			}
		}

		$this->render(
			'update',
			array(
				'model' => $model,
			)
		);
	}

	public function actionExport($managementUnitId, $year, $data_inicio, $data_final)
	{
		$sagres = new SagresConsultModel;
        $sagresEduXML = $sagres->generatesSagresEduXML($sagres->getEducacaoData($managementUnitId, $year, $data_inicio, $data_final));
        print_r($sagres->actionExportSagresXML($sagresEduXML));
	}
}