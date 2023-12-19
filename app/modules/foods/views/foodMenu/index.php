<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */

$this->breadcrumbs = array(
	'Food Menus' => array('index'),
	'Manage',
);

$this->menu = array(
	array('label' => 'List FoodMenu', 'url' => array('index')),
	array('label' => 'Create FoodMenu', 'url' => array('create')),
);

?>
<div id="mainPage" class="main container-instructor">

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
							'description',
							'start_date',
							'final_date',
						),
					)
				); ?>

			</div>
		</div>
	</div>
</div>
