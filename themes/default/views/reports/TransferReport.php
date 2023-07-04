<?php
/* @var $this ReportsController */
/* @var $role mixed */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>  

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo $title ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
    <h4 style="text-align: center;"><?php echo $header?></h4>
    <table class="table table-bordered table-striped" aria-labelledby="Transfer Report Table">
        <thead>
            <tr>
                <th scope="col">Nome do Aluno</th>
                <th scope="col">CPF</th>
                <th scope="col">Turma</th>
                <th scope="col">Escola</th>
                <th scope="col">Responsável</th>
                <th scope="col">Telefone</th>
                <th scope="col">Data de Transferência</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($report as $r) {?>
                <tr>
                    <td><?= $r['name']?></td>
                    <td><?= $r['cpf']?></td>
                    <td><?= $r['classroom_name']?></td>
                    <td><?= $r['school_name']?></td>
                    <td><?= $r['responsable_name']?></td>
                    <td><?= $r['responsable_telephone']?></td>
                    <td><?= date('d/m/Y', strtotime($r['transfer_date']))?></td>
                </tr>
            <?php }?>
        </tbody>
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