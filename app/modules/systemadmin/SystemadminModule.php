<?php

class SystemadminModule extends CWebModule
{
    public function init()
    {
        $this->setImport([
            'application.modules.systemadmin.SystemadminRoutes',
            'systemadmin.models.*',
            'systemadmin.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';

        return parent::beforeControllerAction($controller, $action);
    }
}
