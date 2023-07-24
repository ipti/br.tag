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

            $sql = "SELECT c.id, esvm.id as stage_fk, ii.name as instructor_name, ed.id as edcenso_discipline_fk, ed.name as discipline_name, esvm.name as stage_name, c.name  
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

        /**
         * Summary of getClassContents
         * @param mixed $classroom_fk
         * @param mixed $stage_fk
         * @param mixed $date
         * @param mixed $discipline_fk
         * @return array
         */
        public function getClassContents($classroom_fk, $stage_fk, $date, $discipline_fk) {
            // Fundamental menor
            $is_minor_schooling = $stage_fk >= 14 && $stage_fk <= 16;
            if ($is_minor_schooling) 
            {
                $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and month = :month and day = :day and unavailable = 0 group by day order by day, schedule", ["classroom_fk" => $classroom_fk,
                "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m"),
                "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d")]);
            } else {
                $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and month = :month and day = :day  and discipline_fk = :discipline_fk and unavailable = 0 order by day, schedule", ["classroom_fk" => $classroom_fk, 
                "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m"),
                "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"),
                "discipline_fk" => $discipline_fk]);
            }
             if (!empty($schedule)) {
                
                if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
                    if ($is_minor_schooling) {
                        $courseClasses = Yii::app()->db->createCommand(
                            "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.objective from course_class cc 
                            join course_plan cp on cp.id = cc.course_plan_fk
                            join edcenso_discipline ed on cp.discipline_fk = ed.id
                            where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.users_fk = :users_fk
                            order by ed.name, cp.name"
                        )
                            ->bindParam(":school_inep_fk", Yii::app()->user->school)
                            ->bindParam(":modality_fk", $schedule->classroomFk->edcenso_stage_vs_modality_fk)
                            ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                            ->queryAll();
                    } else {
                        $courseClasses = Yii::app()->db->createCommand(
                            "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.objective from course_class cc 
                            join course_plan cp on cp.id = cc.course_plan_fk
                            join edcenso_discipline ed on cp.discipline_fk = ed.id
                            where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.discipline_fk = :discipline_fk and cp.users_fk = :users_fk
                            order by ed.name, cp.name"
                        )
                            ->bindParam(":school_inep_fk", Yii::app()->user->school)
                            ->bindParam(":modality_fk", $schedule->classroomFk->edcenso_stage_vs_modality_fk)
                            ->bindParam(":discipline_fk",  $discipline_fk)
                            ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                            ->queryAll();
                    }
                }
                $classContents = [];
                foreach ($schedule->classContents as $classContent) {
                    array_push($classContents, $classContent->courseClassFk->id);
                }

                return [
                    "valid" => true,
                    "courseClasses" => $courseClasses,
                    "classContents" => $classContents,
                ];
            } else {
                return ["valid" => false, "error" => "Não existe quadro de horário com dias letivos para o mês selecionado."];
            } 
        }

        /**
         * Summary of SaveClassContents
         * @param mixed $classContent
         * @param mixed $stage_fk
         * @param mixed $date
         * @param mixed $discipline_fk
         * @param mixed $classroom_fk
         * @return void
         */
        public function SaveClassContents($stage_fk, $date, $discipline_fk, $classroom_fk, $classContent)
        {
             // Fundamental menor
             $is_minor_schooling = $stage_fk >= 14 && $stage_fk <= 16;
             if ($is_minor_schooling) 
             {
                 $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and month = :month and day = :day and unavailable = 0 group by day order by day, schedule", 
                 ["classroom_fk" => $classroom_fk,
                 "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m"),
                 "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d")]);
             } else {
                 $schedule = Schedule::model()->find("classroom_fk = :classroom_fk and month = :month and day = :day and discipline_fk = :discipline_fk group by day order by day, schedule",
                  ["classroom_fk" => $classroom_fk, "month" => DateTime::createFromFormat("d/m/Y", $date)->format("m"),
                   "day" => DateTime::createFromFormat("d/m/Y", $date)->format("d"), "discipline_fk" => $discipline_fk]);
             }

            ClassContents::model()->deleteAll("schedule_fk = :schedule_fk", ["schedule_fk" => $schedule->id]);

            $classContent = explode(",", $classContent);                
            foreach ($classContent as $content) {
                $classHasContent = new ClassContents();
                $classHasContent->schedule_fk = $schedule->id;
                $classHasContent->course_class_fk = $content;
                
                $classHasContent->save();
            }

        }
    }

   