<?php

/* @var $this ReportsController */
/* @var $students mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsInAlphabeticalOrderRelationReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>
<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Students In Alphabetical Order Relation'); ?></h3>



    <table class="table table-bordered table-striped">
        <tr>
            <th> Ordem </th>
            <th> Identifica&ccedil;&atilde;o &Uacute;nica</th>
            <th> Nome do Aluno </th>
            <th> Data de Nascimento </th>
            <th> C&oacute;digo da Turma </th>
            <th> Nome da Turma </th>
            <th> Tipo de Atendimento </th>
            <th> Modalidade </th>
            <th> Etapa </th>
        </tr>

        <?php
        $ordem = 1;
        foreach ($students as $s) {
            $html = '';
            $html .= '<tr>'
                . '<td>' . $ordem . '</td>'
                . '<td>' . $s['studentInepId'] . '</td>'
                . '<td>' . $s['studentName'] . '</td>'
                . '<td>' . $s['birthday'] . '</td>'
                . '<td>' . $s['classroom_inep_id'] . '</td>'
                . '<td>' . $s['name'] . '</td>'
                . '<td>' . $s['assistance_type'] . '</td>'
                . '<td>' . $s['modality'] . '</td>'
                . '<td>' . $s['stage'] . '</td>'
                . '</tr>';

            $ordem++;
            echo $html;
        }
        $html = '';
        $html .= '<tr>'
                . '<td colspan= 4>' . ' <b> Total de alunos nessa turma: </b>' . ($ordem - 1) . '</td>'
            . '</tr>';

        echo $html;
        ?>

    </table>
</div>