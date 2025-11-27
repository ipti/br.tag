<?php

    Yii::import('application.modules.classdiary.services.*');
    /**
    * @property GetClassContents $GetClassContent
    */

    class GetClassContents
    {
        /**
         * Summary of classService
         * @var ClassesService $classService
         */
        private $classService;

        public function __construct($classService = null)
        {
            $this->classService = $classService ?? new ClassesService();
        }

        public function exec($classroomFk, $stageFk, $date, $disciplineFk)
        {
            return $this->classService->getClassContents($classroomFk, $stageFk, $date, $disciplineFk);
        }
    }
