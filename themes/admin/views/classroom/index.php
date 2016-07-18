<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Classrooms'));

    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new Classroom'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new Classroom')),
    );
    ?>

    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Classrooms') ?></h3>

        </div>
    </div>

    <div class="innerLR">
        <div class="columnone" style="padding-right: 1em">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="widget">
                <div class="widget-body">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $filter->search(false),
                        'enablePagination' => true,
                        'filter' => $filter,
                        'selectionChanged' => 'function(id){ location.href = "' . $this->createUrl('update') . '/id/"+$.fn.yiiGridView.getSelection(id);}',
                        'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => '$data->name',
                                'htmlOptions' => array('width' => '300px')
                            ),
                            array(
                                'name' => 'edcensoStageVsModalityFk',
                                'header' => 'Etapa',
                                'value' => '$data->edcensoStageVsModalityFk->name',
                                'htmlOptions' => array('width' => '300px'),
                            ),
                            array(
                                'header' => 'Escola',
                                'value' => 'SchoolIdentification::model()->findByAttributes(array("inep_id"=>$data->school_inep_fk))->name',
                                'htmlOptions' => array('width' => '300px'),
                                'filter' => false
                            ),
                            array(
                                'header' => 'Horário',
                                'value' => '$data->initial_hour.":".$data->initial_minute." - ".$data->final_hour.":".$data->final_minute',
                                'htmlOptions' => array('width' => '200px'),
                                'filter' => false
                            ),

                        ),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="columntwo">
            <a href="<?php echo Yii::app()->createUrl("wizard/configuration/classroom"); ?>"><?php echo Yii::t('default', 'Classroom Configurarion') . ' ' . (Yii::app()->user->year - 1) ?></a>
        </div>
    </div>

</div>
