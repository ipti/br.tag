<?php

     Yii::import('application.modules.classdiary.services.*');
     /**
    * @property SaveFrequency $SaveFrequency
    */
     class SaveFrequency
     {
         private $studentService;

         public function __construct($studentService = null)
         {
             $this->studentService = $studentService ?? new StudentService();
         }

         public function exec($schedule, $studentId, $fault, $stageFk, $date, $classroomId)
         {
            return $this->studentService->getSechedulesToSaveFrequency($schedule, $studentId, $fault, $stageFk, $date, $classroomId);
         }
     }
