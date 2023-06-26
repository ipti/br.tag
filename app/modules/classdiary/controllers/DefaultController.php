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
		$this->render('classDiary');
	}
	public function actionRenderFrequencyElementMobile($classrom_fk, $stage_fk, $discipline_fk, $date)
	{
		$getFrequency = new GetFrequency();
		$frequency = $getFrequency->exec($classrom_fk, $stage_fk, $discipline_fk, $date);

		header('Content-Type: application/json; charset="UTF-8"');
		$vaild = json_encode($frequency["valid"]);
		
		if ($vaild == 'false'){
			echo json_encode($frequency["error"]);
		} else  {
			$this->renderPartial('frequencyElementMobile', ["frequency" => $frequency, "date"=> $date,  "discipline_fk" => $discipline_fk]);
		}
	}
	public function actionRenderFrequencyElementDesktop($classrom_fk, $stage_fk, $discipline_fk, $date)
	{
		$getFrequency = new GetFrequency();
		$frequency = $getFrequency->exec($classrom_fk, $stage_fk, $discipline_fk, $date);
		$this->renderPartial('frequencyElementDesktop', ["frequency" => $frequency]);
	}
	public function actionSaveFresquency()
	{
		$saveFrequency = new SaveFrequency();
		$frequency = $saveFrequency->exec($_POST["schedule"], $_POST["studentId"],$_POST["fault"], $_POST["stage_fk"], $_POST["date"], $_POST["classroom_id"]);
	}
	public function actionStudentClassDiary($student_id, $stage_fk, $classrom_id, $schedule, $date, $discipline_fk, $justification)
	{
		
		
		$getStudent = new GetStudent();
		$student = $getStudent->exec($student_id);

		if(isset($_POST["justification"])) {
			$justification = $_POST["justification"];
			$saveJustification = new SaveJustification();
			$saveJustification->exec($student_id, $stage_fk, $classrom_id, $schedule, $date, $justification);
			$this->redirect(['classDiary', 'classrom_fk' => $classrom_id, 'stage_fk' => $stage_fk, 'discipline_fk' => $discipline_fk]);
		} else {
			$this->render('studentClassDiary', ["student" => $student, "stage_fk" => $stage_fk, "classrom_id" => $classrom_id, "schedule" => $schedule, "date" =>$date, "justification" => $justification]);
		}
		
		
		
	}
}