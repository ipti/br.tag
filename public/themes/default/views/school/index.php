<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'School Identifications'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new SchoolIdentification'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new SchoolIdentification')),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();


    ?>

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'School Identifications') ?></h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo Yii::app()->createUrl('school/create') ?>" class="t-button-primary  "> Adicionar escola</a>
            </div>
        </div>
    </div>

    <div class="tag-inner">
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
                        'enableSorting' => false,
                        'dataProvider' => $dataProvider,
                        'itemsCssClass' => 'js-tag-table tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enablePagination' => false,
                        'columns' => array(
                            array(
                                'name' => 'inep_id',
                                'htmlOptions' => array('width' => '150px')
                            ),
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,Yii::app()->createUrl("school/update",array("id"=>$data->inep_id)))',
                                'htmlOptions' => array('class' => 'link-update-grid-view'),
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
        <div class="columntwo">
        </div>

    </div>
</div>
