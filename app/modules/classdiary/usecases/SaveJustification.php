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

        public function exec($studentId, $stageFk, $classroomId, $schedule, $date, $justification)
        {
            return $this->studentService->SaveJustification($studentId, $stageFk, $classroomId, $schedule, $date, $justification);
        }
    }
