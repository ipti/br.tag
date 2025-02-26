<?php
    Yii::import('application.modules.classdiary.services.*');
     /**
    * @property GetAbilities $GetAbilities
    */
    class GetAbilities
    {
        private $courseClassService;
        public function __construct($courseClassService = null)
        {
            $this->courseClassService = $courseClassService ?? new CourseClassService();
        }
        public function exec($discipline_fk, $stage_fk){
                $response = $this->courseClassService->getAbilities($discipline_fk, $stage_fk);
                return  $response;
        }
    }
