<?php
    class ClassesService 
    {
        public function getClassrooms() {
            $criteria = new CDbCriteria;
            $criteria->condition = "school_year = :school_year and school_inep_fk = :school_inep_fk";
                $criteria->order = "name";
                $criteria->params = $criteria->params = array(':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school);
            $classrooms = Classroom::model()->findAll($criteria);
            
            return $classrooms;
        }

        public function getClassroomsInstructor() {
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id ";
            $criteria->condition = "c.school_year = :school_year and c.school_inep_fk = :school_inep_fk and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->params = array(':school_year' => Yii::app()->user->year, ':school_inep_fk' => Yii::app()->user->school, ':users_fk' => Yii::app()->user->loginInfos->id);
            $classrooms = Classroom::model()->findAll($criteria);
           
            return $classrooms;
        }
        
        
    }