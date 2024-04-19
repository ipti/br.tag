<?php
class CourseplanModule extends CWebModule
{
    public $defaultController = "courseplan";
    public $baseScriptUrl;
    public $baseUrl;


    public function init(){

        $this->baseUrl = Yii::app()->createUrl("courseplan");
        $assetManager = Yii::app()->getAssetManager();
        $aliasPath = Yii::getPathOfAlias('application.modules.courseplan.resources');
        $this->baseScriptUrl = $assetManager->publish($aliasPath);


        $this->setImport(array(
            'courseplan.models.*',
            'courseplan.components.*',
        ));
    }



    public function beforeControllerAction($controller, $action){

        $controller->layout='webroot.themes.default.views.layouts.fullmenu';

        if(parent::beforeControllerAction($controller, $action)){return true;}
        return false;
    }
}
