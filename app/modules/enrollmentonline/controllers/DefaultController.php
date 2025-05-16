<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('status', "Ativo");

        $dataProvider = Classroom::model()->with('activeEnrollmentsCount')->search();

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }
}
