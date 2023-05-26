<?php
    class ClassesRepository 
    {
        public function getClassrooms($criteria) {
            $classrooms = Classroom::model()->findAll($criteria);
            return $classrooms;
        }

        public function getClassroomsInstructor($criteria) {
            $classrooms = Classroom::model()->findAll($criteria);
            return $classrooms;
        }
        
        
    }