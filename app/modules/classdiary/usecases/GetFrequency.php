<?php
    Yii::import('application.modules.classdiary.services.*');
     /**
    * @property GetFrequency $GetFrequency
    */
    class GetFrequency 
    {
        private $studentService;
        public function __construct($studentService = null)
        {
            $this->studentService = $studentService ?? new StudentService();
        }
        public function exec($classrom_fk, $stage_fk, $discipline_fk, $date){
                $response = $this->studentService->getFrequency($classrom_fk, $stage_fk, $discipline_fk, $date);
                return  $response; 
        }
    }