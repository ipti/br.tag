<?php
    Yii::import('application.modules.foods.services.*');
    /**
    * @property UpdateFarmerRegister $UpdateFarmerRegister
    */
    class UpdateFarmerRegister
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
        public function exec($farmerId, $name, $cpf, $phone, $groupType, $foodsRelation){
            return $this->firebaseservice->updateFarmerRegister($farmerId ,$name, $cpf, $phone, $groupType, $foodsRelation);
        }
    }
