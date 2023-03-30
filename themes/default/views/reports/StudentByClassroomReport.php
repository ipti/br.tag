<?php
/* @var $this ReportsController */
/* @var $role mixed */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

function age($date){
    $temp = explode('/',$date);
    //$date = str_replace('/','',$date);
    $date1 = new DateTime($temp[2].$temp[1].$temp[0]);
    $age = $date1->diff(new DateTime());
    return $age->y;

}



?>
<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Student By Classroom'); ?></h3>
    <h4 style="text-align: center;"><?php echo $classroom[0]['classroom_name']?></h4>
    <table class="table table-bordered table-striped">
        <tr>
            <th> <b>Aluno </b></th>
            <th> <b>RG </b></th>
            <th> <b>ID INEP</b></th>
            <!-- <th> <b>N° SUS </b></th>
            <th> <b>Data de Nascimento </b></th> -->
            <th> <b>Idade </b></th>
            <th> <b>Mãe </b></th>
            <th> <b>RG Mãe </b></th>
            <th> <b>Pai</b> </th>
            <th> <b>RG Pai</b> </th>
            <th> <b>Responsável</b> </th>
            <th> <b>RG Responsável</b> </th>
        </tr>
        <?php
        $oldClassroom = "";
        foreach($classroom as $c){?>
            <tr>
                <td><?= $c['name'] ?></td>
                <td><?= $c['rg_number'] ?></td>
                <td><?= $c['inep_id'] ?></td>
                <!-- <td><?= $c['cns'] ?></td>
                <td><?= $c['birthday'] ?></td> -->
                <td><?= age($c['birthday']) ?></td>
                <td><?= $c['filiation_1'] ?></td>
                <td><?= $c['filiation_1_rg'] ?></td>
                <td><?= $c['filiation_2'] ?></td>
                <td><?= $c['filiation_2_rg'] ?></td>
                <td><?= $c['responsable_name'] ?></td>
                <td><?= $c['responsable_rg'] ?></td>
            </tr>
    <?php 
            $oldClassroom = $c['classroom_id'];
    
        } 
    ?>
    </table>
    <?php $this->renderPartial('footer'); ?>

</div>

