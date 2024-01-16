<?php

class ReportsController extends Controller
{

	
	public function actionFoodMenuReport($id) {
        $foodMenu = FoodMenu::model()->findByPk($id);
		$this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render('FoodMenuReport', array("foodMenu" => $foodMenu));
    }
}