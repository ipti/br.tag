<?php

    Yii::import('application.modules.classdiary.services.*');

    /**
    * @property GetClassrooms $GetClassrooms
    */

    class GetClassrooms
    {
        private $classService;

        public function __construct($classService = null)
        {
            $this->classService = $classService ?? new ClassesService();
        }

        public function exec($isInstructor, $discipline)
        {
            return $isInstructor?$this->classService->getClassroomsInstructor($discipline):$this->classService->getClassrooms();
        }
    }
