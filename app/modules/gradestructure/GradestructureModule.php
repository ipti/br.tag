<?php

class GradestructureModule extends CWebModule
{
    public function init()
    {
        $this->setImport([
            'gradestructure.models.*',
            'gradestructure.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
        return parent::beforeControllerAction($controller, $action);
    }
}
