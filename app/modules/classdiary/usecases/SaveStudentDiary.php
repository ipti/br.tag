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
        public function exec($stage_fk, $classroom_id, $date, $discipline_fk, $student_id, $student_observation)
        {
            $response = $this->studentService->saveStudentDiary($stage_fk, $classroom_id, $date, $discipline_fk, $student_id, $student_observation);
            return $response;
        }
    }