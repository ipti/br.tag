<?php

    Yii::import('application.modules.classdiary.services.*');
    /**
    * @property GetCoursePlans $GetCoursePlans
    */
    class GetCoursePlans
    {
        private $courseClassService;

        public function __construct($courseClassService = null)
        {
            $this->courseClassService = $courseClassService ?? new CourseClassService();
        }

        public function exec($disciplineFk, $stage)
        {
            return $this->courseClassService->getCoursePlans($disciplineFk, $stage);
        }
    }
