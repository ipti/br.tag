<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classroom-form',
    'enableAjaxValidation' => false,
        ));
?>


<div class="innerLR">
    <?php if (Yii::app()->user->hasFlash('success') && (!$modelClassroom->isNewRecord)): ?>
        <div class="alert alert-success">
            <?php echo Yii::app()->user->getFlash('success') ?>
        </div>
    <?php endif ?>
    <div class="widget widget-tabs border-bottom-none">

        <?php echo $form->errorSummary($modelClassroom); ?>


        <div class="widget-body form-horizontal">

            <div>
                <div id="students">
                    <div class="row-fluid">
                        <div id="widget-StudentsList" class="widget" style="margin-top: 8px;">
                            <?php
                            if (!$modelClassroom->isNewRecord) {
                            $classroom = $modelClassroom->id;
                            $criteria = new CDbCriteria();
                            $criteria->alias = 'e';
                            $criteria->select = '*';
                            $criteria->join = 'JOIN student_identification s ON s.id = e.student_fk';
                            $criteria->condition = "classroom_fk = $classroom";
                            $criteria->order = 's.name';
                            $enrollments = StudentEnrollment::model()->findAll($criteria);
                            ?>
                            <style type="text/css" media="print">
                                a[href]:after {
                                    content:"" !important;
                                }
                            </style>
                            <table id="StudentsList" class="table table-bordered table-striped" style="display: table;">
                                <sumary>Turma <?php echo $modelClassroom->name ?></sumary>
                                <thead>
                                    <tr><th>Nome</th><th>Tipo de Matrícula</th><th>Situação na Série</th></tr>
                                </thead>
                                <tbody>
                                     <?php
                                     if(isset($enrollments)){
                                        foreach ($enrollments as $enr) {
                                            $options_adt = array('0' => 'MI', '1' => 'MC','4'=>'MR','3'=>'MT');
                                            $options_css = array('0' => 'N', '1' => 'P','2'=>'R');
                                            $namecss = $enr->id.'[current_stage_situation]';
                                            $nameadt = $enr->id.'[admission_type]';
                                            echo "<tr><td>".$enr->studentFk->name."</a></td><td>".CHtml::dropDownList($nameadt, $enr->admission_type, $options_adt)."</td>";
                                            echo "<td>".CHtml::dropDownList($namecss, $enr->current_stage_situation, $options_css)."</td></tr>";
                                        }
                                        echo "<tr><th>Total:</th><td>" . count($enrollments) . "</td></tr>";
                                    } else {
                                        echo "<tr><th>Não há alunos matriculados.</th></tr>";
                                    }
                                    ?>
                                </tbody>
                                <tfooter>
                                    <?php
                                    echo '<tr><td><input value="Atualizar Todos" type="submit" class="btn btn-icon btn-primary"><i></i></input></td></tr>';
                                    ?>
                                </tfooter>
                            </table>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>

