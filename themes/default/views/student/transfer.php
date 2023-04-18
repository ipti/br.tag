<div id="mainPage" class="main">
<?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Transfer Student'));
?>

<h1><?php echo Yii::t('default', 'Transfer Student'); ?></h1>

<div>
    <div class="row">
        <div class="column">
            <h2>Matrícula Atual</h2>
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
        <div class="column">
             <h2>Nova Matrícula</h2>
        </div>
    </div>
    
</div>

</div>