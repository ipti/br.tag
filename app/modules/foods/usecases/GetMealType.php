<?php
Yii::import('application.modules.foods.services.*');
  /**
    * @property GetMealType $GetMealType
    */
    class GetMealType
    {
        /**
         * Summary of foodmenuService
         * @var FoodMenuService $foodmenuService
         */
        private $foodmenuService;

        public function __construct($foodmenuService = null){
            $this->foodmenuService = $foodmenuService ?? new FoodMenuService();
        }
        public function exec($foodMenuId){
            return $this->foodmenuService->getMealType($foodMenuId);
        }
    }
