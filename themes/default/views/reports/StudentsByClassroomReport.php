<?php

/* @var $this ReportsController */
/* @var $classroom mixed */
/* @var $students mixed*/

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsByClassroomReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>


<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Students By Classroom'); ?></h3>

    <?php

    if (count($classroom) == 0) {
        echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";
    } else {
        foreach ($classroom as $c) {
            $html = '';

            echo '<b>Nome da turma: </b>' . $c['name'] . '<br>';
            echo '<b>C&oacute;digo da Turma: </b>' . $c['inep_id'] . '<br>';
            echo '<b>Etapa: </b>' . $c['stage'] . '<br>';
            echo '<b>Modalidade: </b>' . $c['modality'] . '<br>';
            echo '<b>Hor&aacute;rio de Funcionamento: </b>' . $c['initial_hour'] . ':' . $c['initial_minute'] . '-' . $c['final_hour'] . ':' . $c['final_minute'] . '<br>';
            echo '<b>Dias da Semana: </b>' . ($c['week_days_sunday'] == 1 ? 'Domingo - ' : '') . ($c['week_days_monday'] == 1 ? 'Segunda - ' : '') .
                ($c['week_days_tuesday'] == 1 ? 'Terca - ' : '') . ($c['week_days_wednesday'] == 1 ? 'Quarta - ' : '') .
                ($c['week_days_thursday'] == 1 ? 'Quinta - ' : '') . ($c['week_days_friday'] == 1 ? 'Sexta - ' : '') .
                ($c['week_days_saturday'] == 1 ? 'Sabado ' : '') . '<br>';

            $html .= "<table class= 'table table-bordered table-striped' >";
            $html .= '<tr>'
                . '<th> <b>Ordem </b></th>'
                . '<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>'
                . '<th> <b>Data de Nascimento  </b></th>'
                . '<th> <b>Nome Completo do Aluno </b></th>'
                . '</tr>';

            $total_alunos = 0;
            $ordem = 1;

            foreach ($students as $s) {
                if ($s['classroom_fk'] == $c['id']) {
                    $html .= '<tr>'
                        . '<td>' . $ordem . '</td>'
                        . '<td>' . $s['inep_id'] . '</td>'
                        . '<td>' . $s['birthday'] . '</td>'
                        . '<td>' . $s['name'] . '</td>'
                        . '</tr>';
                    $ordem++;
                    $total_alunos++;
                }
            }

            $html .= '<tr>'
                . '<td colspan= 4>' . ' <b> Total de alunos nessa turma: </b>' . $total_alunos .
                '</td>'
                . '</tr>';
            $html .= '</table>' . '<br>';
            echo $html;
        }
    }
    ?>
</div>