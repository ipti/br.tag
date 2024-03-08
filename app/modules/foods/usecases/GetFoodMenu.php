<?php
Yii::import('application.modules.foods.services.*');
  /**
    * @property GetFoodMenu $GetFoodMenu
    */
    class GetFoodMenu
    {
        /**
         * Summary of foodmenuService
         * @var FoodMenuService $foodmenuService
         */
        private $foodmenuService;

        public function __construct($foodmenuService = null){
            $this->foodmenuService = $foodmenuService ?? new FoodMenuService();
        }
        public function exec($modelFoodMenu, $publicTarget, $modelMenuMeals){
            return $this->foodmenuService->getFoodMenu($modelFoodMenu, $publicTarget, $modelMenuMeals);
        }
    }
