<?php
    Yii::import('application.modules.foods.services.*');
    /**
    * @property GetFarmerRegister $GetFarmerRegister
    */
    class GetFarmerRegister
    {
        /**
         * Summary of firebaseservice
         * @var FireBaseService $firebaseservice
         */
        private $firebaseservice;

        public function __construct($firebaseservice = null){
            $this->firebaseservice = $firebaseservice ?? new FireBaseService();
        }
        /**
         * @return MrShan0\PHPFirestore\FirestoreDocument[]
         */
        public function exec($cpf){
            return $this->firebaseservice->getFarmerRegister($cpf);
        }
    }
