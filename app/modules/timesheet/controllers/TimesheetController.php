<?php

	class TimesheetController extends Controller {
		/**
		 * @return array action filters
		 */
		public function filters() {
			return [
				'accessControl', // perform access control for CRUD operations
				'postOnly + delete', // we only allow deletion via POST request
			];
		}

		/**
		 * Specifies the access control rules.
		 * This method is used by the 'accessControl' filter.
		 * @return array access control rules
		 */
		public function accessRules() {
			return [
				[
					'allow',  // allow all users to perform 'index' and 'view' actions
					'actions' => [], 'users' => ['*'],
				], [
					'allow', // allow authenticated user to perform 'create' and 'update' actions
					'actions' => ['index', 'SetActual'], 'users' => ['@'],
				], [
					'allow', // allow admin user to perform 'admin' and 'delete' actions
					'actions' => [], 'users' => ['admin'],
				], [
					'deny',  // deny all users
					'users' => ['*'],
				],
			];
		}


		public function actionIndex() {
			$this->render('index');
		}
	}