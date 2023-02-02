<div id="mainPage" class="main container-instructor">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Instructor Identifications'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on InstructorIdentification.');
    $this->menu = [
        ['label' => Yii::t('default', 'Create a new InstructorIdentification'), 'url' => ['create'], 'description' => Yii::t('default', 'This action create a new InstructorIdentification')],
    ];
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($themeUrl . '/css/template2.css');
    ?>

    <div class="row-fluid box-instructor">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Instructor Identifications') ?></h3>
            <div class="buttons span7 hide-responsive">
                <a href="<?php echo Yii::app()->createUrl('instructor/updateEmails')?>" class="tag-button medium-button">Atualizar e-mails</a>
                <a href="<?php echo Yii::app()->createUrl('instructor/create')?>" class="tag-button medium-button">Adicionar professor</a>
            </div>
        </div>
        <div class="btn-group pull-right mt-30 responsive-menu dropdown-margin">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                Menu
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
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
        <div class="widget">
            <div class="widget-body">
                <?php
                $this->widget('zii.widgets.grid.CGridView', [
                    'dataProvider' => $filter->search(),
                    'enablePagination' => true,
                    'filter' => $filter,
                    'itemsCssClass' => 'tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => [
                        [
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::link($data->name,Yii::app()->createUrl("instructor/update",array("id"=>$data->id)))',
                            'htmlOptions' => ['width' => '400px']
                        ],
                        [
                            'name' => 'documents',
                            'header' => 'CPF',
                            'value' => '$data->documents->cpf',
                            'htmlOptions' => ['width' => '400px']
                        ],
                        [
                            'name' => 'birthday_date',
                            'filter' => false
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
