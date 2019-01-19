<?php

class InstructorController extends Controller
{
	public $layout = '//layouts/dashform';

	public function actionIndex()
	{
		$this->layout = 'dashform';
		$this->render('index');
	}

	public function actionIn()
	{
		$this->layout = 'dashform';
		$this->render('in');
	}
	
}