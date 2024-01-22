<?php
Yii::import('application.modules.foods.services.*');
  /**
    * @property CreateFoodMenuRelations $CreateFoodMenuRelations
    */
    class CreateFoodMenuRelations
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
            $response = $this->foodmenuService->createFoodMenuRelations($modelFoodMenu, $publicTarget, $modelMenuMeals);
            return $response;
        }
    }