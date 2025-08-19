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
        public function exec($discipline_fk, $stage){
                $response = $this->courseClassService->getCoursePlans($discipline_fk, $stage);
                return  $response;
        }
    }
