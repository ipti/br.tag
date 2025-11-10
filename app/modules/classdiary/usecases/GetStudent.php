<?php

    Yii::import('application.modules.classdiary.services.*');
    /**
    * @property GetStudent $GetStudent
    */
    class GetStudent
    {
        private $studentService;

        public function __construct($studentService = null)
        {
            $this->studentService = $studentService ?? new StudentService();
        }

        public function exec($studentId)
        {
            return $this->studentService->GetStudent($studentId);
        }
    }
