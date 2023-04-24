<?php
use SagresEdu\SagresConsultModel;

class DefaultController extends Controller
{
	public function actionIndex()
	{
   		$this->render('index');
	}

	public function actionCreateOrUpdate()
	{		   		
		$sagresConsultModel = new SagresConsultModel;
		$managementUnitCode = $sagresConsultModel->getUnitCode();

		if ($managementUnitCode) {
			$model = ProvisionAccounts::model()->findByPk($managementUnitCode);
			if (!$model) {
				Yii::app()->user->setFlash('error', Yii::t('default', 'Unidade gestora solicitada não existe!'));
				$this->redirect(array('index', 'cod_unidade_gestora' => $model->cod_unidade_gestora));
			}
		} else {
			$model = new ProvisionAccounts;
		}

		if (isset($_POST['ProvisionAccounts'])) {
			$model->attributes = $_POST['ProvisionAccounts'];

			if ($model->validate() && $model->save()) {
				$msg = $managementUnitCode ? 'atualizada' : 'criada';
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


	public function actionExport($managementUnitCode, $year, $startDate, $endDate)
	{
		try {
			$sagres = new SagresConsultModel;
			$sagresEduXML = $sagres->generatesSagresEduXML($sagres->getSagresEdu($managementUnitCode, $year, $startDate, $endDate));
			echo $sagres->actionExportSagresXML($sagresEduXML);
		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
		}
	}
}