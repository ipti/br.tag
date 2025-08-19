<div id="mainPage" class="main">
    <?php
    //@done S1 - 14 - Lista de matricula, precisa de busca pelo nome do aluno.
    $this->setPageTitle('TAG - ' . Yii::t('default','Student Enrollments'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentEnrollment.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new StudentEnrollment'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new StudentEnrollment')),
    );
    ?>
    
    <div class="row-fluid">
    <div class="span12">
        <h1><?php echo Yii::t('default', 'Student Enrollments') ?></h1>  
        <div class="buttons">
                <a href="?r=enrollment/create" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Nova matrícula</a>
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
                
                $this->widget('zii.widgets.grid.CGridView', array(
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'itemsCssClass' => 'js-tag-table table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => array(
                        array(
                                'name' => 'enrollment_id',
                                'htmlOptions' => array('width'=> '150px')
                            ),
                        array(
                                'name' => 'student_fk',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->studentFk->name,Yii::app()->createUrl("enrollment/update",array("id"=>$data->id)))',
                                
                            ),
                        array(
                                'name' => 'classroom_fk',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->classroomFk->name,Yii::app()->createUrl("enrollment/update",array("id"=>$data->id)))',
                                'htmlOptions' => array('width'=>'150px')
                        ),
                        array('class' => 'CButtonColumn','template'=>' {delete}'),),
                    ));
                ?>
            </div>   
        </div>
    </div>
    <div class="columntwo">
    </div>
</div>
