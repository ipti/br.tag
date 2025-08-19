<?php
    Yii::import('application.modules.foods.services.*');
  /**
    * @property CreateNotice $CreateNotice
    */
    class CreateNotice
    {
        /**
         * Summary of firebaseService
         * @var FireBaseService $firebaseService
         */
        private $firebaseService;

        public function __construct($firebaseService = null){
            $this->firebaseService = $firebaseService ?? new FireBaseService();
        }
        public function exec(){
            return $this->firebaseService->createNotice();
        }
    }
