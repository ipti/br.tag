<?php

class StudentService
{
    public function getFrequency($classroom_fk, $stage_fk, $discipline_fk, $date) {
        // Fundamental menor
        $is_minor_schooling = $stage_fk >= 14 && $stage_fk <= 16;
        if ($is_minor_schooling) 
        {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and 
            unavailable = 0 group by day order by day, schedule", ["classroom_fk" => $classroom_fk, 
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
            "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        } else 
        {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and discipline_fk = :discipline_fk
             and month = :month and unavailable = 0 order by day, schedule", ["classroom_fk" => $classroom_fk, 
             "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), "discipline_fk" => $discipline_fk,
             "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        }
        
        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classroom_fk), $criteria);
        
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
                return ["valid" => true, "students" => $students];
            } else {
                return  ["valid" => false, "error" => "Matricule alunos nesta turma para trazer o quadro de frequência."];
            }
        }else {
            return ["valid" => false, "error" => "No quadro de horário da turma, não existe dia letivo para este componente curricular/eixo na data selecionada."];
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
    public function saveJustification($student_id, $stage_fk, $classroom_id, $schedule, $date, $justification){
         // Fundamental menor
         $is_minor_schooling = $stage_fk >= 14 && $stage_fk <= 16;
        if ($is_minor_schooling) {    
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and month = :month", ["classroom_fk" => $classroom_id, 
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
            "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
            foreach ($schedules as $schedule) {
                $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $student_id]);
                $classFault->justification = $justification == "" ? null : $justification;
                $classFault->save();
            }
        }  else {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and schedule = :schedule", ["classroom_fk" =>  $classroom_id, 
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
    public function getStudentFault($stage_fk, $classroom_fk, $discipline_fk, $date, $student_fk){
        // Fundamental menor
        $is_minor_schooling = $stage_fk >= 14 &&  $stage_fk <= 16;
        if ($is_minor_schooling) 
        {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and 
            unavailable = 0 group by day order by day, schedule", ["classroom_fk" => $classroom_fk, 
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
            "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        } else 
        {
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and discipline_fk = :discipline_fk
             and month = :month and unavailable = 0 order by day, schedule", ["classroom_fk" => $classroom_fk, 
             "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), "discipline_fk" => $discipline_fk,
             "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        }

        $classFault = ClassFaults::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", ["schedule_fk" => $schedule->id, "student_fk" => $student_fk]);
        return  $classFault;
    }
    public function getStudentDiary($stage_fk, $classroom_fk, $discipline_fk, $date, $student_fk) {
         // Fundamental menor
         $is_minor_schooling = $stage_fk >= 14 &&  $stage_fk <= 16;
        if ($is_minor_schooling == "1") {
             $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and unavailable = 0 group by day order by day, schedule", ["classroom_fk" => $classroom_fk, 
             "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
             "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        } else { 
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and 
            day = :day and 
            month = :month and 
            discipline_fk = :discipline_fk 
            and unavailable = 0
            group by day
            order by day, schedule", 
            [
                "classroom_fk" => $classroom_fk,
                "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"),
                "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m"), 
                "discipline_fk" => $discipline_fk
            ]);
        }
        if (!empty($schedule)) {
            
                $classDiary_key = array_search($student_fk, array_column($schedule->classDiaries, 'student_fk'));
                
                
                if(is_numeric($classDiary_key)){
                    return $schedule->classDiaries[$classDiary_key]->diary;
                } else {
                    return "";
                }   
        }
    }

      public function saveStudentDiary($stage_fk, $classroom_fk, $date, $discipline_fk, $student_fk, $student_observation) {
         // Fundamental menor
         $is_minor_schooling = $stage_fk >= 14 &&  $stage_fk <= 16;
        if ($is_minor_schooling == "1") {
             $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and unavailable = 0 group by day order by schedule, schedule", ["classroom_fk" => $classroom_fk, 
             "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
             "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        } else { 
            $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and day = :day and month = :month and discipline_fk = :discipline_fk and unavailable = 0 order by schedule, schedule", ["classroom_fk" => $classroom_fk,
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"),
            "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m"), "discipline_fk" => $discipline_fk]);
        }
        $classDiary_key = array_search($student_fk, array_column($schedule->classDiaries, 'student_fk'));

        if ($schedule->classDiaries[$classDiary_key]->diary != "") {
            $classDiary = ClassDiaries::model()->find("schedule_fk = :schedule_fk and student_fk = :student_fk", [":schedule_fk" => $schedule->id, ":student_fk" => $student_fk]);
            if ($classDiary == null) {
                $classDiary = new ClassDiaries();
                $classDiary->schedule_fk = $schedule->id;
                $classDiary->student_fk = $student_fk;
            }
            $classDiary->diary = $student_observation === "" ? null : $student_observation;
            if ($classDiary->save()) {
                // Atualização bem-sucedida
            } else {
                // Erro ao atualizar
                $errors = $classDiary->getErrors();
                var_dump($errors);
                exit();
            }
           
        } else {
            ClassDiaries::model()->deleteAll("schedule_fk = :schedule_fk and student_fk = :student_fk", [":schedule_fk" => $schedule->id, ":student_fk" => $student_fk]);
        }
      }
}