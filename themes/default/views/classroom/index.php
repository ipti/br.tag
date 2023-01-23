<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Classrooms'));

    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new Classroom'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new Classroom')),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($themeUrl . '/css/template2.css');
    $cs->registerCssFile($baseUrl . '/sass/css/main.css');
    ?>

    <div class="row-fluid hide-responsive">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Classrooms') ?></h3>  
            <div class="t-buttons-container buttons span9">
                <a href="<?php echo Yii::app()->createUrl('classroom/create') ?>" class="t-button"> Adicionar turma</a>
                <a href="<?php echo Yii::app()->createUrl('reports/numberstudentsperclassroomreport') ?>" class="t-button middle-button" target="_blank">Relatório Alunos/Turma</a>
                <a href="<?php echo Yii::app()->createUrl('reports/instructorsperclassroomreport') ?>" class="t-button" target="_blank">Relatório Professores/Turma</a>
            </div>
        </div>
    </div>
    
    <div class="tag-inner">
        <div class="btn-group pull-right responsive-menu">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Menu
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo Yii::app()->createUrl('classroom/create') ?>" class=""><i></i> Adicionar turma</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('reports/numberstudentsperclassroomreport') ?>" class=""><i></i>Relatório Alunos/Turma</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('reports/instructorsperclassroomreport') ?>" class=""><i></i>Relatório Professores/Turma</a></li>
            </ul>
        </div>
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
                        'dataProvider' => $filter->search(),
                        'enablePagination' => true,
                        'filter' => $filter,
                        'itemsCssClass' => 'tag-table table table-condensed table-striped table-hover table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("classroom/update",array("id"=>$data->id)))',
                                'htmlOptions' => array('width' => '400px')
                            ),
                            array(
                                'name' => 'edcensoStageVsModalityFk',
                                'header' => 'Etapa',
                                'value' => '$data->edcensoStageVsModalityFk->name',
                                'htmlOptions' => array('width' => '400px'),
                            ),
                            array(
                                'header' => 'Horário',
                                'value' => '$data->initial_hour.":".$data->initial_minute." - ".$data->final_hour.":".$data->final_minute',
                                'htmlOptions' => array('width' => '200px'),
                                'filter' => false
                            ),
                            array(
                                'class' => 'CButtonColumn', 
                                'template' => '{update}',
                                'buttons' => array(
                                    'update' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/edit.png',
                                    )
                                )
                            ),
                            array(
                            'class' => 'CButtonColumn', 
                            'template' => '{delete}',
                            'buttons' => array(
                                'delete' => array(
                                    'imageUrl' => Yii::app()->theme->baseUrl.'/img/cancelar.png',
                                )
                            )
                        ),
                        ),
                    ));
                    ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
            <a href="<?php echo Yii::app()->createUrl("wizard/configuration/classroom"); ?>"><?php echo Yii::t('default', 'Reaproveitamento de Turmas') . ' ' . (Yii::app()->user->year - 1) ?></a>
        </div>
    </div>

</div>
