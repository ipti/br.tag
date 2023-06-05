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

        public function getClassroomsInstructor($discipline) {

            $sql = "SELECT c.id, ii.name as instructor_name, ed.name as discipline_name, esvm.name as stage_name, c.name  
            from instructor_teaching_data itd 
            join teaching_matrixes tm ON itd.id = tm.teaching_data_fk 
            join instructor_identification ii on itd.instructor_fk = ii.id 
            join curricular_matrix cm on tm.curricular_matrix_fk = cm.id 
            JOIN edcenso_discipline ed on ed.id = cm.discipline_fk 
            join classroom c on c.id = itd.classroom_id_fk  
            Join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk  
            WHERE ii.users_fk = :users_fk and ed.id = :discipline_id and c.school_year = :user_year
            ORDER BY ii.name";

            $command = Yii::app()->db->createCommand($sql);
            $command->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
            ->bindValue(':discipline_id', $discipline, PDO::PARAM_INT)
            ->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT);

            $classrooms = $command->queryAll();
           
            return $classrooms;
        }
        
        
    }