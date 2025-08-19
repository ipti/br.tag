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

        $stagesSQL = "Select esvm.name FROM food_menu_vs_edcenso_stage_vs_modality fmesvsm
        INNER JOIN food_menu fm on fm.id = fmesvsm.food_menu_fk
        INNER JOIN edcenso_stage_vs_modality esvm on esvm.id = fmesvsm.edcenso_stage_vs_modality_fk
        Where fmesvsm.food_menu_fk = :id";

        $stagesNames = Yii::app()->db->createCommand($stagesSQL)
            ->bindParam(':id', $modelFoodMenu->id)
            ->queryColumn();

        $getFoodMenu = new GetFoodMenu();
        $foodMenu = $getFoodMenu->exec($modelFoodMenu, $publicTarget, $modelMenuMeals);

        $getMealTypes = new GetMealType();
        $mealTypes = $getMealTypes->exec($id);

        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $schoolLocation = $school->location;
        $schoolCity = EdcensoCity::model()->findByPk($school->edcenso_city_fk)->name;
        $getNutritionalValue = new GetNutritionalValue();
        $nutritionalValue = $getNutritionalValue->exec($id);

        $includeSatruday = FoodMenu::model()->findByPk($id, array('select' => 'include_saturday'))->include_saturday;




        $this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render('FoodMenuReport', array(
            "foodMenu" => $foodMenu,
            "mealTypes" => $mealTypes,
            "publicTarget" => $publicTarget,
            "schoolLocation" => $schoolLocation,
            "schoolCity" => $schoolCity,
            "nutritionalValue" => $nutritionalValue,
            "include_saturday" => $includeSatruday,
            "stagesNames" => $stagesNames
        )
        );
    }
    public function actionShoppingListReport()
    {
        $getFoodIngredientsList = new GetFoodIngredientsList();
        $resultFoodIngredientsList = $getFoodIngredientsList->exec();
        $foodIngredientsList = $resultFoodIngredientsList["processFood"];
        $totalStudents = $resultFoodIngredientsList["totalStudents"];


        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
        $schoolCity = EdcensoCity::model()->findByPk($school->edcenso_city_fk)->name;
        $schoolName = $school->name;

        $this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render('ShoppingListReport',
        array(
            'foodIngredientsList' => $foodIngredientsList,
            "schoolCity" => $schoolCity,
            "totalStudents"=>$totalStudents,
            "schoolName"=>$schoolName,
        ));
    }
}
