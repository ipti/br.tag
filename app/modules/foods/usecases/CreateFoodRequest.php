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
        public function exec($noticeId, $requestSchools, $requestFarmers, $requestItems){
            return $this->firebaseservice->createFoodRequest($noticeId, $requestSchools, $requestFarmers, $requestItems);
        }
    }
