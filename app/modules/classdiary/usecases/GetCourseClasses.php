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

        public function exec($courseClassId)
        {
            return $this->courseClassService->getCourseClasses($courseClassId);
        }
    }
