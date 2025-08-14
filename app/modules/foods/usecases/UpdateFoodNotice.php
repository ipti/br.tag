<?php

    Yii::import('application.modules.foods.services.*');
    /**
     * @property UpdateFoodNotice $UpdateFoodNotice
     */
    class UpdateFoodNotice
    {
        /**
         * Summary of firebaseservice.
         * @var FireBaseService $firebaseservice
         */
        private $firebaseservice;

        public function __construct($firebaseservice = null)
        {
            $this->firebaseservice = $firebaseservice ?? new FireBaseService();
        }

        /**
         * @return MrShan0\PHPFirestore\FirestoreDocument[]
         */
        public function exec($noticeData, $noticeId)
        {
            return $this->firebaseservice->updateFoodNotice($noticeData, $noticeId);
        }
    }
