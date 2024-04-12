<?php
    Yii::import('application.modules.foods.services.*');
    /**
    * @property CreateFoodRequest $CreateFoodRequest
    */
    class CreateFoodRequest
    {
        /**
         * Summary of firebaseservice
         * @var FireBaseService $firebaseservice
         */
        private $firebaseservice;

        public function __construct($firebaseservice = null){
            $this->firebaseservice = $firebaseservice ?? new FireBaseService();
        }
        public function exec($food, $amount, $measurementUnit,$description, $status, $school, $farmerId){
            return $this->firebaseservice->createFoodRequest($food, $amount, $measurementUnit,$description, $status, $school, $farmerId);
        }
    }
