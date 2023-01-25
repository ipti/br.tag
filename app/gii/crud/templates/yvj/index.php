<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div id="mainPage" class="main">
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label',
);\n";
?>
$contextDesc = Yii::t('default', 'Available actions that may be taken on <?php echo $this->modelClass; ?>.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new <?php echo $this->modelClass; ?>'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new <?php echo $this->modelClass; ?>')),
); 

?>
<div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php echo "<?php"; ?> if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo "<?php"; ?> echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php echo "<?php"; ?> endif ?>
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo "<?php echo Yii::t('default', '$label')?>" ?></div></div>
                <div class="panelGroupBody">
                    <?php echo "<?php"; ?> $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => true,
                        'baseScriptUrl' => Yii::app()->theme->baseUrl . '/plugins/gridview/',
                        'columns' => array(
                    <?php
                    foreach ($this->tableSchema->columns as $column) {
                        echo "\t\t'" . $column->name . "',\n";
                    }
                    ?>
                     array('class' => 'CButtonColumn',),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>

</div>
