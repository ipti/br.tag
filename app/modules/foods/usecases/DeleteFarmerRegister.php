<?php
    Yii::import('application.modules.foods.services.*');
    /**
    * @property DeleteFarmerRegister $DeleteFarmerRegister
    */
    class DeleteFarmerRegister
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
        public function exec($farmerId){
            return $this->firebaseservice->deleteFarmerRegister($farmerId);
        }
    }
