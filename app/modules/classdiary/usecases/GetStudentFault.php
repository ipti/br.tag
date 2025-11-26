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

        public function exec($stageFk, $classroomFk, $disciplineFk, $date, $studentFk, $schedule)
        {
            return $this->studentService->GetStudentFault($stageFk, $classroomFk, $disciplineFk, $date, $studentFk, $schedule);
        }
    }
