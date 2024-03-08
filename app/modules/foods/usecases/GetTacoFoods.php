<?php
Yii::import('application.modules.foods.services.*');
  /**
    * @property GetTacoFoods $getTacoFoods
    */
    class GetTacoFoods
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
            return $this->foodmenuService->getTacoFoods();
        }
    }
