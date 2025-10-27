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

        public function exec($disciplineFk, $stageFk)
        {
            return $this->courseClassService->getAbilities($disciplineFk, $stageFk);
        }
    }
