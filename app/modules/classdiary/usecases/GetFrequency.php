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

        public function exec($classroomFk, $stageFk, $disciplineFk, $date)
        {
            return $this->studentService->getFrequency($classroomFk, $stageFk, $disciplineFk, $date);
        }
    }
