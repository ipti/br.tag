<?php
/* @var $this ReportsController */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/IncompatibleStudentAgeByClassroomReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Incompatible Student Age By Classroom') . ' - ' . Yii::app()->user->year; ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>
<?php

$minAge = 0;
$maxAge = 0;

if(empty($classroom)){
    echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";
}else {
    foreach ($classroom as $c) {
        $html = "";

        echo "<b>Nome da turma: </b>" . $c['name'] . "<br>";
        echo "<b>C&oacute;digo da Turma: </b>" . $c['inep_id'] . "<br>";
        echo "<b>Etapa: </b>" . $c['stage'] . "<br>";
        echo "<b>Modalidade: </b>" . $c['modality'] . "<br>";
        echo "<b>Hor&aacute;rio de Funcionamento: </b>" . $c['initial_hour'] . ":" . $c['initial_minute'] . "-" . $c['final_hour'] . ":" . $c['final_minute'] . "<br>";
        echo "<b>Dias da Semana: </b>" . ($c['week_days_sunday'] == 1 ? "Domingo - " : "") . ($c['week_days_monday'] == 1 ? "Segunda - " : "") .
            ($c['week_days_tuesday'] == 1 ? "Terca - " : "") . ($c['week_days_wednesday'] == 1 ? "Quarta - " : "") .
            ($c['week_days_thursday'] == 1 ? "Quinta - " : "") . ($c['week_days_friday'] == 1 ? "Sexta - " : "") .
            ($c['week_days_saturday'] == 1 ? "Sabado " : "") . "<br>";

        $html .= "<table class= 'table table-bordered table-striped' >";
        $html .= "<tr>"
            . "<th> <b>Ordem </b></th>"
            . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
            . "<th> <b>Data de Nascimento  </b></th>"
            . "<th> <b>Idade  </b></th>"
            . "<th> <b>Nome Completo do Aluno </b></th>"
            . "</tr>";

        $totalAlunos = 0;
        $ordem = 1;

        static $stageAgeMap = [
            'Educação Infantil - Creche (0 a 3 anos)' => ['min' => 0, 'max' => 3],
            'Educação Infantil - Pré-escola (4 e 5 anos)' => ['min' => 4, 'max' => 5],
            'Educação Infantil - Unificada (0 a 5 anos)' => ['min' => 0, 'max' => 5],
            'Ensino Fundamental de 8 anos - 1ª Série' => ['min' => 6, 'max' => 6],
            'Ensino Fundamental de 8 anos - 2ª Série' => ['min' => 7, 'max' => 7],
            'Ensino Fundamental de 8 anos - 3ª Série' => ['min' => 8, 'max' => 8],
            'Ensino Fundamental de 8 anos - 4ª Série' => ['min' => 9, 'max' => 9],
            'Ensino Fundamental de 8 anos - 5ª Série' => ['min' => 10, 'max' => 10],
            'Ensino Fundamental de 8 anos - 6ª Série' => ['min' => 11, 'max' => 11],
            'Ensino Fundamental de 8 anos - 7ª Série' => ['min' => 12, 'max' => 12],
            'Ensino Fundamental de 8 anos - 8ª Série' => ['min' => 13, 'max' => 13],
            'Ensino Fundamental de 9 anos - 1º Ano' => ['min' => 6, 'max' => 6],
            'Ensino Fundamental de 9 anos - 2º Ano' => ['min' => 7, 'max' => 7],
            'Ensino Fundamental de 9 anos - 3º Ano' => ['min' => 8, 'max' => 8],
            'Ensino Fundamental de 9 anos - 4º Ano' => ['min' => 9, 'max' => 9],
            'Ensino Fundamental de 9 anos - 5º Ano' => ['min' => 10, 'max' => 10],
            'Ensino Fundamental de 9 anos - 6º Ano' => ['min' => 11, 'max' => 11],
            'Ensino Fundamental de 9 anos - 7º Ano' => ['min' => 12, 'max' => 12],
            'Ensino Fundamental de 9 anos - 8º Ano' => ['min' => 13, 'max' => 13],
            'Ensino Fundamental de 9 anos - 9º Ano' => ['min' => 14, 'max' => 14],
            'Ensino Médio - 1ª Série' => ['min' => 15, 'max' => 15],
            'Ensino Médio - 2ª Série' => ['min' => 16, 'max' => 16],
            'Ensino Médio - 3ª Série' => ['min' => 17, 'max' => 17],
            'Ensino Médio - 4ª Série' => ['min' => 18, 'max' => 18],
            'Ensino Médio - Não Seriada' => ['min' => 15, 'max' => 18],
            'Curso Técnico Integrado (Ensino Médio Integrado) 1ª Série' => ['min' => 15, 'max' => 15],
            'Curso Técnico Integrado (Ensino Médio Integrado) 2ª Série' => ['min' => 16, 'max' => 16],
            'Curso Técnico Integrado (Ensino Médio Integrado) 3ª Série' => ['min' => 17, 'max' => 17],
            'Curso Técnico Integrado (Ensino Médio Integrado) 4ª Série' => ['min' => 18, 'max' => 18],
            'Curso Técnico Integrado (Ensino Médio Integrado) Não Seriada' => ['min' => 15, 'max' => 18],
            'Ensino Médio - Normal/Magistério 1ª Série' => ['min' => 15, 'max' => 15],
            'Ensino Médio - Normal/Magistério 2ª Série' => ['min' => 16, 'max' => 16],
            'Ensino Médio - Normal/Magistério 3ª Série' => ['min' => 17, 'max' => 17],
            'Ensino Médio - Normal/Magistério 4ª Série' => ['min' => 18, 'max' => 18],
            'Curso Técnico  - Concomitante' => ['min' => 15, 'max' => 16],
            'Curso Técnico  - Subsequente' => ['min' => 17, 'max' => 18],
            'EJA Presencial - Anos iniciais' => ['min' => 6, 'max' => 10],
            'EJA Presencial - Anos finais' => ['min' => 11, 'max' => 14],
            'EJA Presencial - Ensino Médio' => ['min' => 15, 'max' => 17],
            'EJA Semi Presencial - Anos iniciais' => ['min' => 6, 'max' => 10],
            'EJA Semi Presencial - Anos finais' => ['min' => 11, 'max' => 14],
            'EJA Semi Presencial - Ensino Médio' => ['min' => 15, 'max' => 17],
            'EJA Presencial - Anos iniciais e Anos finais' => ['min' => 6, 'max' => 14],
            'EJA Semi Presencial - Anos iniciais e Anos finais' => ['min' => 6, 'max' => 14],
            'EJA Presencial - integrado à Educação Profissional de Nível Fundamental - FIC' => ['min' => 6, 'max' => 14],
            'EJA Semi Presencial - integrado à Educação Profissional de Nível Fundamental - FIC' => ['min' => 6, 'max' => 14],
            'EJA Presencial - integrada à Educação Profissional de Nível Médio' => ['min' => 15, 'max' => 17],
            'EJA Semi Presencial - integrada à Educação Profissional de Nível Médio' => ['min' => 15, 'max' => 17],
            'Educação Profissional Mista - Concomitante e Subsequente' => ['min' => 6, 'max' => 17],
            'EJA Presencial - Ensino Fundamental - Projovem Urbano' => ['min' => 6, 'max' => 14],
            'Segmento Técnico da EJA integrada' => ['min' => 6, 'max' => 17],
            'EJA - Ensino Fundamental -  Anos iniciais' => ['min' => 6, 'max' => 10],
            'EJA - Ensino Fundamental -  Anos finais' => ['min' => 11, 'max' => 14],
            'EJA - Ensino Médio' => ['min' => 11, 'max' => 14],
            'EJA - Ensino Fundamental  - Anos iniciais e Anos finais' => ['min' => 6, 'max' => 14],
            'Curso FIC integrado na modalidade EJA  Nvel Fundamental (EJA integrada  Educação Profissional de ' => ['min' => 6, 'max' => 14],
            'Curso Técnico Integrado na Modalidade EJA (EJA integrada  Educação Profissional de Nvel Médio)' => ['min' => 15, 'max' => 17],
            'Ensino Fundamental de 8 anos - Multi' => ['min' => 6, 'max' => 13],
            'Ensino Fundamental de 8 anos - Correção de Fluxo' => ['min' => 6, 'max' => 13],
            'Ensino Fundamental de 9 anos - Multi' => ['min' => 6, 'max' => 13],
            'Ensino Fundamental de 9 anos - Correção de Fluxo' => ['min' => 6, 'max' => 14],
            'Ensino Fundamental de 8 e 9 anos - Multi 8 e 9 anos' => ['min' => 6, 'max' => 14],
            // 'Ensino Fundamental de 9 anos - 9º Ano' => ['min' => 6, 'max' => 14],
            'Educação Infantil e Ensino Fundamental (8 e 9 anos) Multietapa' => ['min' => 6, 'max' => 14],
        ];

        $minAge = $stageAgeMap[$c['stage']]['min'] ?? 0;
        $maxAge = $stageAgeMap[$c['stage']]['max'] ?? 0;

        foreach ($students as $s) {
            if ($s['classroom_fk'] == $c['id']) {
                // Declara a data! :P
                $data = $s['birthday'];

                // Separa em dia, m�s e ano
                list($dia, $mes, $ano) = explode('/', $data);

                // Descobre que dia � hoje e retorna a unix timestamp
                $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                // Descobre a unix timestamp da data de nascimento do fulano
                $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

                // Depois apenas fazemos o c�lculo j� citado :)
                $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

                if (!($idade >= $minAge && $idade <= $maxAge)) {
                    $html .= "<tr>"
                        . "<td>" . $ordem . "</td>"
                        . "<td>" . $s['inep_id'] . "</td>"
                        . "<td>" . $s['birthday'] . "</td>"
                        . "<td>" . $idade . ' anos' . "</td>"
                        . "<td>" . $s['name'] . "</td>"
                        . "</tr>";
                    $ordem++;
                    $totalAlunos++;
                }
            }
        }

        $minAge = 0;
        $maxAge = 0;

        $html .= "<tr>"
            . "<td colspan= 5>" . " <b> Total de alunos nessa turma: </b>" . $totalAlunos .
            "</td>"
            . "</tr>";
        $html .= "</table>" . "<br>";
        echo $html;

    }
}

?>
<div id="rodape"><?php $this->renderPartial('footer'); ?></div>
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
            size: portrait;
        }
    }
</style>
