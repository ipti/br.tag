<?php
    Yii::import('application.modules.classdiary.services.*');
     /**
    * @property GetHasClassType $GetHasClassType
    */
    class GetHasClassType 
    {
        private $courseClassService;
        public function __construct($courseClassService = null)
        {
            $this->courseClassService = $courseClassService ?? new CourseClassService();
        }
        public function exec($course_class_id){
                $response = $this->courseClassService->getCourseClassHasClassType($course_class_id);
                return  $response; 
        }
    }