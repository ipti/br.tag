<?php
Yii::import('application.modules.foods.usecases.*');
class ReportsController extends Controller
{


    public function actionFoodMenuReport($id)
    {
        $modelFoodMenu = FoodMenu::model()->findByPk($id);
        $modelMenuMeals = FoodMenuMeal::model()->findAllByAttributes(array('food_menuId' => $modelFoodMenu->id));

        $publicTargetSql = "
        SELECT fpt.id, fpt.name FROM food_public_target fpt
        LEFT JOIN food_menu_vs_food_public_target fmvfpt ON fmvfpt.food_public_target_fk = fpt.id
        WHERE fmvfpt.food_menu_fk = :id";
        $publicTarget = Yii::app()->db->createCommand($publicTargetSql)->bindParam(':id', $modelFoodMenu->id)->queryRow();

        $getFoodMenu = new GetFoodMenu();
        $foodMenu = $getFoodMenu->exec($modelFoodMenu, $publicTarget, $modelMenuMeals);

        $getMealTypes = new GetMealType();
        $mealTypes = $getMealTypes->exec($id);

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $schoolLocation = $school->location;
        $schoolCity = EdcensoCity::model()->findByPk($school->edcenso_city_fk)->name;
        $getNutritionalValue = new GetNutritionalValue();
        $nutritionalValue = $getNutritionalValue->exec($id);



        $this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render('FoodMenuReport', array(
            "foodMenu" => $foodMenu,
            "mealTypes" => $mealTypes,
            "publicTarget" => $publicTarget,
            "schoolLocation" => $schoolLocation,
            "schoolCity" => $schoolCity,
            "nutritionalValue" => $nutritionalValue
        )
        );
    }
    public function actionShoppingListReport()
    {
        $getFoodIngredientsList = new GetFoodIngredientsList();
        $foodIngredientsList = $getFoodIngredientsList->exec();

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $schoolCity = EdcensoCity::model()->findByPk($school->edcenso_city_fk)->name;

        $this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render('ShoppingListReport', array('foodIngredientsList' => $foodIngredientsList, "schoolCity" => $schoolCity));
    }
}
