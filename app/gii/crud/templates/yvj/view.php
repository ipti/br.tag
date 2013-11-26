<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn},
);\n";
?>
$contextDesc = Yii::t('default', 'Available actions that may be taken on <?php echo $this->modelClass; ?>.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new <?php echo $this->modelClass; ?>'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new <?php echo $this->modelClass; ?>')),
array('label'=> Yii::t('default', 'List <?php echo $this->modelClass; ?>'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all <?php echo $this->pluralize($this->class2name($this->modelClass)) ?>, you can search, delete and update')),
); 
?>
<div id="mainPage" class="main">
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo"<?php echo Yii::t('default', 'View $this->modelClass # '.\$model->{$this->tableSchema->primaryKey}.' :')?>" ?></div></div>
                <div class="panelGroupBody">
                    <?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
                    <?php
                    foreach ($this->tableSchema->columns as $column)
                        echo "\t\t'" . $column->name . "',\n";
                    ?>
                    ),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
            <?php echo "<?php echo \$this->renderPartial('////common/defaultcontext', array('contextDesc'=>\$contextDesc)); ?>"; ?>
        </div>
    </div>
</div>