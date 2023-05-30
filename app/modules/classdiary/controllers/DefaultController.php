<?php
Yii::import('application.modules.classdiary.usecases.*');

class DefaultController extends Controller
{
	public function actionIndex()
	{
		try {
			
			$getClassrooms = new GetClassrooms();
			$classrooms = $getClassrooms->exec(Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id));

			$this->render('index', [
				'classrooms' => $classrooms
			]);

		} catch (\Throwable $th) {
			Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro ao carregar as turmas.'));
			$this->redirect(array('index'));
		}
		
	}
}