<?php
/* @var $this FoodNoticeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Food Notices',
);

$this->menu=array(
	array('label'=>'Create FoodNotice', 'url'=>array('create')),
	array('label'=>'Manage FoodNotice', 'url'=>array('admin')),
);
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

				<?php $this->widget(
					'zii.widgets.grid.CGridView',
					array(
						'id' => 'food-menu-grid',
						'dataProvider' => $dataProvider,
						'enablePagination' => false,
						'enableSorting' => false,
						'ajaxUpdate' => false,
						'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
						'columns' => array(
							'id',
							'name' => array(
								'header' => 'Nome',
								'type' => 'raw',
								'value' => 'CHtml::link($data->name,Yii::app()->createUrl("foods/foodnotice/update",array("id"=>$data->id)))',
								'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
							),
							'date' => array(
								'header' => 'Início',
								'type' => 'raw',
								'value' => 'DateTime::createFromFormat("Y-m-d",$data->date)->format("d/m/Y")',
								'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
							),
							array(
								'header' => 'Ações',
								'class' => 'CButtonColumn',
								'template' => '{update}{delete}',
								'buttons' => array(
									'update' => array(
										'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
									),
									'delete' => array(
										'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
									)
								),
								'updateButtonOptions' => array('style' => 'margin-right: 20px;'),
								'deleteButtonOptions' => array('style' => 'cursor: pointer;'),
								'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
							)
						),
					)
				); ?>

			</div>
		</div>
	</div>

</div>
