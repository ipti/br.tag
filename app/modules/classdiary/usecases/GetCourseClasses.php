<?php
    Yii::import('application.modules.classdiary.services.*');
     /**
    * @property GetCourseClasses $GetCourseClasses
    */
    class GetCourseClasses 
    {
        private $courseClassService;
        public function __construct($courseClassService = null)
        {
            $this->courseClassService = $courseClassService ?? new CourseClassService();
        }
        public function exec($course_class_id){
                $response = $this->courseClassService->getCourseClasses($course_class_id);
                return  $response; 
        }
    }