<?php

class sideInfoWidget extends CWidget{

    public function run(){
        $year = Yii::app()->user->year;

        $schools = SchoolIdentification::model()->findAll();
        $classrooms = Classroom::model()->findAll("school_year = :year", ["year"=>$year]);
        $enrollments = Yii::app()->db->createCommand("select student_enrollment.* from student_enrollment JOIN classroom ON classroom.id = student_enrollment.classroom_fk
where classroom.school_year = :year")->queryAll(true,["year"=>$year]);

        $this->render('sideInfo', [
            'schools'=>$schools,
            'classrooms'=>$classrooms,
            'enrollments'=>$enrollments
        ]);
    }

}