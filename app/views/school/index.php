<div id="mainPage" class="main">
    <?php
    $this->breadcrumbs = array(
        Yii::t('default', 'School Identifications'),
    );
    $contextDesc = Yii::t('default', 'Available actions that may be taken on SchoolIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new SchoolIdentification'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new SchoolIdentification')),
    );
    ?>

    <div class="heading-buttons">
        <h3><?php echo Yii::t('default', 'School Identifications') ?></h3>
        <div class="buttons pull-right">
            <a href="?r=school/create" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Adicionar escola</a>
        </div>
        <div class="clearfix"></div>
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
                        'dataProvider' => $filter->search(),
                        'filter' => $filter,
                        'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'enablePagination' => true,
                        'columns' => array(
                            'inep_id',
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->name,"?r=school/update&id=".$data->inep_id)'
                            ),
                            array('class' => 'CButtonColumn', 'template' => '{update} {delete}',),),
                    ));
                    ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>

    </div>
</div>
