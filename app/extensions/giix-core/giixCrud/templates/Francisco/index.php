<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>

<div id="mainPage" class="main">
	<?php
	$this->setPageTitle('TAG - ' . Yii::t('default','Student Identifications'));
	echo "<?php\n
	\$this->breadcrumbs = array(
		{$this->modelClass}::label(2),
		Yii::t('app', 'Index'),
	);\n";
	?>
	
	<div class="row-fluid">
	    <div class="span12">
	        <h3 class="heading-mosaic"><?php echo "<?php echo Yii::t('default', '" . $this->modelClass . "')?>"?></h3>  
	        <div class="buttons">
	        	<?php echo "<?php echo CHtml::htmlButton(\"<i></i><?php echo Yii::t('app', 'Create') ?>\", array('url'=>array('create'))); ?>" ?>
	        </div>
	    </div>
	</div>
	
	
	
	<div class="innerLR">
	    <?php echo "<?php if (Yii::app()->user->hasFlash('success')): ?>"?>
	        <div class="alert alert-success">
	            <?php echo "<?php echo Yii::app()->user->getFlash('success') ?>"?>
	        </div>
	        <br/>
	        <?php echo "<?php endif ?>"?>
	        <div class="widget">
	        	<div class="widget-body">
	            	<?php echo "<?php \$this->widget('zii.widgets.grid.CGridView', array(
		                'dataProvider' => \$filter->search(),
		                'enablePagination' => true,
	                    'filter'=>\$filter,
	                    'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
	            	)); ?>"?>
	        	</div>   
	        </div>
	</div>
	<div class="columntwo">
	</div>
 </div>

</div>