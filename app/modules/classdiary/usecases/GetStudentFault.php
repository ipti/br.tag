<?php

    Yii::import('application.modules.classdiary.services.*');
    /**
     * @property GetStudentFault $GetStudentFault
     */
    class GetStudentFault
    {
        private $studentService;

        public function __construct($studentService = null)
        {
            $this->studentService = $studentService ?? new StudentService();
        }

        public function exec($stage_fk, $classroom_fk, $discipline_fk, $date, $student_fk, $schedule)
        {
            $response = $this->studentService->GetStudentFault($stage_fk, $classroom_fk, $discipline_fk, $date, $student_fk, $schedule);

            return $response;
        }
    }
