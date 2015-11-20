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


}