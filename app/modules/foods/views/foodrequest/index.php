<?php
/* @var $this FoodRequestController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Food Requests',
);

$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('app\modules\foods\resources\request\_initialization.js', CClientScript::POS_END);
$cs->registerScriptFile('app\modules\foods\resources\request\functions.js', CClientScript::POS_END);

$this->menu=array(
	array('label'=>'Create FoodRequest', 'url'=>array('create')),
	array('label'=>'Manage FoodRequest', 'url'=>array('admin')),
);
?>

<div id="mainPage" class="main">
	<div class="mobile-row">
		<div class="column clearleft">
			<h1 class="t-padding-none--bottom"><?php echo $model->isNewRecord ? 'Solicitações' : '' ?></h1>
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

	<div class="row  t-buttons-container">
		<a class="t-button-primary" id="js-entry-request-button" href="<?php echo yii::app()->createUrl('foods/foodRequest/create') ?>" type="button">Gerar Solicitação</a>
	</div>

	<div class="row">
		<div class="t-field-select column is-two-fifths clearfix">
			<select class="select-search-on t-field-select__input select2-container" id="foodRequestSelect">
				<option value="total">Filtrar solicitações por alimento</option>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="column is-four-fifths clearfix">
			<table id="foodRequestTable" class="tag-table-secondary align-start">

			</table>
		</div>
	</div>
</div>

<!-- <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?> -->
