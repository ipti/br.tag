<?php
Yii::import('application.modules.classdiary.usecases.*');

class DefaultController extends Controller
{
	public function actionIndex()
	{
		try {
			$getDisciplines = new GetDisciplines();
			$disciplines = $getDisciplines->exec();
			$this->render('index', [
				'disciplines' => $disciplines
			]);

		} catch (\Throwable $th) {
			Yii::app()->user->setFlash('error', Yii::t('default', 'Ocorreu um erro ao carregar as turmas.'));
			var_dump($th);
		}
		
	}
	public function actionGetClassrooms()
	{
		$getClassrooms = new GetClassrooms();
		$isInstructor = Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id);
		$discipline = $_POST["discipline"];
		$classrooms = $getClassrooms->exec($isInstructor, $discipline); 
		 echo json_encode($classrooms, JSON_OBJECT_AS_ARRAY);
	}
	public function actionClassDiary($classrom_fk, $stage_fk, $discipline_fk) 
	{	
		//var_dump($classrom_fk, $stage_fk, $discipline_fk);
		//$this->render('classDiary');
	}
	public function actionRenderFrequencyElementMobile() 
	{
		$this->renderPartial('frequencyElementMobile');
	}
	public function actionRenderFrequencyElementDesktop() 
	{
		$this->renderPartial('frequencyElementDesktop');
	}
}