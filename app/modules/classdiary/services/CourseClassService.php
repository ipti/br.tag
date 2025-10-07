<?php

    class CourseClassService
    {
        public function getCourseClasses($course_plan_id)
        {
            $coursePlan = CoursePlan::model()->findByPk($course_plan_id);
            $courseClasses = [];
            foreach ($coursePlan->courseClasses as $courseClass) {
                $order = $courseClass->order - 1;
                $courseClasses[$order] = [];
                $courseClasses[$order]['class'] = $courseClass->order;
                $courseClasses[$order]['courseClassId'] = $courseClass->id;
                $courseClasses[$order]['objective'] = $courseClass->objective;
                $courseClasses[$order]['types'] = [];
                $courseClasses[$order]['resources'] = [];
                $courseClasses[$order]['abilities'] = [];
                foreach ($courseClass->courseClassHasClassResources as $courseClassHasClassResource) {
                    $resource['id'] = $courseClassHasClassResource->id;
                    $resource['value'] = $courseClassHasClassResource->course_class_resource_fk;
                    $resource['description'] = $courseClassHasClassResource->courseClassResourceFk->name;
                    $resource['amount'] = $courseClassHasClassResource->amount;
                    array_push($courseClasses[$order]['resources'], $resource);
                }
                foreach ($courseClass->courseClassHasClassTypes as $courseClassHasClassType) {
                    array_push($courseClasses[$order]['types'], $courseClassHasClassType->course_class_type_fk);
                }
                foreach ($courseClass->courseClassHasClassAbilities as $courseClassHasClassAbility) {
                    $ability['id'] = $courseClassHasClassAbility->courseClassAbilityFk->id;
                    $ability['code'] = $courseClassHasClassAbility->courseClassAbilityFk->code;
                    $ability['description'] = $courseClassHasClassAbility->courseClassAbilityFk->description;
                    array_push($courseClasses[$order]['abilities'], $ability);
                }
                $courseClasses[$order]['deleteButton'] = empty($courseClass->classContents) ? '' : 'js-unavailable';
            }
            echo json_encode(['data' => $courseClasses]);
        }

        public function GetCoursePlans($discipline_fk, $stage_fk)
        {
            $year = (int)Yii::app()->user->year;

            $criteria = new CDbCriteria();
            $criteria->condition = 'users_fk = :user_id AND YEAR(start_date) = :year AND school_inep_fk= :school_fk';
            $criteria->params = [
                ':user_id' => Yii::app()->user->loginInfos->id,
                ':year' => $year,
                ':school_fk' => Yii::app()->user->school,
            ];

            if (!TagUtils::isStageMinorEducation($stage_fk)) {
                // Se não for Minor Education, filtra por disciplina e modalidade diretamente no banco
                $criteria->condition .= ' AND discipline_fk = :discipline_fk AND modality_fk = :stage_fk';
                $criteria->params[':discipline_fk'] = $discipline_fk;
                $criteria->params[':stage_fk'] = $stage_fk;

                $courses = CoursePlan::model()->findAll($criteria);
            } else {
                // Se for Minor Education, busca sem filtrar por etapa no banco
                $courses = CoursePlan::model()->findAll($criteria);

                // Filtra manualmente para manter apenas os que pertencem à Minor Education
                $courses = array_filter($courses, function ($course) {
                    return TagUtils::isStageMinorEducation($course->modality_fk);
                });
            }

            return CHtml::listData($courses, 'id', 'name');
        }

        public function GetAbilities($discipline_fk, $stage_fk)
        {
            $criteria = new CDbCriteria();
            if (!TagUtils::isStageMinorEducation($stage_fk)) {
                $criteria->addCondition('code IS NOT NULL AND edcenso_discipline_fk = :disciplineFK');
                $criteria->params = [':disciplineFK' => $discipline_fk];
            } else {
                $criteria->addCondition('code IS NOT NULL');
            }

            $abilities = CourseClassAbilities::model()->findAll($criteria);

            $formattedAbilities = [];
            foreach ($abilities as $ability) {
                $formattedAbilities[$ability->id] = "({$ability->code}) {$ability->description}";
            }

            return $formattedAbilities;
        }
    }
