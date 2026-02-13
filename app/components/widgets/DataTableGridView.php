<?php

// Import CGridView from Yii Zii library
Yii::import('zii.widgets.grid.CGridView');

/**
 * DataTableGridView
 * 
 * Wrapper component for CGridView that automatically integrates DataTables.
 * Uses the existing init.js for DataTables initialization to maintain consistency.
 * 
 * Usage:
 * ```php
 * $this->widget('application.components.widgets.DataTableGridView', [
 *     'dataProvider' => $dataProvider,
 *     'filter' => $model,  // Optional: enables CGridView filters
 *     'columns' => [...],
 * ]);
 * ```
 */
class DataTableGridView extends CGridView
{
    /**
     * @var bool Whether to enable DataTables (default: true)
     */
    public $enableDataTable = true;

    /**
     * @var array Additional HTML options for the table
     */
    public $htmlOptions = array();

    /**
     * Initialize the widget
     */
    public function init()
    {
        // Set default CSS classes for DataTables
        if ($this->enableDataTable) {
            // Use js-tag-table class to trigger init.js initialization
            $defaultClasses = 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs';
            
            if (!isset($this->itemsCssClass)) {
                $this->itemsCssClass = $defaultClasses;
            } else {
                // Replace default 'items' class with our classes
                if ($this->itemsCssClass === 'items') {
                    $this->itemsCssClass = $defaultClasses;
                } else if (strpos($this->itemsCssClass, 'js-tag-table') === false) {
                    // Append our classes if js-tag-table is not present
                    $this->itemsCssClass = trim($this->itemsCssClass . ' ' . $defaultClasses);
                }
            }

            // Set afterAjaxUpdate to re-initialize DataTables after AJAX updates
            if (!isset($this->afterAjaxUpdate)) {
                $this->afterAjaxUpdate = 'js:function(id, data){ if(typeof initDatatable === "function") initDatatable(); }';
            }
        }

        if (!isset($this->enableSorting)) {
            $this->enableSorting = true;
        }

        parent::init();
    }

    /**
     * Static helper method to render the widget cleanly.
     * Usage: DataTableGridView::show($this, ['dataProvider' => $model->search(), ...]);
     * 
     * @param CController $controller The controller instance (usually $this in views)
     * @param array $options The widget configuration options
     * @param bool $captureOutput Whether to return the output instead of echoing it
     * @return mixed string|void
     */
    public static function show($controller, $options = array(), $captureOutput = false)
    {
        return $controller->widget('application.components.widgets.DataTableGridView', $options, $captureOutput);
    }

    /**
     * Run the widget
     * No need to register custom scripts - init.js handles everything
     */
    public function run()
    {
        parent::run();
        // init.js will automatically initialize DataTables for tables with js-tag-table class
    }
}
