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
	public function actionClassDiary($discipline_name)
	{
		$this->render('classDiary', ["discipline_name"=> $discipline_name]);
	}
    public function actionclassDays($discipline_name)
	{
		$this->render('classDays', ["discipline_name"=> $discipline_name]);
	}
    public function actionGetMonths() {
        $result = [];
        $classroom = Classroom::model()->findByPk($_POST["classroom"]);
        if ($classroom->calendar_fk != null) {
            $result["months"] = [];
            $calendar = $classroom->calendarFk;
            $begin = new Datetime($calendar->start_date);
            $begin->modify("first day of this month");
            $end = new Datetime($calendar->end_date);
            $end->modify("first day of next month");
            $interval = DateInterval::createFromDateString('1 month');
            $period = new DatePeriod($begin, $interval, $end);
            $meses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
            foreach ($period as $date) {
                array_push($result["months"], ["id" => $date->format("Y") . "-" . $date->format("n"), "name" => $meses[$date->format("n") - 1] . "/" . $date->format("Y")]);
            }
            $result["valid"] = true;
        } else {
            $result = ["valid" => false, "error" => "A Turma está sem Calendário Escolar vinculado."];
        }
        echo json_encode($result);
    }
    public function actionGetDates() {
        $year = $_POST["year"];
        $month = $_POST["month"];
        $classroomId = $_POST["classroom"];
        $discipline = $_POST["discipline"];
        $classroom = Classroom::model()->findByPk($classroomId);
        $isMinor = $this->checkIsStageMinorEducation($classroom);
        if ($isMinor == false) {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and year = :year and month = :month and discipline_fk = :discipline_fk and unavailable = 0 order by day, schedule",
                [
                    "classroom_fk" => $classroomId,
                    "year" => $year,
                    "month" => $month,
                    "discipline_fk" => $discipline
                ]
            );
        } else {
            $schedules = Schedule::model()->findAll(
                "classroom_fk = :classroom_fk and year = :year and month = :month and unavailable = 0 group by day order by day, schedule",
                [
                    "classroom_fk" => $classroomId,
                    "year" => $year,
                    "month" => $month
                ]
            );
        }

        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';

        if ($schedules != null) {
            $scheduleDays = $this->getScheduleDays($schedules);
            echo json_encode(["valid" => true, "scheduleDays"=>$scheduleDays]);

        } else {
            echo json_encode(["valid" => false, "error" => "Mês/Ano " . ($isMinor == false ? "e Disciplina" : "") . " sem aula no Quadro de Horário."]);
        }

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
	public function actionRenderAccordion()
	{
			$course_class_id = $_POST["id"];
			$getCourseClasses = new  GetCourseClasses();
			$types = $getCourseClasses->exec($course_class_id);
			$plan_name = $_POST["plan_name"];
			$this->renderPartial('_accordion', ["plan_name"=>$plan_name]);
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
	public function actionStudentClassDiary($student_id, $stage_fk, $classroom_id, $schedule, $date, $discipline_fk, $justification)
	{


		$getStudent = new GetStudent();
		$student = $getStudent->exec($student_id);

		$getStudentFault = new GetStudentFault();
		$studentFault = $getStudentFault->exec($stage_fk, $classroom_id, $discipline_fk, $date, $student_id) != null;

		$getStudentDiary = new GetStudentDiary();
		$student_observation = $getStudentDiary->exec($stage_fk, $classroom_id, $discipline_fk, $date, $student_id);

		if(isset($_POST["justification"])) {
			$justification = $_POST["justification"];
			$saveJustification = new SaveJustification();
			$saveJustification->exec($student_id, $stage_fk, $classroom_id, $schedule, $date, $justification);

		}
		if(isset($_POST["student_observation"])) {
			$student_observation = $_POST["student_observation"];
			$saveStudentDiary = new SaveStudentDiary();
			 $saveStudentDiary->exec($stage_fk, $classroom_id, $date, $discipline_fk, $student_id, $student_observation);

		}
		if(isset($_POST["student_observation"]) || isset($_POST["justification"])) {
			$getDiscipline = new GetDiscipline();
			$discipline = $getDiscipline->exec($discipline_fk)->name;
			$this->redirect(['classDiary', 'classroom_fk' => $classroom_id, 'stage_fk' => $stage_fk, 'discipline_fk' => $discipline_fk, 'discipline_name' => $discipline]);
		}


		$this->render('studentClassDiary', ["student" => $student, "stage_fk" => $stage_fk, "classroom_id" => $classroom_id, "schedule" => $schedule, "date" =>$date, "justification" => $justification, 'studentFault' => $studentFault, "student_observation"=> $student_observation]);




	}

    private function checkIsStageMinorEducation($classroom) {
        $isMinor = TagUtils::isStageMinorEducation($classroom->edcenso_stage_vs_modality_fk);

        if (!$isMinor && TagUtils::isMultiStage($classroom->edcenso_stage_vs_modality_fk)) {
            $enrollments = StudentEnrollment::model()->findAllByAttributes(["classroom_fk" => $classroom->id]);

            foreach ($enrollments as $enrollment) {
                if (!$enrollment->edcenso_stage_vs_modality_fk ||
                    !TagUtils::isStageMinorEducation($enrollment->edcenso_stage_vs_modality_fk)) {
                    return false;
                }
            }

            $isMinor = true;
        }

        return $isMinor;
    }
    private function getScheduleDays($schedules)
    {
        $result = [];
        $dayName = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"];
        foreach ($schedules as $schedule) {
            $day = ($schedule->day < 10) ? '0' . $schedule->day : $schedule->day;
            $month = ($schedule->month < 10) ? '0' . $schedule->month : $schedule->month;
            $date = $day . "/" . $month . "/" . $schedule->year;
            $index = array_search($date, array_column($result, 'date'));
            if ($index === false) {
                array_push($result, [
                    "day" => $schedule->day,
                    "date" => $date,
                    "week_day" => $dayName[$schedule->week_day],
                ]);
            }
        }
        return $result;
    }
}
