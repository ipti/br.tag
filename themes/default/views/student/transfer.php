<div id="mainPage" class="main">
<?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Transfer Student'));

    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/student/form/functions.js', CClientScript::POS_END);

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'transfer-student-form',
        'enableAjaxValidation' => false,
    ));
?>

<h1><?php echo Yii::t('default', 'Transfer Student'); ?></h1>

<div class="form-content">
<div>
    <div class="row">
        <h3>Matrícula Atual</h3>
    </div>
    
    <div class="row">
        <div class="column">
             
            <table class="tag-table table-bordered table-striped" aria-describedby="tabela de matriculas">
                <thead>
                    <tr>
                        <th style="text-align: center">Aluno</th>
                        <th style="text-align: center;">Escola</th>
                        <th style="text-align: center">Turma</th>
                        <th style="text-align: center">Ano</th>                     
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $modelStudentIdentification->name ?></td>
                        <td><?php echo $modelStudentIdentification->studentEnrollment->schoolInepIdFk->name ?></td>
                        <td><?php echo $modelStudentIdentification->studentEnrollment->classroomFk->name?></td>
                        <td><?php echo $modelStudentIdentification->studentEnrollment->classroomFk->school_year?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
    <div class="row">
         <h3>Nova Matrícula</h3>
    </div>
    <div class="row">
        <div class="column">
              
             <div class="t-field-select">
                <?php echo $form->labelEx($modelEnrollment, 'school-identifications', array('class' => 't-field-select__label')); ?>
                <?php echo $form->dropDownList($modelEnrollment, 'school_inep_id_fk', Chtml::listData(Yii::app()->user->usersSchools, 'inep_id', 'name'), array('empty' => 'Selecione a escola', 'class' => 'select-search-on t-field-select__input js-select-school-classroom', 'style' => 'width:100%')); ?>
             </div>
        </div>
        <div class="column">
            <div class="t-field-select">
                    <?php echo $form->labelEx($modelEnrollment, 'classroom_fk', array('class' => 't-field-select__label')); ?>
                    <?php echo $form->dropDownList($modelEnrollment, 'classroom_fk', CHtml::listData($classrooms, 'id', 'name', 'schoolInepFk.name'), array('empty' => 'Selecione a escola', 'class' => 'select-search-on t-field-select__input js-classrooms-select', 'disabled'=>'disabled', 'style' => 'width:100%')); ?> 
            </div>
        </div>
    </div>
    <div class="">
        <?php echo CHtml::htmlButton((Yii::t('default', 'Save')), array('type' => 'submit', 'class' => 'btn btn-icon btn-primary')); ?>
    </div>
</div>

    <?php $this->endWidget(); ?>
</div>

</div>
<style>
    h3 {
        margin: 0 0 15px;
    }
</style>