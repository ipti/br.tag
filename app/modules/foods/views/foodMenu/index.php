<?php
/* @var $this FoodMenuController */
/* @var $dataProvider CActiveDataProvider */

// $this->breadcrumbs=array(
// 	'Food Menus',
// );

// $this->menu=array(
// 	array('label'=>'Create FoodMenu', 'url'=>array('create')),
// 	array('label'=>'Manage FoodMenu', 'url'=>array('admin')),
// );
?>

<!-- <h1>Food Menus</h1> -->

<?php
    // $this->widget('zii.widgets.CListView', array(
	// 'dataProvider'=>$dataProvider,
	// 'itemView'=>'_view',));
?>

<?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Food Menus'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on FoodMenu.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new FoodMenu'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new FoodMenu')),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();

?>

<div class="row-fluid">
    <div class="span12">
        <h1><?php echo Yii::t('default', 'Food Menus') ?></h1>
        <div class="t-buttons-container">
            <a class="t-button-primary"
                href="<?= Yii::app()->createUrl('foods/foodMenu/create') ?>"><?= Yii::t('default', 'Add') ?></a>
        </div>
    </div>
</div>

<div class="tag-inner">
    <?php if (Yii::app()->user->hasFlash('error')) : ?>
        <div class="alert alert-error">
            <?php echo Yii::app()->user->getFlash('error') ?>
        </div>
        <?php
        if (isset($buttons))
            echo $buttons;
        ?>
        <br/>
    <?php endif ?>
    <?php if (Yii::app()->user->hasFlash('success')) : ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <?php
        if (isset($buttons))
            echo $buttons;
        ?>
    <?php endif ?>
    <div class="widget clearmargin">
        <div class="widget-body">
            <div class="grid-view">
                <table id="food-menu-table" class="display js-tag-table food-menu-table
                    tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs"
                        style="width:100%" aria-label="foodMenu table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Data de Início</th>
                        <th>Data Final</th>
                        <th style="width: 104px;text-align: center;">Ações</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- <div class="widget-body"> -->
                <?php
                // $this->widget('zii.widgets.grid.CGridView', [
                //     'id' => 'foodmenugridview', 'dataProvider' => $dataProvider, 'ajaxUpdate' => false,
                //     'itemsCssClass' => 'js-tag-table foodMenu-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                //     'enablePagination' => false, 'columns' => [
                //         [
                //             'header' => Yii::t('foodMenu.index', 'ID'),
                //             'name' => 'id',
                //             'value' => '$data->stageFk->name',
                //         ], [
                //             'header' => Yii::t('foodMenuModule.index', 'Description'),
                //             'name' => 'description',
                //             'value' => '$data->disciplineFk->name',
                //         ], [
                //             'header' => Yii::t('foodMenuModule.index', 'Start Date'),
                //             'name' => 'start_date',
                //             'htmlOptions' => ['width' => '150px']
                //         ], [
                //             'header' => Yii::t('foodMenuModule.index', 'Final Date'),
                //             'name' => 'final_date',
                //             'htmlOptions' => ['width' => '150px']
                //         ],
                        // [
                        //     'header' => 'Ações',
                        //     'class' => 'CButtonColumn',
                        //     'template' => Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ? '{delete}' : '',
                        //     'buttons' => array(
                        //         'delete' => array(
                        //             'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                        //         ),
                        //     ),
                        //     'deleteButtonOptions' => array('style' => 'cursor: pointer;'),
                        // ],

                //     ],
                // ]);
                ?>
    <!-- </div> -->
</div>
