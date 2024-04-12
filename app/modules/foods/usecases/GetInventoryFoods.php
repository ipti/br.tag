<?php
Yii::import('application.modules.food.services.*');

class GetInventoryFoods{

    private $mealId;

    public function __construct($mealId = null)
    {
        $this->mealId = $mealId;
    }


    public function execute()
    {

        $meal = FoodMenuMeal::model()->with('foodMenuMealComponents.foodIngredients')->findByPk($this->mealId);

        $foods = [];

        foreach ($meal->foodMenuMealComponents as $component) {
            foreach ($component->foodIngredients as $foodIngredient) {
                array_push($foods, $foodIngredient->food_id_fk);
            }
        }
        $school_test = Yii::app()->user->school;

        $criteria = new CDbCriteria();
        $criteria->condition = "status = :status AND school_fk = :schoolId";
        $criteria->params = [
            ':status' => 'Emfalta',
            ':schoolId' => $school_test,
        ];
        $criteria->addInCondition('id', $foods);
        $criteria->params = array_merge([":status" => 'Emfalta'], $criteria->params);


        $inventoryData = FoodInventory::model()->findAll($criteria);

        CVarDumper::dump($meal, 10, true);

        $result = array_map(function ($item) {
            return $item->attributes;
        }, $inventoryData);

        return $result;
    }
    
}


	// $criteria = new CDbCriteria();
    //     $criteria->condition = "status = :status";
    //     $criteria->addInCondition('id', $foods);
    //     $criteria->params = array_merge([":status" => 'Emfalta'], $criteria->params);

    //     $inventoryData = FoodInventory::model()->findAll($criteria);

    //     CVarDumper::dump($inventoryData, 10, true);

    //     $result = array_map(function ($item) {
    //         return $item->attributes;
    //     }, $inventoryData);

    //     return $result;






//   /**
//     * @property CreateFoodMenuRelations $CreateFoodMenuRelations
//     */
//     class CreateFoodMenuRelations
//     {
//         /**
//          * Summary of foodmenuService
//          * @var FoodMenuService $foodmenuService
//          */
//         private $foodmenuService;

//         public function __construct($foodmenuService = null){
//             $this->foodmenuService = $foodmenuService ?? new FoodMenuService();
//         }
//         public function exec($modelFoodMenu, $publicTarget, $modelMenuMeals){
//             return $this->foodmenuService->createFoodMenuRelations($modelFoodMenu, $publicTarget, $modelMenuMeals);
//         }
//     }
