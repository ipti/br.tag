<?php
/* @var $this FoodMenuController */
/* @var $model FoodMenu */

$this->breadcrumbs=array(
	'Food Menus'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FoodMenu', 'url'=>array('index')),
	array('label'=>'Create FoodMenu', 'url'=>array('create')),
	array('label'=>'View FoodMenu', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FoodMenu', 'url'=>array('admin')),
);
?>

<h1>Update FoodMenu <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<script type="text/javascript">
	/* var menuUpdate =  <?php echo json_encode($modelMenu); ?>
	const name = $('.js-menu-name')
    const publicTarget = $('.js-public-target')
    const startDate = $('.js-start-date')
    const finalDate = $('.js-final-date')
    const observation = $('.js-observation')
	
	menuUpdate.meals.map((meal) => MealsComponent(meal).render());


    name.val(menuUpdate.name)
    // publicTarget.select2('val',2)
    startDate.val(menuUpdate.start_date)
    finalDate.val(menuUpdate.final_date) */
</script>