<div id="mainPage" class="main">
<?php
$this->setPageTitle('TAG - ' . Yii::t('default','Student Identifications'));
$this->breadcrumbs=array(
	Yii::t('default', 'Student Identifications'),
);
$contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
$this->menu=array(
array('label'=> Yii::t('default', 'Create a new StudentIdentification'), 'url'=>array('create'),'description' => Yii::t('default', 'This action create a new StudentIdentification')),
); 

?>
    
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Student Identifications')?></h3>  
        <div class="buttons">
                <a href="?r=student/create" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Adicionar aluno</a>
        </div>
    </div>
</div>
    
<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
        <br/>
        <?php endif ?>
        <div class="widget">
                <div class="widget-body">
                    <?php 
                    
                    //<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i>Ok</i></button>
                    //@done S1 - 05 - Tirar borda esquerda e direita do filtro por nome dos alunos
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $filter->search(),
                        'enablePagination' => true,
                        'filter'=>$filter,
                        'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'name' => 'inep_id',
                                    'htmlOptions' => array('width'=> '150px')
                            ),
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,"?r=student/update&id=".$data->id)'
                            ),),
                    )); ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>

</div>
