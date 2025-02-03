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
                $sql = "SELECT c.id, esvm.id as stage_fk, ii.name as instructor_name, ed.id as edcenso_discipline_fk,
                ed.name as discipline_name, esvm.name as stage_name, c.name
                from instructor_teaching_data itd
                join teaching_matrixes tm ON itd.id = tm.teaching_data_fk
                join instructor_identification ii on itd.instructor_fk = ii.id
                join curricular_matrix cm on tm.curricular_matrix_fk = cm.id
                JOIN edcenso_discipline ed on ed.id = cm.discipline_fk
                join classroom c on c.id = itd.classroom_id_fk
                Join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk
                WHERE ii.users_fk = :users_fk and esvm.id NOT BETWEEN 14 AND 18 and esvm.unified_frequency = :unified_frequency
                and ed.id = :discipline_id and c.school_year = :user_year
                ORDER BY ii.name";

                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
                ->bindValue(':discipline_id', $discipline, PDO::PARAM_INT)
                ->bindValue(':unified_frequency', 0, PDO::PARAM_INT)
                ->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT);
            } else {
                $sql = "SELECT c.id, esvm.id as stage_fk, ii.name as instructor_name, ed.id as edcenso_discipline_fk, ed.name as discipline_name, esvm.name as stage_name, c.name
                from instructor_teaching_data itd
                join teaching_matrixes tm ON itd.id = tm.teaching_data_fk
                join instructor_identification ii on itd.instructor_fk = ii.id
                join curricular_matrix cm on tm.curricular_matrix_fk = cm.id
                JOIN edcenso_discipline ed on ed.id = cm.discipline_fk
                join classroom c on c.id = itd.classroom_id_fk
                Join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk
                WHERE ii.users_fk = :users_fk and esvm.id NOT BETWEEN 14 AND 18 and c.school_year = :user_year and esvm.unified_frequency = :unified_frequency
                ORDER BY ii.name";

                $command = Yii::app()->db->createCommand($sql);
                $command->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
                ->bindValue(':unified_frequency', 0, PDO::PARAM_INT)
                ->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT);
            }

            $classrooms = $command->queryAll();
            $minorSchoolingClassroom = $this->getMinorSchoolingClassrooms();

            return [
                "valid" => true,
                "classrooms" => $classrooms,
                "minorSchoolingClassroom" => $minorSchoolingClassroom
            ] ;
        }
        public function getMinorSchoolingClassrooms() {
            $sql = "SELECT c.id, esvm.name as stage_name, c.name, c.edcenso_stage_vs_modality_fk as stage_fk
            from classroom c
            join instructor_teaching_data on instructor_teaching_data.classroom_id_fk = c.id
            join instructor_identification on instructor_teaching_data.instructor_fk = instructor_identification.id
            join edcenso_stage_vs_modality esvm on esvm.id = c.edcenso_stage_vs_modality_fk
            where c.school_year = :school_year and instructor_identification.users_fk = :users_fk and( esvm.id BETWEEN 14 AND 18 or esvm.unified_frequency = :unified_frequency) ";
             $command = Yii::app()->db->createCommand($sql);
             $command->bindValue(':school_year', Yii::app()->user->year, PDO::PARAM_INT)
             ->bindValue(':unified_frequency', 1, PDO::PARAM_INT)
             ->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT);
             $minorSchoolingClassroom = $command->queryAll();



            return $minorSchoolingClassroom;
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
            $classroom = Classroom::model()->findByPk($classroom_fk);
            $is_minor_schooling = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : TagUtils::isStageMinorEducation($stage_fk);
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
                            "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.content from course_class cc
                            join course_plan cp on cp.id = cc.course_plan_fk
                            join edcenso_discipline ed on cp.discipline_fk = ed.id
                            where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.users_fk = :users_fk
                            order by ed.name, cp.name"
                        )
                            ->bindParam(":school_inep_fk", Yii::app()->user->school)
                            ->bindParam(":modality_fk", $schedule->classroomFk->edcenso_stage_vs_modality_fk)
                            ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                            ->queryAll();

                            $additionalClasses = Yii::app()->db->createCommand(
                                "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.content, cp.id as cpid
                                from course_class cc
                                join course_plan cp on cp.id = cc.course_plan_fk
                                join course_plan_discipline_vs_abilities dvsa on dvsa.course_class_fk = cc.id
                                join edcenso_discipline ed on ed.id = dvsa.discipline_fk
                                where cp.school_inep_fk = :school_inep_fk and cp.modality_fk = :modality_fk and cp.users_fk = :users_fk
                                order by ed.name, cp.name"
                            )
                                ->bindParam(":school_inep_fk", Yii::app()->user->school)
                                ->bindParam(":modality_fk", $schedule->classroomFk->edcenso_stage_vs_modality_fk)
                                ->bindParam(":users_fk", Yii::app()->user->loginInfos->id)
                                ->queryAll();

                            $courseClasses = array_merge($courseClasses, $additionalClasses);
                    } else {
                        $courseClasses = Yii::app()->db->createCommand(
                            "select cc.id, cp.name as cpname, ed.id as edid, ed.name as edname, cc.order, cc.content from course_class cc
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
             $classroom = Classroom::model()->findByPk($classroom_fk);
             $is_minor_schooling = $classroom->edcensoStageVsModalityFk->unified_frequency == 1 ? true : TagUtils::isStageMinorEducation($stage_fk);
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

            foreach ($classContent as $content) {
                $classHasContent = new ClassContents();
                $classHasContent->schedule_fk = $schedule->id;
                $classHasContent->course_class_fk = $content;
                $classHasContent->day = $schedule->day;
                $classHasContent->month = $schedule->month;
                $classHasContent->year = $schedule->year;
                $classHasContent->classroom_fk = $schedule->classroom_fk;
                $classHasContent->discipline_fk = $schedule->discipline_fk;

                $classHasContent->save();
            }

        }
        public function SaveNewClassContent($coursePlanId, $content, $methodology, $abilities) {

            $nextOrder = Yii::app()->db->createCommand("
            SELECT MAX(`order`) AS max_order
            FROM course_class
            WHERE course_plan_fk = :coursePlanId
        ")
            ->bindParam(":coursePlanId", $coursePlanId, PDO::PARAM_INT)
            ->queryRow();


            $nextOrderValue = $nextOrder['max_order']  + 1;

            $courseClass = new CourseClass();
            $courseClass->course_plan_fk = $coursePlanId;
            $courseClass->content = $content;
            $courseClass->methodology = $methodology;
            $courseClass->order = $nextOrderValue;
            $courseClass->save();



            foreach ($abilities as $ability) {
                $courseClassAbility = new CourseClassHasClassAbility();
                $courseClassAbility->course_class_fk = $courseClass->id;
                $courseClassAbility->course_class_ability_fk = $ability;
                $courseClassAbility->save();
            }

            return $courseClass->id;
        }
    }

