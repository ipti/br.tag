<?php
    yii::import('application.modules.classdiary.services.*');
    /**
     * @property SaveNewClassContent $SaveNewClassContent
     */
    class SaveNewClassContent
    {
        private $classesService;
        public function __construct($classesService = null)
        {
            $this->classesService = $classesService ?? new ClassesService();
        }
        public function exec($coursePlanId, $content, $methodology, $abilities)
        {
            $response = $this->classesService->SaveNewClassContent($coursePlanId, $content, $methodology, $abilities);
            return  $response;
        }
    }
