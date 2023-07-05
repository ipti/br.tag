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
		$memory_limit = ini_get('memory_limit');

		try {

			$sagres = new SagresConsultModel;
			ini_set('memory_limit', '2048M');
			$sagresEduXML = $sagres->generatesSagresEduXML($sagres->getSagresEdu($year, $month, $finalClass));
			$sagres->actionExportSagresXML($sagresEduXML);
			Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação Concluida com Sucesso.<br><a href="'.Yii::app()->createUrl("sagres/default/download").'" class="btn btn-mini" target="_blank"><i class="icon-download-alt"></i>Clique aqui para fazer o Download do arquivo de exportação!!!</a>'));							
			ini_set('memory_limit', $memory_limit);

		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
            ini_set('memory_limit', $memory_limit);
		}		
	}

	public function actionDownload(){

		
		$fileDir = "./app/export/SagresEdu/Educacao.zip";
        if (file_exists($fileDir)) {
            header('Content-Description: File Transfer');
            header('Content-type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($fileDir) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileDir));
            readfile($fileDir);			
			unlink($fileDir);
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Arquivo de exportação não encontrado!!! Tente exportar novamente.'));
            $this->render('index');
        }
	}
}