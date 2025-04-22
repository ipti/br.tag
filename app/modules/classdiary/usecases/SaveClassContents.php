<?php
    yii::import('application.modules.classdiary.services.*');
    /**
     * @property SaveClassContents $SaveClassContents
     */
    class SaveClassContents
    {
        private $classesService;
        public function __construct($classesService = null)
        {
            $this->classesService = $classesService ?? new ClassesService();
        }
        public function exec($date, $discipline_fk, $classroom_fk, $classContent)
        {
            $response = $this->classesService->saveClassContents($date, $discipline_fk, $classroom_fk, $classContent);
            return  $response;
        }
    }
