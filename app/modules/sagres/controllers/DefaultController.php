<?php
use SagresEdu\SagresConsultModel;

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$cod_unidade_gestora = Yii::app()->request->getParam('cod_unidade_gestora');
   		$this->render('index', array('cod_unidade_gestora' => $cod_unidade_gestora));
	}

	public function actionCreateOrUpdate($id=3)
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