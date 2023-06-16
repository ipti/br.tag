<?php

class StudentService
{
    public function getFrequency($classrom_fk, $stage_fk, $discipline_fk, $date) {
        
        if ($stage_fk >= 14 && $stage_fk <= 16) 
        {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and 
            unavailable = 0 group by day order by day, schedule", ["classroom_fk" => $classrom_fk, 
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
            "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        } else 
        {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and discipline_fk = :discipline_fk
             and month = :month and unavailable = 0 order by day, schedule", ["classroom_fk" => $classrom_fk, 
             "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), "discipline_fk" => $discipline_fk,
             "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        }
        
        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classrom_fk), $criteria);
        if ($schedule != null) 
        {
            if ($enrollments != null) 
            {
                $students = array();
                foreach ($enrollments as $enrollment) {
                    $array["studentId"] = $enrollment->student_fk;
                    $array["studentName"] = $enrollment->studentFk->name;
                    $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $enrollment->student_fk]);
                    $available = date("Y-m-d") >= Yii::app()->user->year . "-" . str_pad($schedule->month, 2, "0", STR_PAD_LEFT);
                    $array["schedule"] = [
                        "available" => $available,
                        "day" => $schedule->day,
                        "schedule" => $schedule->schedule,
                        "fault" => $classFault != null,
                        "justification" => $classFault->justification
                    ];
                    array_push($students, $array);
                }   
                return $students;
            } else {
                echo json_encode(["valid" => false, "error" => "Matricule alunos nesta turma para trazer o quadro de frequência."]);
            }
        }else {
            echo json_encode(["valid" => false, "error" => "No quadro de horário da turma, não existe dia letivo no mês selecionado para este componente curricular/eixo."]);
        }
    }

    public function saveFrequency($schedule, $studentId, $fault, $stage_fk, $date){
       /*  if ($_POST["fundamentalMaior"] == "1") {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and schedule = :schedule", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"], "schedule" => $_POST["schedule"]]);
            $this->saveFrequency($schedule);
        } else {
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and month = :month", ["classroom_fk" => $_POST["classroomId"], "day" => $_POST["day"], "month" => $_POST["month"]]);
            foreach ($schedules as $schedule) {
                $this->saveFrequency($schedule);
            }
        } */
    }
}