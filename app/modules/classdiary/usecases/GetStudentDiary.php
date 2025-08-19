<?php
    Yii::import('application.modules.classdiary.services.*');
     /**
    * @property GetStudentDiary $GetStudentDiary
    */
    class GetStudentDiary 
    {
        private $studentService;
        public function __construct($studentService = null)
        {
            $this->studentService = $studentService ?? new StudentService();
        }
        public function exec($stage_fk, $classroom_fk, $discipline_fk, $date, $student_fk){
                $response = $this->studentService->GetStudentDiary($stage_fk, $classroom_fk, $discipline_fk, $date, $student_fk);
                return  $response; 
        }
    }