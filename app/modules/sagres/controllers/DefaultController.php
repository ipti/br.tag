<?php
use SagresEdu\SagresConsultModel;

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionCreateOrUpdate($id)
	{
		if ($id) {
			$model = ProvisionAccounts::model()->findByPk($id);
			if (!$model) {
				Yii::app()->user->setFlash('error', Yii::t('default', 'Unidade gestora solicitada não existe!'));
				$this->redirect(array('index'));
			}
		} else {
			$model = new ProvisionAccounts;
		}

		if (isset($_POST['ProvisionAccounts'])) {
			$model->attributes = $_POST['ProvisionAccounts'];

			if ($model->validate() && $model->save()) {
				$msg = $id ? 'atualizada' : 'criada';
				Yii::app()->user->setFlash('success', Yii::t('default', 'Unidade ' . $msg . ' com sucesso!'));
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
		try {
			$sagres = new SagresConsultModel;
			$sagresEduXML = $sagres->generatesSagresEduXML($sagres->getSagresEdu($managementUnitId, $year, $data_inicio, $data_final));
			echo $sagres->actionExportSagresXML($sagresEduXML);
		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
		}
	}
}