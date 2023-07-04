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

        public function __construct($classService = null){
            $this->classService = $classService ?? new ClassesService();
        }
        public function exec($classroom_fk, $stage_fk, $date, $discipline_fk){
            $response = $this->classService->getClassContents($classroom_fk, $stage_fk, $date, $discipline_fk);
            return $response;
        }
    }