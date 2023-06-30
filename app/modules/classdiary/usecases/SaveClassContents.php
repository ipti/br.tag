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
            $this->classesService = $classesService ?? new ClassContents();
        }
        public function exec($stage_fk, $date, $discipline_fk, $classroom_fk, $classContent)
        {
            $response = $this->classesService->saveClassContents($stage_fk, $date, $discipline_fk, $classroom_fk, $classContent);
            return  $response; 
        }
    }