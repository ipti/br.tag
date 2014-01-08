<div id="mainPage" class="main">
    <?php
    //@todo 14 - Lista de matricula, precisa de busca pelo nome do aluno.
    
    $this->breadcrumbs = array(
        'Student Enrollments',
    );
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentEnrollment.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new StudentEnrollment'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new StudentEnrollment')),
    );
    ?>

    <div class="heading-buttons">
        <h3><?php echo Yii::t('default', 'Student Enrollments') ?></h3>
        <div class="buttons pull-right">
            <a href="?r=enrollment/create" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Nova matrícula</a>
        </div>
        <div class="clearfix"></div>
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
                    'enablePagination' => true,
                    'itemsCssClass' => 'table table-bordered table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => array(
//                            array(
//                                'class' => 'CLinkColumn',
//                                'header'=>'Escola',
//                                'labelExpression'=>'SchoolIdentification::model()->findByPk($data->school_inep_id_fk)->name',
//                                'urlExpression'=>'"?r=school/update&id=".$data->school_inep_id_fk',
//                                ),
                            array(
                                'class' => 'CLinkColumn',
                                'header'=>'Aluno',
                                'labelExpression'=>'
                                ($data->student_inep_id === null) 
                                ? StudentIdentification::model()->findByAttributes(array("id" => $data->student_fk))["name"] 
                                : StudentIdentification::model()->findByAttributes(array("inep_id" => $data->student_inep_id))["name"]',
                                'urlExpression'=>'"?r=student/update&id=".(($data->student_fk === null) 
                                ? StudentIdentification::model()->findByAttributes(array("inep_id" => $data->student_inep_id))["id"]: $data->student_fk)',
                                ),
                            array(
                                'class' => 'CLinkColumn',
                                'header'=>'Turma',
                                'labelExpression'=>'
                                ($data->classroom_inep_id === null) 
                                ? Classroom::model()->findByAttributes(array("id" => $data->classroom_fk))["name"] 
                                : Classroom::model()->findByAttributes(array("inep_id" => $data->classroom_inep_id))["name"]',
                                'urlExpression'=>'"?r=classroom/update&id=".(($data->classroom_fk === null) 
                                ? Classroom::model()->findByAttributes(array("inep_id" => $data->classroom_inep_id))["id"]: $data->classroom_fk)',
                                ),
                        'enrollment_id', 
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
