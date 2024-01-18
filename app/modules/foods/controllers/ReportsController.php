<?php
Yii::import('application.modules.foods.usecases.*');
class ReportsController extends Controller
{

	
	public function actionFoodMenuReport($id) {
        $modelFoodMenu = FoodMenu::model()->findByPk($id);
        $modelMenuMeals = FoodMenuMeal::model()->findByattributes(array('food_menuId'=>$foodMenu->id));
        
        $publicTargetSql = "
        SELECT fpt.id, fpt.name FROM food_public_target fpt
        LEFT JOIN food_menu_vs_food_public_target fmvfpt ON fmvfpt.food_public_target_fk = fpt.id
        WHERE fmvfpt.food_menu_fk = :id";
       $publicTarget = Yii::app()->db->createCommand($publicTargetSql)->bindParam(':id', $modelFoodMenu->id)->queryRow();

        $getFoodMenu = new GetFoodMenu();
        $foodMenu  = $getFoodMenu->exec($modelFoodMenu, $publicTarget, $modelMenuMeals);

		$this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render('FoodMenuReport', array("foodMenu" => $foodMenu));
    }
}