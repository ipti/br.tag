<?php
/** @var $this FoodNoticeController */
/** @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Food Notices',
];

$this->menu = [
    ['label' => 'Create FoodNotice', 'url' => ['create']],
    ['label' => 'Manage FoodNotice', 'url' => ['admin']],
];
?>

<div id="mainPage" class="main container-instructor">
<div class="row">
    <h1 class="column clearleft">Editais</h1>
</div>
<div class="row-fluid">
        <div class="t-buttons-container">
            <a class="t-button-primary"  rel="noopener" href="<?= Yii::app()->createUrl('foods/foodnotice/create') ?>">
                <?= Yii::t('default', 'Add') ?>
            </a>
            <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('foods/foodnotice/activateNotice')?>">Exibir Inativos</a>
        </div>
</div>

<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?php echo Yii::app()->user->getFlash('success') ?>
    </div>
    <br/>
<?php endif ?>
<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="alert alert-error">
        <?php echo Yii::app()->user->getFlash('error') ?>
    </div>
    <br/>
<?php endif ?>

<div class="tag-inner">
		<div class="widget clearmargin">
			<div class="widget-body">

				<?php $this->widget(
    'zii.widgets.grid.CGridView',
    [
        'id' => 'food-notice-grid',
        'dataProvider' => $dataProvider,
        'enablePagination' => false,
        'enableSorting' => false,
        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
        'columns' => [
            'id',
            'name' => [
                'header' => 'Nome',
                'type' => 'raw',
                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("foods/foodnotice/update",array("id"=>$data->id)))',
                'htmlOptions' => ['width' => '400px', 'class' => 'link-update-grid-view'],
            ],
            'date' => [
                'header' => 'Início',
                'type' => 'raw',
                'value' => 'DateTime::createFromFormat("Y-m-d",$data->date)->format("d/m/Y")',
                'htmlOptions' => ['width' => '400px', 'class' => 'link-update-grid-view'],
            ],
            [
                'header' => 'Ações',
                'class' => 'CButtonColumn',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => [
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                    ],
                    'delete' => [
                        'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                    ]
                ],
                'afterDelete' => 'window.location.href = "?r=foods/foodnotice/index";',
                'updateButtonOptions' => ['style' => 'margin-right: 20px;'],
                'deleteButtonOptions' => ['style' => 'cursor: pointer;'],
                'htmlOptions' => ['width' => '100px', 'style' => 'text-align: center'],
            ]
        ],
        'afterAjaxUpdate' => 'function(id, data) {
                            initDatatable()
                        }',
    ]
); ?>

			</div>
		</div>
	</div>

</div>
