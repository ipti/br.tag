<?php

class SchoolConfigurationController extends Controller {

	public function actionIndex() {
		$year = Yii::app()->user->school;
		$model = SchoolConfiguration::model()->findByAttributes(array("school_inep_id_fk"=>$year));

		if(!isset($model))
			$model = new SchoolConfiguration;

		if (isset($_POST['SchoolConfiguration'])) {
			$model->setAttributes($_POST['SchoolConfiguration']);

			if ($model->save()) {
				if (Yii::app()->getRequest()->getIsAjaxRequest())
					Yii::app()->end();
				else
					$this->redirect(array('index'));
			}
		}
		$this->render('index', array( 'model' => $model));
	}

}