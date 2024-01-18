<?php
Yii::import('application.modules.foods.services.*');
    /**
    * @property GetMelsOfWeek $GetMelsOfWeek
    */
    class GetMelsOfWeek
    {
         /**
         * Summary of foodmenuService
         * @var FoodMenuService $foodmenuService
         */
        private $foodmenuService;
        public function __construct($foodmenuService = null){
            $this->foodmenuService = $foodmenuService ?? new FoodMenuService();
        }
        public function exec(){
            $response = $this->foodmenuService->getMelsOfWeek();
            return $response;
        }
    }