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
        public function exec($isInstructor, $discipline){
            if ($isInstructor) {
                $response = $this->classService->getClassroomsInstructor($discipline);
                return  $response; 
            } else {
                $response = $this->classService->getClassrooms();
                
                return  $response; 
            }
        }
    }