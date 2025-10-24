<?php

    yii::import('application.modules.classdiary.services.*');
    /**
     * @property SaveStudentDiary $SaveStudentDiary
     */

    class SaveStudentDiary
    {
        private $studentService;

        public function __construct($studentService = null)
        {
            $this->studentService = $studentService ?? new StudentService();
        }

        public function exec($stageFk, $classroomId, $date, $disciplineFk, $studentId, $studentObservation)
        {
            return $this->studentService->saveStudentDiary($stageFk, $classroomId, $date, $disciplineFk, $studentId, $studentObservation);

        }
    }
