<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/InstructorsPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Professores por Turma'); ?></h3>
    <div>

        <table >
            <tr>
                <?php
                $html = "";
                $i = 0;
                $id = 0;
                foreach ($report as $r) {
                    if ($id != $r['id']) {
                        $i = 0;
                        $id = $r['id'];
                        $html .= "</table><table class='table table-bordered table-striped'><tr>"
                                . "<th>Código da Turma:</th>"
                                . "<td>" . $r["id"] . "</td>"
                                . "<th>Nome da Turma:</th>"
                                . "<td colspan='3'>" . $r["name"] . "</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<th>Tipo de Atendimento:</th>"
                                . "<td>" . $r["assistance_type"] . "</td>"
                                . "<th>Dias da Semana da Turma:</th>"
                                . "<td colspan='3'>" . $r["week_days"] . "</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<th>Horário de Funcionamento:</th>"
                                . "<td>" . $r["time"] . "</td>"
                                . "<th>Modalidade:</th>"
                                . "<td colspan='3'>" . $r["modality"] . "</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<th>Etapa:</th>"
                                . "<td colspan='5'>" . $r["stage"] . "</td>"
                                . "</tr>"
                                . "<tr><th>Ordem</th><th>Identificação Única</th><th>Data de Nascimento</th><th>Nome do Doscente</th><th>Escolaridade</th><th>Componentes curriculares/eixos Ministrados</th></tr>";
                    }
                    $i++;
                    $html .= "<tr>"
                            . "<td>" . $i . "</td>"
                            . "<td>" . $r['instructor_id'] . "</td>"
                            . "<td>" . $r['birthday_date'] . "</td>"
                            . "<td>" . $r['instructor_name'] . "</td>"
                            . "<td>" . $r['scholarity'] . "</td>"
                            . "<td>" . $r['disciplines'] . "</td>"
                            . "</tr>";
                }
                echo $html;
                ?>
        </table><br>
    </div>
    <?php $this->renderPartial('footer'); ?>
</div>