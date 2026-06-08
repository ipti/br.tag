<?php

class ClassroomModule extends CWebModule
{
    public function init()
    {
        $this->setImport([
            'application.modules.classroom.ClassroomRoutes',
            'classroom.models.*',
            'classroom.components.*',
        ]);
        Yii::import('application.modules.sedsp.models.Classroom.*');
        Yii::import('application.modules.sedsp.models.Enrollment.*');
        Yii::import('application.modules.sedsp.models.Student.*');
        Yii::import('application.modules.sedsp.datasources.sed.Classroom.*');
        Yii::import('application.modules.sedsp.datasources.sed.ClassStudentsRelation.*');
        Yii::import('application.modules.sedsp.datasources.sed.Enrollment.*');
        Yii::import('application.modules.sedsp.datasources.sed.Student.*');
        Yii::import('application.modules.sedsp.mappers.*');
        Yii::import('application.modules.sedsp.usecases.*');
        Yii::import('application.modules.sedsp.usecases.Enrollment.*');
        Yii::import('application.modules.aeerecord.models.*');
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
        return parent::beforeControllerAction($controller, $action);
    }
}
