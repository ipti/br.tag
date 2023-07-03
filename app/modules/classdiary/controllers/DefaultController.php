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
	public function actionClassDiary($classroom_fk, $stage_fk, $discipline_fk, $discipline_name)
	{
		$this->render('classDiary', ["discipline_name"=> $discipline_name]);
	} 
	public function actionGetClassesContents($classroom_fk, $stage_fk, $date, $discipline_fk){
		$getClassContents = new GetClassContents();
		$classContent = $getClassContents->exec($classroom_fk, $stage_fk, $date, $discipline_fk); 
		header('Content-Type: application/json; charset="UTF-8"');
	    echo json_encode($classContent, JSON_OBJECT_AS_ARRAY);
	}
	public function actionSaveClassContents($stage_fk, $date, $discipline_fk, $classroom_fk, $classContent)
	{
		$saveClassContent = new SaveClassContents();
		$saveClassContent->exec($stage_fk, $date, $discipline_fk, $classroom_fk, $classContent);
	}
	
	public function actionRenderFrequencyElementMobile($classroom_fk, $stage_fk, $discipline_fk, $date)
	{
		$getFrequency = new GetFrequency();
		$frequency = $getFrequency->exec($classroom_fk, $stage_fk, $discipline_fk, $date);
		
			$this->renderPartial('_frequencyElementMobile', ["frequency" => $frequency, "date"=> $date,  "discipline_fk" => $discipline_fk, "stage_fk" => $stage_fk, "classroom_fk" => $classroom_fk]);
	}
	public function actionRenderFrequencyElementDesktop($classroom_fk, $stage_fk, $discipline_fk, $date)
	{
		$getFrequency = new GetFrequency();
		$frequency = $getFrequency->exec($classroom_fk, $stage_fk, $discipline_fk, $date);
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

			$getDiscipline = new GetDiscipline();
			$discipline = $getDiscipline->exec($discipline_fk)->name;
			$this->redirect(['classDiary', 'classroom_fk' => $classrom_id, 'stage_fk' => $stage_fk, 'discipline_fk' => $discipline_fk, 'discipline_name' => $discipline]);
		} else {
			$this->render('studentClassDiary', ["student" => $student, "stage_fk" => $stage_fk, "classrom_id" => $classrom_id, "schedule" => $schedule, "date" =>$date, "justification" => $justification]);
		}
		
		
		
	}
}