<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div id="mainPage" class="main">
    <?php
    echo "<?php\n";
    $nameColumn = $this->guessNameColumn($this->tableSchema->columns);
    $label = $this->pluralize($this->class2name($this->modelClass));
    echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn}=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Update',
);\n";
    ?>

    $title=Yii::t('default', 'Update <?php echo $this->modelClass ?>: ');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on <?php echo $this->modelClass; ?>.');
    $this->menu=array(
    array('label'=> Yii::t('default', 'Create a new <?php echo $this->modelClass; ?>'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new <?php echo $this->modelClass; ?>')),
    array('label'=> Yii::t('default', 'List <?php echo $this->modelClass; ?>'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all <?php echo $this->pluralize($this->class2name($this->modelClass)) ?>, you can search, delete and update')),
    );  
    ?>

    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model,'title'=>\$title)); ?>"; ?>
        </div>
        <div class="columntwo">
            <?php echo "<?php echo \$this->renderPartial('////common/defaultcontext', array('contextDesc'=>\$contextDesc)); ?>"; ?>
        </div>
    </div>
</div>