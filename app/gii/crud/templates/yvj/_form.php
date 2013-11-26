<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'" . $this->class2id($this->modelClass) . "-form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>
        <div class="panelGroup form">
            <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
            <div class="panelGroupHeader"><div class=""> <?php echo "<?php echo \$title; ?>\n"; ?></div></div>
            <div class="panelGroupBody">
                <div class="panelGroupAbout">
                     <?php echo "<?php echo Yii::t('default', 'Fields with * are required.')?>"; ?></div>

                <?php
                foreach ($this->tableSchema->columns as $column) {
                    if ($column->autoIncrement)
                        continue;
                    ?>
                    <div class="formField">
                        <?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>\n"; ?>
                        <?php echo "<?php echo " . $this->generateActiveField($this->modelClass, $column) . "; ?>\n"; ?>
                        <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
                    </div>

                    <?php
                }
                ?>
                <div class="formField buttonWizardBar">
                    <?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Save'),array('class' => 'buttonLink button')); ?>\n"; ?>
                </div>
                <?php echo "<?php \$this->endWidget(); ?>\n"; ?>
            </div>
        </div>
