<?php

class NotificationsModule extends CWebModule
{
    public $defaultController = 'notifications';
    public $layout = 'webroot.themes.default.views.layouts.fullmenu';

    public function init()
    {
        $this->setImport([
            'notifications.models.*',
        ]);
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            $controller->layout = $this->layout;
            return true;
        }
        return false;
    }
}
