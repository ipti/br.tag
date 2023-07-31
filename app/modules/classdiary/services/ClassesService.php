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
             if ($discipline != "") {
                $sql = "SELECT itd.id, esvm.id as stage_fk, ii.name as instructor_name, ed.id as edcenso_discipline_fk, ed.name as discipline_name, esvm.name as stage_name, c.name  
                from instructor_teaching_data itd 
                join teaching_matrixes tm ON itd.id = tm.teaching_data_fk 
                join instructor_identification ii on itd.instructor_fk = ii.id 
                join curricular_matrix cm on tm.curricular_matrix_fk = cm.id 
                JOIN edcenso_discipline ed on ed.id = cm.discipline_fk 
                join classroom c on c.id = itd.classroom_id_fk  
                Join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk  
                WHERE ii.users_fk = :users_fk and esvm.id NOT BETWEEN 14 AND 16 and ed.id = :discipline_id and c.school_year = :user_year
                ORDER BY ii.name";

                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
                ->bindValue(':discipline_id', $discipline, PDO::PARAM_INT)
                ->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT);
            } else {
                $sql = "SELECT itd.id, esvm.id as stage_fk, ii.name as instructor_name, ed.id as edcenso_discipline_fk, ed.name as discipline_name, esvm.name as stage_name, c.name  
                from instructor_teaching_data itd 
                join teaching_matrixes tm ON itd.id = tm.teaching_data_fk 
                join instructor_identification ii on itd.instructor_fk = ii.id 
                join curricular_matrix cm on tm.curricular_matrix_fk = cm.id 
                JOIN edcenso_discipline ed on ed.id = cm.discipline_fk 
                join classroom c on c.id = itd.classroom_id_fk  
                Join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk  
                WHERE ii.users_fk = :users_fk and esvm.id NOT BETWEEN 14 AND 16 and c.school_year = :user_year
                ORDER BY ii.name";

                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
                ->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT);
            }
            
            $classrooms = $command->queryAll(); 
            $minorSchoolingClassroom = $this->getMinorSchoolingClassrooms();
            /* echo "<pre>";
            var_dump($minorSchoolingClassroom[0]["turma"]["name"]);
            echo "</pre>"; */
            return [
                "valid" => true,
                "classrooms" => $classrooms,
                "minorSchoolingClassroom" => $minorSchoolingClassroom
            ] ;
        }
        public function getMinorSchoolingClassrooms() {
            $criteria = new CDbCriteria;
            $criteria->alias = "c";
            $criteria->select = "c.id, c.name, c.edcenso_stage_vs_modality_fk, esvm.name"; 
            $criteria->join = ""
                . " join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id "
                . " join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id "
                ."  Join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk";
            $criteria->condition = "c.school_year = :school_year and instructor_identification.users_fk = :users_fk";
            $criteria->order = "name";
            $criteria->addCondition('esvm.id BETWEEN :start_id AND :end_id');
            $criteria->params = array(':school_year' => Yii::app()->user->year, ':users_fk' => Yii::app()->user->loginInfos->id, 'start_id' =>14,'end_id' => 16);
            $minorSchoolingClassroom = Classroom::model()->findAll($criteria);

            // $results = array();
            foreach ($minorSchoolingClassroom as $c) {
                $disciplines = Yii::app()->db->createCommand(
                    "select ed.id, ed.name as discipline_name from teaching_matrixes tm 
                    join instructor_teaching_data itd on itd.id = tm.teaching_data_fk 
                    join instructor_identification ii on ii.id = itd.instructor_fk
                    join curricular_matrix cm on cm.id = tm.curricular_matrix_fk
                    join edcenso_discipline ed on ed.id = cm.discipline_fk
                    where ii.users_fk = :userid and itd.classroom_id_fk = :crid order by ed.name")
                    ->bindParam(":userid", Yii::app()->user->loginInfos->id)->bindParam(":crid", $c->id)->queryAll();
                    echo "<pre>";
                    var_dump($c);
                    echo "</pre>";
                    exit();
                    $result["turma"]["name"] = $c->name;
                    $result["turma"]["stage_name"] = $c->stage_name;
                    $result["disciplines"] = $disciplines;
                    // array_push($results[$c->id], $result);
                    $results[] = $result;
            }
            
            
           
            return $results;
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

   