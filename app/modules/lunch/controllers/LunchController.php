<?php

/**
 * Class LunchController
 *
 * @property School $school
 */
class LunchController extends Controller {

    public $school;

    public function init(){
        $this->school = School::model()->findByPk(yii::app()->user->school);
    }

    public function actionIndex(){
        $this->render('index');
    }

    public function actionCreate(){
        $request = Yii::app()->getRequest();
        $menuPost = $request->getPost("Menu", false);

        $menu = new Menu();

        if($menuPost){
            $menu->attributes = $menuPost;
            $menu->school_fk = $this->school->inep_id;

            if($menu->validate()){
                $menu->save();
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.stock', 'Menu added successfully.'));
                $this->redirect(['lunch/index']);
            }else{
                $this->render('create', ["menu" => $menu]);
            }
        }else {
            $this->render('create', ["menu" => $menu]);
        }
    }

    public function actionUpdate($id){
        /* @var $menu Menu */
        $request = Yii::app()->getRequest();
        $menuPost = $request->getPost("Menu", false);

        $menu = Menu::model()->findByPk($id);

        if($menuPost){
            $menu->attributes = $menuPost;
            if($menu->validate()){
                $menu->save();
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.stock', 'Menu added successfully.'));
                $this->redirect(['lunch/index']);
            }else{
                $this->render('update', ["menu" => $menu]);
            }
        }else {
            $this->render('update', ["menu" => $menu]);
        }
    }

    public function actionRemovePortion(){
        /* @var $mealPortion MealPortion */

        $request = Yii::app()->getRequest();
        $menuPost = $request->getPost("Menu", false);
        $mealPortionPost = $request->getPost("MealPortion", false);
        $amountPost = $mealPortionPost['amount'] > 0 ? -1 *$mealPortionPost['amount'] : $mealPortionPost['amount'];

        $mealPortion = MealPortion::model()->findByPk($mealPortionPost['id']);
        if(isset($mealPortion)){
            $amount = $mealPortion->amount;
            if($amount + $amountPost > 0){
                $mealPortion->amount = $amount + $amountPost;
                if($mealPortion->validate()){
                    $mealPortion->save();
                    Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Portion decreased successfully!'));
                }else{
                    Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when removing portion.'));
                }
            }else{
                $mealPortion->delete();
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Portion removed successfully!'));
            }
        }else{
            Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error, portion not found.'));
        }
        $this->redirect(yii::app()->createUrl('lunch/lunch/update',["id"=>$menuPost['id']]));
    }

    public function actionAddPortion(){
        /* @var $mealPortion MealPortion */
        /* @var $portion Portion */

        $request = Yii::app()->getRequest();
        $menuPost = $request->getPost("Menu", false);
        $portionPost = $request->getPost("Portion", false);
        $mealPortionPost = $request->getPost("MealPortion", false);
        $isNewPortion = false;
        if($portionPost){
            $portion = new Portion();
            $portion->item_fk = $portionPost["item_fk"];
            $portion->measure = $portionPost["measure"];
            $portion->unity_fk = $portionPost["unity_fk"];
            $portion->amount = 1;
            if($portion->validate()){
                $portion->save();
                $isNewPortion = true;
            }else{
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when adding new Portion.'));
                $this->redirect(yii::app()->createUrl('lunch/lunch/update',["id"=>$menuPost['id']]));
            }
        }

        $mealId = $mealPortionPost["meal_fk"];
        $portionId = $isNewPortion ? $portion->id : $mealPortionPost["portion_fk"];
        $mealPortion = MealPortion::model()->findByAttributes(["meal_fk" => $mealId, "portion_fk"=>$portionId]);
        $isNewMealPortion = !isset($mealPortion);

        if($isNewMealPortion){
            $mealPortion = new MealPortion();
            $mealPortion->meal_fk = $mealId;
            $mealPortion->portion_fk = $portionId;
            $mealPortion->amount = $mealPortionPost["amount"];
        }else{
            $mealPortion->amount = $mealPortion->amount + $mealPortionPost["amount"];
        }

        if($mealPortion->validate()){
            $mealPortion->save();
            Yii::app()->user->setFlash('success', Yii::t('lunchModule.lunch', 'Portion added successfully!'));
        }else{
            if($isNewPortion){
                $portion->delete();
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when adding new Portion.'));
            }else{
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.lunch', 'Error when updating Portion.'));
            }
        }

        $this->redirect(yii::app()->createUrl('lunch/lunch/update',["id"=>$menuPost['id']]));
    }

}