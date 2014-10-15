<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
/* @var $this GiixCrudCode*/
?>

<?php $ajax = ($this->enable_ajax_validation) ? 'true' : 'false'; ?>

<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form GxActiveForm */
?>

<?php echo '<?php '; ?>
$form = $this->beginWidget('GxActiveForm', array(
	'id' => '<?php echo $this->class2id($this->modelClass); ?>-form',
	'enableAjaxValidation' => <?php echo $ajax; ?>,
));
<?php echo '?>'; ?>


<div class="row-fluid">
	<div class="span12">
		<h3 class="heading-mosaic">
			<?php echo "<?php echo \$title; ?><span> | <?php echo Yii::t('default', 'Fields with * are required.') ?></span>";?>
		</h3>
		<div class="buttons">
			<?php echo "<?php echo CHtml::htmlButton('<i></i>' .Yii::t('default', 'Save'), array('class' => 'btn btn-icon btn-primary last glyphicons circle_ok', 'type' => 'submit')); ?>" ?>
		</div>
	</div>
</div>

<div class="innerLR">
	<div class="widget widget-tabs border-bottom-none">
		<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

        <div class="widget-head">
				<ul class="tab">
					<li id="tab" class="active">
						<a class="glyphicons user" href="#tab1" data-toggle="tab"> 
							<?php echo "<i></i> <?php echo \$title; ?>" ?>
						</a>
					</li>
				</ul>
		</div>
	
		<div class="widget-body form-horizontal">
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<div class="row-fluid">
						<div class=" span5">
							<?php foreach ($this->tableSchema->columns as $column): ?>
							<?php if (!$column->autoIncrement): ?>
								<div class="control-group">
									<?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column, "array('class' => 'control-label')") . "; ?>\n"; ?>
									<div class="controls">
										<?php echo "<?php " . $this->generateActiveField($this->modelClass, $column) . "; ?>\n"; ?>
									</div>
									<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
								</div>
								<!-- row -->
							<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<div class=" span6">
							<?php foreach ($this->getRelations($this->modelClass) as $relation): ?>
							<?php if ($relation[1] == GxActiveRecord::HAS_MANY || $relation[1] == GxActiveRecord::MANY_MANY): ?>
							
								<div class="control-group">
									<label><?php echo '<?php'; ?> echo GxHtml::encode($model->getRelationLabel('<?php echo $relation[0]; ?>')); ?></label>
									<div class="controls">
										<?php echo '<?php ' . $this->generateActiveRelationField($this->modelClass, $relation) . "; ?>\n"; ?>
									</div>
								</div>
							<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>			
			<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
		</div>
	</div>
</div>
