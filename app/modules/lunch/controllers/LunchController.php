<?php

Yii::import('application.modules.foods.models.FoodMeasurement', true);
/**
 * Class LunchController.
 *
 * @property School $school
 */
class LunchController extends Controller
{
    public $school;

    public function init()
    {
        $this->school = School::model()->findByPk(yii::app()->user->school);
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionCreate()
    {
        $request = Yii::app()->getRequest();
        $menuPost = $request->getPost('Menu', false);

        $menu = new Menu();

        $menu->date = date('Y-m-d', strtotime(str_replace('/', '-', isset($menuPost['date']) ? $menuPost['date'] : date('Y-m-d'))));
        $menu->turn = $menuPost['turn'];
        if ($menuPost) {
            $menu->attributes = $menuPost;
            $menu->date = date('Y-m-d', strtotime(str_replace('/', '-', $menuPost['date'])));
            $menu->school_fk = $this->school->inep_id;

            if ($menu->validate()) {
                $menu->save();
                Log::model()->saveAction('lunch_menu', $menu->id, 'C', $menu->name);
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Menu inserted successfully!'));
                $this->redirect(yii::app()->createUrl('lunch/lunch/update', ['id' => $menu->id]));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when adding menu.'));
                $this->render('create', ['menu' => $menu]);
            }
        } else {
            $this->render('create', ['menu' => $menu]);
        }
    }

    public function actionUpdate($id)
    {
        /* @var $menu Menu */
        $request = Yii::app()->getRequest();
        $menuPost = $request->getPost('Menu', false);

        $menu = Menu::model()->findByPk($id);
        $menuMeals = MenuMeal::model()->findAllByAttributes(['menu_fk' => $menu->id]);
        $meals = [];
        foreach ($menuMeals as $menuMeal) {
            // Array temporário para inserir porções de uma refeição
            $tmpPortions = [];
            // Encontrar todas as porções que pertencem à uma refeição
            $mealsPortion = MealPortion::model()->findAllByAttributes(['meal_fk' => $menuMeal->id]);

            // Inserir porções em array temporário
            foreach ($mealsPortion as $mealPortion) {
                $tmpPortions[] = Portion::model()->findByPk($mealPortion->portion_fk);
            }

            // Inserir par Refeição:Porções em array Refeições

                $meals[] =
                [
                    'meal' => Meal::model()->findByPk($menuMeal->meal_fk),
                    'portions' => $tmpPortions,
                ]
            ;
        }

        if ($menuPost) {
            $menu->name = $menuPost['name'];
            $menu->date = date('Y-m-d', strtotime(str_replace('/', '-', $menuPost['date'])));
            $menu->turn = $menuPost['turn'];
            if ($menu->validate()) {
                $menu->save();
                Log::model()->saveAction('lunch_menu', $menu->id, 'U', $menu->name);
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Menu updated successfully!'));
                $this->redirect(yii::app()->createUrl('lunch/lunch/update', ['id' => $menu->id]));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when updating menu.'));
                $this->render('update', ['menu' => $menu]);
            }
        } else {
            $this->render('update', ['menu' => $menu, 'meals' => $meals]);
        }
    }

    public function actionLunchDelete()
    {
        $request = Yii::app()->getRequest();
        $menu = Menu::model()->findByPk($request->getPost('id'));

        return $menu->delete();
    }

    public function actionRemovePortion()
    {
        /* @var $mealPortion MealPortion */

        $request = Yii::app()->getRequest();
        $portionId = $request->getPost('id', false);
        $menuPost = $request->getPost('menu', false);

        $portion = Portion::model()->findByPk($portionId);
        $mealPortion = MealPortion::model()->findByAttributes(['portion_fk' => $portion->id]);
        if (isset($portion) && isset($mealPortion)) {
            if ($mealPortion->delete() && $portion->delete()) {
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Portion removed successfully!'));
            }
        } else {
            Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error, portion not found.'));
        }
        $this->redirect(yii::app()->createUrl('lunch/lunch/update', ['id' => $menuPost['id']]));
    }

    public function actionGetFoodAlias()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, description, measurementUnit';
        $criteria->condition = 'alias_id = t.id';

        $foods_description = Food::model()->findAll($criteria);

        return $foods_description;
    }

    public function actionGetFoodMeasurement()
    {
        $foodMeasurements = FoodMeasurement::model()->findAll();
        $options = [];
        foreach ($foodMeasurements as $foodMeasurement) {
                $options[] =
                [
                    'id' => $foodMeasurement->id,
                    'unit' => $foodMeasurement->unit,
                    'value' => $foodMeasurement->value,
                    'measure' => $foodMeasurement->measure,
                ]
            ;
        }

        return $options;
    }

    public function actionGetUnityMeasure()
    {
        $measureId = Yii::app()->request->getPost('id');
        $modal = FoodMeasurement::model()->findByPk($measureId);

        echo CJSON::encode($modal);
    }

    public function actionAddPortion()
    {
        /* @var $mealPortion MealPortion */
        /* @var $portion Portion */

        $request = Yii::app()->getRequest();
        $menuPost = $request->getPost('Menu', false);
        $mealPortionPost = $request->getPost('MealPortion', false);

        $isNewPortion = false;
        if ($mealPortionPost) {
            $portion = new Portion();
            $portion->setAttributes($mealPortionPost);
            if ($portion->validate()) {
                $portion->save();
                $isNewPortion = true;
            } else {
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when adding new Portion.'));
                $this->redirect(yii::app()->createUrl('lunch/lunch/update', ['id' => $menuPost['id']]));
            }
        }

        $mealId = $mealPortionPost['meal_fk'];
        $portionId = $portion->id;
        $mealPortion = MealPortion::model()->findByAttributes(['meal_fk' => $mealId, 'portion_fk' => $portionId]);
        $isNewMealPortion = !isset($mealPortion);

        if ($isNewMealPortion) {
            $mealPortion = new MealPortion();
            $mealPortion->meal_fk = $mealId;
            $mealPortion->portion_fk = $portionId;
            $mealPortion->amount = $mealPortionPost['amount'];
        }

        if ($mealPortion->validate()) {
            $mealPortion->save();
            Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Portion added successfully!'));
        } else {
            if ($isNewPortion) {
                $portion->delete();
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when adding new Portion.'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when updating Portion.'));
            }
        }

        $this->redirect(yii::app()->createUrl('lunch/lunch/update', ['id' => $menuPost['id']]));
    }

    public function actionAddMeal()
    {
        /* @var $menuMeal MenuMeal */
        /* @var $meal Meal */

        $request = Yii::app()->getRequest();
        $menuMealPost = $request->getPost('MenuMeal', false);
        $mealPost = $request->getPost('Meal', false);
        if ($mealPost) {
            $meal = new Meal();
            $meal->restrictions = empty($mealPost['restrictions']) ? yii::t('lunchModule.lunch', 'None') : $mealPost['restrictions'];
            if ($meal->validate()) {
                $meal->save();

                $menuMeal = new MenuMeal();
                $menuMeal->meal_fk = $meal->id;
                $menuMeal->menu_fk = $menuMealPost['menu_fk'];

                if ($menuMeal->validate()) {
                    $menuMeal->save();
                    Log::model()->saveAction('lunch_meal', $menuMeal->id, 'C', $menuMeal->menu->name);
                    Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Meal added successfully!'));
                } else {
                    $meal->delete();
                    Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when adding new meal'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when adding new meal'));
            }
        }

        $this->redirect(yii::app()->createUrl('lunch/lunch/update', ['id' => $menuMealPost['menu_fk']]));
    }

    public function actionChangeMeal()
    {
        /* @var $menuMeal MenuMeal */
        /* @var $meal Meal */

        $request = Yii::app()->getRequest();
        $menuMealPost = $request->getPost('MenuMeal', false);
        $mealPost = $request->getPost('Meal', false);

        if ($mealPost && $menuMealPost) {
            $mealId = $menuMealPost['meal_fk'];
            $menuId = $menuMealPost['menu_fk'];
            $restrictions = $mealPost['restrictions'];

            $menuMeal = MenuMeal::model()->findByAttributes(['meal_fk' => $mealId, 'menu_fk' => $menuId]);
            $meal = Meal::model()->findByPk($menuMeal->meal_fk);
            $meal->restrictions = $restrictions;
            if ($meal->validate() && $menuMeal->validate()) {
                $meal->save();
                $menuMeal->save();
                Log::model()->saveAction('lunch_meal', $menuMeal->id, 'U', $menuMeal->menu->name);
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Meal updated successfully!'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when updating meal.'));
            }
        }
        $this->redirect(yii::app()->createUrl('lunch/lunch/update', ['id' => $menuMealPost['menu_fk']]));
    }
}
