<?php

class StudentService
{
    public function getFrequency($classrom_fk, $stage_fk, $discipline_fk, $date) {
        // Fundamental menor
        $is_minor_schooling = $stage_fk >= 14 && $stage_fk <= 16;
        if ($is_minor_schooling) 
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

    public function getSechedulesToSaveFrequency($schedule, $student_id, $fault, $stage_fk, $date, $classroom_id){
        // Fundamental menor
        $is_minor_schooling = $stage_fk >= 14 && $stage_fk <= 16;
        if ($is_minor_schooling) {
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and month = :month", ["classroom_fk" => $classroom_id,
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"),
            "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
            foreach ($schedules as $schedule) {
                $this->saveFrequency($schedule, $student_id, $fault);
            }
        } else {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and schedule = :schedule", ["classroom_fk" =>$classroom_id, 
            "day" =>DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
            "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m"),
             "schedule" => $schedule]);
            $this->saveFrequency($schedule, $student_id, $fault);
        }
    }

    private function saveFrequency($schedule, $student_id, $fault)
    {  
            if ($fault == "1") {
                $classFault = new ClassFaults();
                $classFault->student_fk = $student_id;
                $classFault->schedule_fk = $schedule->id;
                $classFault->save();
            } else {
                ClassFaults::model()->deleteAll("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $student_id]);
            }
    }
    public function saveJustification($student_id, $stage_fk, $classrom_id, $schedule, $date, $justification){
         // Fundamental menor
         $is_minor_schooling = $stage_fk >= 14 && $stage_fk <= 16;
        if ($is_minor_schooling) {    
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and month = :month", ["classroom_fk" => $classrom_id, 
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
            "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
            foreach ($schedules as $schedule) {
                $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $student_id]);
                $classFault->justification = $justification == "" ? null : $justification;
                $classFault->save();
            }
        }  else {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and schedule = :schedule", ["classroom_fk" =>  $classrom_id, 
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"),
            "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m"),
            "schedule" => $schedule]);
            $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $student_id]);
            $classFault->justification = $justification == "" ? null : $justification;
            $classFault->save();
        } 
    }
    public function getStudent($student_id){
        $student = StudentIdentification::model()->findByPk($student_id);
        return $student;
    }
}