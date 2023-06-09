<?php

class StudentService
{
    public function getFrequency($classrom_fk, $stage_fk, $discipline_fk, $date) {
        if ($stage_fk >= 14 && $stage_fk <= 16) 
        {
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and month = :month and 
            unavailable = 0 group by day order by day, schedule", ["classroom_fk" => $classrom_fk, 
            "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), 
            "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        } else 
        {
            $schedules = Schedule::model()->findAll("classroom_fk = :classroom_fk and day = :day and discipline_fk = :discipline_fk
             and month = :month and unavailable = 0 order by day, schedule", ["classroom_fk" => $classrom_fk, 
             "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), "discipline_fk" => $discipline_fk,
             "month"=>DateTime::createFromFormat("d/m/Y", $date)->format("m")]);
        }

        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classrom_fk), $criteria);
        if ($schedules != null) 
        {
            if ($enrollments != null) 
            {
                
            }
        }
        var_dump($enrollments);
        //$sql = "";
    }
}