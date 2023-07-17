<?php
    yii::import('application.modules.classdiary.services.*');
    /**
     * @property SaveJustification $SaveJustification 
     */

    class SaveJustification
    {
        private $studentService;
        public function __construct($studentService = null)
        {
            $this->studentService = $studentService ?? new StudentService();
        }
        public function exec($student_id, $stage_fk, $classroom_id, $schedule, $date, $justification)
        {
            $response = $this->studentService->SaveJustification($student_id, $stage_fk, $classroom_id, $schedule, $date, $justification);
            return $response;
        }
    }