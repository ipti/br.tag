<?php

class ToolsModule extends CWebModule
{
    public function init()
    {
        $this->setImport([
            'tools.models.*',
            'tools.components.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'webroot.themes.default.views.layouts.fullmenu';
        return parent::beforeControllerAction($controller, $action);
    }
}
