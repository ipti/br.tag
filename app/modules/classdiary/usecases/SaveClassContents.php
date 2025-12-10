<?php

    yii::import('application.modules.classdiary.services.*');
    /**
     * @property SaveClassContents $SaveClassContents
     */
    class SaveClassContents
    {
        private $classesService;

        public function __construct($classesService = null)
        {
            $this->classesService = $classesService ?? new ClassesService();
        }

        public function exec($stageFk, $date, $disciplineFk, $classroomFk, $classContent)
        {
            return $this->classesService->saveClassContents($stageFk, $date, $disciplineFk, $classroomFk, $classContent);
        }
    }
