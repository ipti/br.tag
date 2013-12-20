<div id="mainPage" class="main">
    <?php
    $this->breadcrumbs = array(
        'Classrooms',
    );
    $contextDesc = Yii::t('default', 'Available actions that may be taken on Classroom.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new Classroom'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new Classroom')),
    );
    ?>
    <div class="twoColumn">
        <div class="columnone" style="padding-right: 1em">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?php echo Yii::app()->user->getFlash('success') ?>
                </div>
                <br/>
            <?php endif ?>
            <div class="panelGroup form">
                <div class="panelGroupHeader"><div class=""><?php echo Yii::t('default', 'Classrooms') ?></div></div>
                <div class="panelGroupBody">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'dataProvider' => $dataProvider,
                        'enablePagination' => true,
                        'itemsCssClass' => 'table table-bordered table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                        'columns' => array(
                            'inep_id',
                            'name',
                            array(
                                'class' => 'CLinkColumn',
                                'header'=>'Escola',
                                'labelExpression'=>'SchoolIdentification::model()->findByPk($data->school_inep_fk)->name',
                                'urlExpression'=>'"?r=school/update&id=".$data->school_inep_fk',
                                ),
                            array('class' => 'CButtonColumn','template'=>'{update} {delete}'),),
                    ));
                    ?>
                </div>   
            </div>
        </div>
        <div class="columntwo">
        </div>
    </div>

</div>
