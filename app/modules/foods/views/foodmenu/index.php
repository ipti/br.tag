<?php
/** @var $this FoodMenuController */
/** @var $model FoodMenu */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Cardápios'));

$this->breadcrumbs = [
    'Food Menus' => ['index'],
    'Manage',
];

$this->menu = [
    ['label' => 'List FoodMenu', 'url' => ['index']],
    ['label' => 'Create FoodMenu', 'url' => ['create']],
];

?>
<div id="mainPage" class="main container-instructor">

	<div class="row-fluid">
		<div class="span12">
			<h1>
				Habilidades
			</h1>
			<div class="t-buttons-container">
                <?php
                    if (Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)):
                        ?>
                    <a class="t-button-primary"  rel="noopener" href="<?= Yii::app()->createUrl('foods/foodMenu/create') ?>">
                        <?= Yii::t('default', 'Add') ?>
                    </a>
                <?php
                    endif;
?>
				<a class="t-button-secondary" target="_blank"  rel="noopener"
					href="<?php echo Yii::app()->createUrl('foods/reports/ShoppingListReport') ?>">
					<span class="t-icon-printer"></span>Lista de Compras
				</a>
			</div>
		</div>
	</div>


	<div class="tag-inner">
		<?php if (Yii::app()->user->hasFlash('success')): ?>
			<div class="alert alert-success">
				<?php echo Yii::app()->user->getFlash('success') ?>
			</div>
			<br />
		<?php endif ?>
		<div class="widget clearmargin">
			<div class="widget-body">

				<?php

$this->widget(
    'zii.widgets.grid.CGridView',
    [
        'id' => 'food-menu-grid',
        'dataProvider' => $dataProvider,
        'enablePagination' => false,
        'enableSorting' => false,
        'ajaxUpdate' => false,
        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
        'columns' => [
            'id',
            'description' => [
                'header' => 'Nome',
                'type' => 'raw',
                'value' => function ($data) {
                    if (Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
                        return CHtml::link($data->description, Yii::app()->createUrl('foods/foodmenu/update', ['id' => $data->id]));
                    } else {
                        return CHtml::link($data->description, Yii::app()->createUrl('foods/reports/FoodMenuReport', ['id' => $data->id]));
                    }
                },
                'htmlOptions' => ['width' => '400px', 'class' => 'link-update-grid-view'],
            ],
            'start_date' => [
                'header' => 'Início',
                'type' => 'raw',
                'value' => 'DateTime::createFromFormat("Y-m-d",$data->start_date)->format("d/m/Y")',
                'htmlOptions' => ['width' => '400px', 'class' => 'link-update-grid-view'],
            ],
            'final_date' => [
                'header' => 'Fim',
                'type' => 'raw',
                'value' => 'DateTime::createFromFormat("Y-m-d",$data->final_date)->format("d/m/Y")',
                'htmlOptions' => ['width' => '400px', 'class' => 'link-update-grid-view'],
            ],
            [
                'header' => 'Ações',
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}{report}',
                'buttons' => [
                    'update' => [
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                        'visible' => "Yii::app()->getAuthManager()->checkAccess('nutritionist',Yii::app()->user->loginInfos->id) ||  Yii::app()->getAuthManager()->checkAccess('admin',Yii::app()->user->loginInfos->id)",
                    ],
                    'delete' => [
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                        'visible' => "Yii::app()->getAuthManager()->checkAccess('nutritionist',Yii::app()->user->loginInfos->id) ||  Yii::app()->getAuthManager()->checkAccess('admin',Yii::app()->user->loginInfos->id)",
                    ],
                    'report' => [
                        'label' => '<span class="t-icon-weather-report"><span>',  // Adiciona um rótulo para o botão
                        'url' => 'Yii::app()->createUrl("foods/reports/FoodMenuReport", array("id" => $data->id))',
                        'visible' => '(!Yii::app()->getAuthManager()->checkAccess("nutritionist", Yii::app()->user->loginInfos->id) && !Yii::app()->getAuthManager()->checkAccess("admin", Yii::app()->user->loginInfos->id))',
                    ]
                ],
                'updateButtonOptions' => ['style' => 'margin-right: 20px;'],
                'deleteButtonOptions' => ['style' => 'cursor: pointer;'],
                'htmlOptions' => ['width' => '100px', 'style' => 'text-align: center'],
            ]
        ],
    ]
); ?>

			</div>
		</div>
	</div>
</div>
