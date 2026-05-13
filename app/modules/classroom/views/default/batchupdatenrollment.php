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

                            <style type="text/css" media="print">
                                a[href]:after {
                                    content:"" !important;
                                }
                            </style>
                            <table id="StudentsList" class="table table-bordered table-striped" style="display: table;">
                                <sumary>Turma <?php echo $modelClassroom->name ?></sumary>
                                <thead>
                                    <tr><th>Nome</th><th>Rematricula</th></tr>
                                </thead>
                                <tbody>
                                     <?php
                                     if(isset($enrollments)){
                                        foreach ($enrollments as $enr) {
                                            $namepublic = $enr->id.'[reenrollment]';
                                            echo "<tr><td>".$enr->studentFk->name."</a></td>";
                                            echo "<td>".CHtml::dropDownList($namepublic, $enr->reenrollment, array('1'=>'SIM','0'=>'NÃO'))."</td></tr>";
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>

