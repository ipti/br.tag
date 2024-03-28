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

	public function actionExport($month, $finalClass, $noMovement, $withoutCpf)
	{

		try {
			$year = Yii::app()->user->year;
			$memoryLimit = ini_get('memory_limit');
			set_time_limit(0);

			ini_set('memory_limit', '2048M');

			$noMovement = ($noMovement === "true") ? true : false;
			$withoutCpf = ($withoutCpf === "true") ? true : false;

			$sagres = new SagresConsultModel;
            $sagres->cleanInconsistences();
			$sagresEduData = $sagres->getSagresEdu($year, $month, $finalClass, $noMovement, $withoutCpf);
			$sagresEduXML = $sagres->generatesSagresEduXML($sagresEduData);
			$sagres->actionExportSagresXML($sagresEduXML);

			Yii::app()->user->setFlash('success', Yii::t('default', 'Exportação Concluída com Sucesso. <br><a href="' . Yii::app()->createUrl("sagres/default/download") . '" class="btn btn-mini" target="_blank"><i class="icon-download-alt"></i>Clique aqui para fazer o Download do arquivo de exportação!!!</a>'));

		} catch (Exception $e) {
			Yii::app()->user->setFlash('error', Yii::t('default', $e->getMessage()));
		} finally {
			ini_set('memory_limit', $memoryLimit);
		}
	}

	public function actionDownload(){

		$inst = "File_" . INSTANCE . "/";
		$fileDir = "./app/export/SagresEdu/" . $inst . "Educacao.zip";
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
