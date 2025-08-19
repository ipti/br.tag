<div id="mainPage" class="main container-instructor">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Instructor Identifications'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new InstructorIdentification'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new InstructorIdentification')),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();

    ?>


    <div class="row-fluid box-instructor">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Instructor Identifications') ?></h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('instructor/create')?>">Adicionar professor</a>
                <div class="mobile-row">
                    <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('instructor/frequency')?>">Frequência</a>
                    <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('instructor/updateEmails')?>">Atualizar e-mails</a>
                </div>
            </div>
        </div>
        <div class="btn-group pull-right mt-30 responsive-menu dropdown-margin">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Menu
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo Yii::app()->createUrl('instructor/frequency')?>" class="t-button-primary">Frequência</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('instructor/updateEmails')?>"><i></i> Atualizar e-mails</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('instructor/create')?>"><i></i> Adicionar professor</a></li>
            </ul>
        </div>
    </div>

    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
            <br/>
        <?php endif ?>

        <?php if (Yii::app()->user->hasFlash('notice')):?>
            <div class="alert alert-info">
                <?php echo Yii::app()->user->getFlash('notice') ?>
            </div>
        <?php endif ?>
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => array(
                        array(
                            'name' => 'id',
                            'type' => 'raw',
                            'value' => '$data->id',
                        ),
                        array(
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->name,Yii::app()->createUrl("instructor/update",array("id"=>$data->id)))',
                            'htmlOptions' => array('width' => '400px', 'class' => 'link-update-grid-view'),
                        ),
                        array(
                            'name' => 'documents',
                            'header' => 'CPF',
                            'value' => '$data->documents->cpf',
                            'htmlOptions' => array('width'=> '400px')
                        ),
                        array(
                            'name' => 'birthday_date',
                            'filter' => false
                        ),
                        array(
                            'header' => 'Ações',
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
                            'deleteButtonOptions' => array('style' => 'cursor: pointer;'),
                            'htmlOptions' => array('width' => '100px', 'style' => 'text-align: center'),
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>
