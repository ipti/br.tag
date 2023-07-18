<?php
    class CourseClassService 
    {
		public function getCourseClasses($course_plan_id)
		{
			$coursePlan = CoursePlan::model()->findByPk($course_plan_id);
			echo "<pre>";
			var_dump($course_plan_id);
			echo "</pre>";
			exit();
			$courseClasses = [];
			foreach ($coursePlan->courseClasses as $courseClass) {
				$order = $courseClass->order - 1;
				$courseClasses[$order] = [];
				$courseClasses[$order]["class"] = $courseClass->order;
				$courseClasses[$order]['courseClassId'] = $courseClass->id;
				$courseClasses[$order]['objective'] = $courseClass->objective;
				$courseClasses[$order]['types'] = [];
				$courseClasses[$order]['resources'] = [];
				$courseClasses[$order]['abilities'] = [];
				foreach ($courseClass->courseClassHasClassResources as $courseClassHasClassResource) {
					$resource["id"] = $courseClassHasClassResource->id;
					$resource["value"] = $courseClassHasClassResource->course_class_resource_fk;
					$resource["description"] = $courseClassHasClassResource->courseClassResourceFk->name;
					$resource["amount"] = $courseClassHasClassResource->amount;
					array_push($courseClasses[$order]['resources'], $resource);
				}
				foreach ($courseClass->courseClassHasClassTypes as $courseClassHasClassType) {
					array_push($courseClasses[$order]['types'], $courseClassHasClassType->course_class_type_fk);
				}
				foreach ($courseClass->courseClassHasClassAbilities as $courseClassHasClassAbility) {
					$ability["id"] = $courseClassHasClassAbility->courseClassAbilityFk->id;
					$ability["code"] = $courseClassHasClassAbility->courseClassAbilityFk->code;
					$ability["description"] = $courseClassHasClassAbility->courseClassAbilityFk->description;
					array_push($courseClasses[$order]['abilities'], $ability);
				}
					$courseClasses[$order]["deleteButton"] = empty($courseClass->classContents) ? "" : "js-unavailable";
				}
			//echo json_encode(["data" => $courseClasses]);
			echo "<pre>";
			var_dump($courseClasses);
			echo "</pre>";
			exit();
		}
	}