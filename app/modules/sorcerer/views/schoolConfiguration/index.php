<?php

$this->breadcrumbs = array(
	Yii::t('app', 'School Configurarion') => array('index'),
	Yii::t('app', 'Create'),
);

$this->renderPartial('_form', array(
		'model' => $model,
		'buttons' => 'create',
		'title' => Yii::t('app', 'School Configurarion')
));
?>