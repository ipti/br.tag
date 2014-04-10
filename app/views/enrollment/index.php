<div id="mainPage" class="main">
    <?php
    //@done S1 - 14 - Lista de matricula, precisa de busca pelo nome do aluno.
    $this->setPageTitle('TAG - ' . Yii::t('default','Student Enrollments'));
    $this->breadcrumbs = array(
        Yii::t('default', 'Student Enrollments'),
    );
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentEnrollment.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new StudentEnrollment'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new StudentEnrollment')),
    );
    ?>
    
    <div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Student Enrollments') ?></h3>  
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
                    'dataProvider' => $model->search(),
                    'enablePagination' => true,
                    'filter'=>$model,
                    'itemsCssClass' => 'table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => array(
                            'enrollment_id', 
                        //@done S1 - 05 - Modificar o banco para ter a relação estrangeira dos alunos e turmas
                        //@done S1 - 05 - Criar Trigger ou solução similar para colocar o auto increment do aluno no student_fk da turma
                        //@done S1 - Modificar as colunas para a estutura abaixo:
                        //     array(
//                                'name' => 'name',
//                                'type' => 'raw',
//                                'value' => 'CHtml::link("Texto","?r=classroom/update&id=".$data->id)'
//                            ),
                        //@done S1 - Corrigir o controler do enrollment.
                        array(
                                'name' => 'student_fk',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->studentFk->name,"?r=enrollment/update&id=".$data->id)',
                                'htmlOptions' => array('width'=>'35%')
                            ),
                        array(
                                'name' => 'classroom_fk',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data->classroomFk->name,"?r=enrollment/update&id=".$data->id)',
                                'htmlOptions' => array('width'=>'35%')
                        ),
                        array( 'name'=>'school_year', 'value'=>'$data->classroomFk->school_year' ),
                        array('class' => 'CButtonColumn','template'=>' {delete}'),),
                    ));
                ?>
            </div>   
        </div>
    </div>
    <div class="columntwo">
    </div>
</div>
