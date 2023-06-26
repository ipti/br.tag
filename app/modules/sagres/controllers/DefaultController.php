<?php
use SagresEdu\SagresConsultModel;

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$sagresConsultModel = new SagresConsultModel;

		$numInconsistencys = $sagresConsultModel->getInconsistenciesCount();
   		$this->render('index', ['numInconsistencys' => $numInconsistencys]);
	}

	public function actionCreateOrUpdate()
	{		   		
		$sagresConsultModel = new SagresConsultModel;
		$managementUnitCode = $sagresConsultModel->getManagementId();

		if (isset($managementUnitCode)) {
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

			$model->cpf_responsavel = str_replace(array(".", "-"), "", $model->cpf_responsavel);
			$model->cpf_gestor = str_replace(array(".", "-"), "", $model->cpf_gestor);

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

	public function actionInconsistencySagres()
	{
		$this->render('inconsistencys');
	}

	public function actionExport($year, $month, $finalClass)
	{
		try {
			$sagres = new SagresConsultModel;
			$sagresEduXML = $sagres->generatesSagresEduXML($sagres->getSagresEdu($year, $month, $finalClass));
			echo $sagresEduXML;
		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
		}
	}
}