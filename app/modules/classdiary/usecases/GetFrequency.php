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
        public function exec($classroom_fk, $stage_fk, $discipline_fk, $date){
                $response = $this->studentService->getFrequency($classroom_fk, $stage_fk, $discipline_fk, $date);
                return  $response; 
        }
    }