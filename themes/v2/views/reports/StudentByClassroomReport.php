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
    <table class="table table-bordered table-striped">
        <tr>
            <th> <b>Aluno </b></th>
            <th> <b>N° INEP</b></th>
            <th> <b>N° SUS </b></th>
            <th> <b>Data de Nascimento </b></th>
            <th> <b>Idade </b></th>
            <th> <b>Mãe </b></th>
            <th> <b>Pai</b> </th>
            <th> <b>Responsável</b> </th>
        </tr>
        <?php
        $oldClassroom = "";
        foreach($classroom as $c){
            if($c['classroom_id'] != $oldClassroom){ ?>
                <tr>
                    <td style="text-align: center; font-size: 14px; vertical-align: inherit;" colspan="7" align="center" valign="middle" height="30"> <b><?= $c['classroom_name'] ?></b></td>
                </tr>
        <?php } ?>
            <tr>
                <td><?= $c['name'] ?></td>
                <td><?= $c['inep_id'] ?></td>
                <td><?= $c['cns'] ?></td>
                <td><?= $c['birthday'] ?></td>
                <td><?= age($c['birthday']) ?></td>
                <td><?= $c['filiation_1'] ?></td>
                <td><?= $c['filiation_2'] ?></td>
                <td><?= $c['responsable_name'] ?></td>
            </tr>
    <?php 
            $oldClassroom = $c['classroom_id'];
    
        } 
    ?>
    </table>
    <?php $this->renderPartial('footer'); ?>

</div>

