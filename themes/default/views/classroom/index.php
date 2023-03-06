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
    $cs->registerCssFile(Yii::app()->request->baseUrl . '/sass/css/main.css');
    
    ?>

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Classrooms') ?></h1>  
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('classroom/create') ?>"> Adicionar turma</a>
                <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('reports/numberstudentsperclassroomreport') ?>" target="_blank">Relatório Alunos/Turma</a>
                <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('reports/instructorsperclassroomreport') ?>" target="_blank">Relatório Professores/Turma</a>
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
        <div class="columnone">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="widget clearmargin">  
                <div class="widget-body">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => false,
                        'enableSorting' => false,
                        'itemsCssClass' => 'js-tag-table tag-table table table-condensed
                        table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("classroom/update",array("id"=>$data->id)))',
                                'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
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
                                'template' => '{update}{delete}',
                                'buttons' => array(
                                    'update' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/editar.svg',
                                    ),
                                    'delete' => array(
                                        'imageUrl' => Yii::app()->theme->baseUrl.'/img/deletar.svg',
                                    )
                                ),
                                'updateButtonOptions' => array('style' => 'margin-right: 20px;'),
                                'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
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
