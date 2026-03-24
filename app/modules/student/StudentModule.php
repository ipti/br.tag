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

    /**
     * Initializes the module.
     *
     * @return void
     */
    public function init()
    {
        // Models are in app/models/ (shared across modules) — no local import needed.
        $this->setImport([]);
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
