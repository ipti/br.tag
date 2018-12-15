<?php

class SchoolController extends Controller
{
	public $layout = '//layouts/dashform';

	public function actionIndex()
	{
		$this->layout = 'dashform';
		$this->render('index');
	}
	
}