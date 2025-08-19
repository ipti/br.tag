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
    <h3><?php echo Yii::t('default', 'Student By Classroom') . ' - ' . Yii::app()->user->year; ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <h4 style="text-align: center;"><?php echo $classroom[0]['classroom_name']?></h4>
    <table class="table table-bordered table-striped">
        <tr>
            <th> <b>Aluno </b></th>
            <th> <b>CPF </b></th>
            <th> <b>ID INEP</b></th>
            <th> <b>Data de Nascimento </b></th>
            <th> <b>Filiação 1</b></th>
            <th> <b>RG Filiação 1</b></th>
            <th> <b>Filiação 2</b> </th>
            <th> <b>RG Filiação 2</b> </th>
            <th> <b>Responsável</b> </th>
            <th> <b>CPF Responsável</b> </th>
            <th> <b>Tel. Responsável</b> </th>
            <th> <b>Endereço</b> </th>
            <?= ($classroom[0]['stage'] == "6" || $classroom[0]['stage'] == "7" ? "<th> <b>Ano/Turma</b> </th>" : "") ?>
        </tr>
        <?php
        $oldClassroom = "";
        foreach($classroom as $c){?>
            <tr>
                <td><?= $c['name'] ?></td>
                <td><?= $c['cpf'] ?></td>
                <td><?= $c['inep_id'] ?></td>
                <td><?= $c['birthday'] ?></td>
                <td><?= $c['filiation_1'] ?></td>
                <td><?= $c['filiation_1_rg'] ?></td>
                <td><?= $c['filiation_2'] ?></td>
                <td><?= $c['filiation_2_rg'] ?></td>
                <td><?= $c['responsable_name'] ?></td>
                <td><?= $c['responsable_cpf'] ?></td>
                <td><?= $c['responsable_telephone'] ?></td>
                <td><?= $c['address'] ?></td>
                <?= ($classroom[0]['stage'] == "6" || $classroom[0]['stage'] == "7" ? "<td>" . ($c['stage_alias'] != "" ? $c['stage_alias'] : $c['stage_name']) . "</td>" : "") ?>
            </tr>
    <?php
            $oldClassroom = $c['classroom_id'];

        }
    ?>
    </table>
    <?php $this->renderPartial('footer'); ?>

</div>

<script>
    function imprimirPagina() {
      window.print();
    }
</script>

<style>
    @media print {
        .hidden-print {
            display: none;
        }
        @page {
            size: landscape;
        }
    }
</style>
