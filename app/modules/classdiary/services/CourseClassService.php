<?php
    class CourseClassService 
    {
		public function getCourseClassHasClassType($course_class_id) {
			$types = CourseClassHasClassType::model()->findAllByAttributes(array(
				'course_class_fk' => $course_class_id,
			));
			echo "<pre>";
			var_dump($types);
			echo "</pre>";
			
			exit();
			return $types;
			
		}
		public function getCourseClassHasClassResource($course_class_id) {
			$resources = CourseClassHasClassResource::model()->findByPk($course_class_id);
			return $resources;
			 
		}
	}