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

        public function exec($stageFk, $classroomFk, $disciplineFk, $date, $studentFk)
        {
           return $this->studentService->GetStudentDiary($stageFk, $classroomFk, $disciplineFk, $date, $studentFk);
        }
    }
