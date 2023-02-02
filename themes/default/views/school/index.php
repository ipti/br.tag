<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'School Identifications'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
    $this->menu = [
        ['label' => Yii::t('default', 'Create a new SchoolIdentification'), 'url' => ['create'], 'description' => Yii::t('default', 'This action create a new SchoolIdentification')],
    ];
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($themeUrl . '/css/template2.css');

    ?>

    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo Yii::t('default', 'School Identifications') ?></h3>  
            <div class="buttons  hide-responsive">
                <a href="<?php echo Yii::app()->createUrl('school/create') ?>" class="tag-button medium-button"> Adicionar escola</a>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <div class="span12 hide-box">
            <div class="">
                <a id="button-add-school" href="<?php echo Yii::app()->createUrl('school/create') ?>" class="pull-right btn btn-primary btn-icon glyphicons circle_plus"><i></i> Adicionar escola</a>
            </div>
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
                    $this->widget('zii.widgets.grid.CGridView', [
                        'dataProvider' => $filter->search(),
                        'filter' => $filter,
                        'itemsCssClass' => 'tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enablePagination' => true,
                        'columns' => [
                            [
                                'name' => 'inep_id',
                                'htmlOptions' => ['width' => '150px']
                            ],
                            [
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("school/update", array("id"=>$data->inep_id)))',
                            ], ],
                    ]);
                    ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>

    </div>
</div>
