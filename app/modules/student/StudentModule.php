<?php

/**
 * Student module.
 *
 * Manages student identification, enrollment, documents,
 * transfers, and related operations.
 */
class StudentModule extends CWebModule
{
    /** @var string Default controller for this module */
    public $defaultController = 'student';

    /** @var string Layout used by all controllers in this module */
    public $layout = 'webroot.themes.default.views.layouts.fullmenu';
    public $baseScriptUrl;
    public $baseUrl;

    /**
     * Initializes the module.
     *
     * @return void
     */
    public function init()
    {
        $this->baseUrl = Yii::app()->createUrl('student');
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(
            Yii::getPathOfAlias('application.modules.student.resources')
        );

        $this->setImport([
            'application.modules.student.models.*',
            'application.modules.grades.usecases.*',
        ]);
    }

    /**
     * Sets the layout on the controller before action execution.
     *
     * @param CController $controller The active controller
     * @param CAction     $action     The active action
     *
     * @return bool
     */
    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            $controller->layout = $this->layout;
            return true;
        }
        return false;
    }
}
