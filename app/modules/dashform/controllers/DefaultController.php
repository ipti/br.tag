<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->layout = 'dashform';
		$this->render('index');
	}
	public function actionSchool()
	{
		$this->layout = 'dashform';
		$this->render('school');
	}
}