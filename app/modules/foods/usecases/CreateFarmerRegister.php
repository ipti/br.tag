<?php
    Yii::import('application.modules.foods.services.*');
    /**
    * @property CreateFarmerRegister $CreateFarmerRegister
    */
    class CreateFarmerRegister
    {
        /**
         * Summary of firebaseservice
         * @var FireBaseService $firebaseservice
         */
        private $firebaseservice;

        public function __construct($firebaseservice = null){
            $this->firebaseservice = $firebaseservice ?? new FireBaseService();
        }
        public function exec($name, $cpf, $phone, $groupType, $foodsRelation){
            return $this->firebaseservice->createFarmerRegister($name, $cpf, $phone, $groupType, $foodsRelation);
        }
    }
