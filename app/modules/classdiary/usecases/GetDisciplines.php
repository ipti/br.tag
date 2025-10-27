<?php

    Yii::import('application.modules.classdiary.services.*');
    /**
    * @property GetDisciplines $GetDisciplines
    */
    class GetDisciplines
    {
        private $instructorService;

        public function __construct($instructorService = null)
        {
            $this->instructorService = $instructorService ?? new InstructorService();
        }

        public function exec()
        {
            return $this->instructorService->getDisciplines();
        }
    }
