<?php
/* @var $this ReportsController */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsBetween5And14YearsOldReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Students Between 5 And 14 Years Old'); ?></h3>
            <div class="buttons">
                <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?>
                    <i></i></a>
            </div>
        </div>
    </div>


<div class="innerLR">
    <p> <b>Escola: </b> <?= $school->inep_id ?>-<?= $school->name ?></p>
    <p> <b>Estado: </b> <? echo $school->edcensoUfFk->name; ?></p>
    <p> <b>Munic&iacute;pio:</b> <? echo $school->edcensoCityFk->name; ?> </p>
    <p> <b>Localiza&ccedil;&atilde;o:</b> <? echo $school->location == 0? "Rural" : "Urbana" ?> </p>
    <p> <b>Depend&ecirc;ncia Administrativa:</b> <? echo $school->administrative_dependence == 1 ? "Federal" :
            $school->administrative_dependence == 2? "Estadual" :
                $school->administrative_dependence == 3? "Municipal": "Estadual"
        ?> </p>

    <?

    if(count($classroom) == 0){
        echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";
    }else {
        foreach($classroom as $c) {
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

            $html .= "<table class= table table-bordered table-striped >";
            $html .= "<tr>"
                . "<th> <b>Ordem </b></th>"
                . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
                . "<th> <b>Data de Nascimento  </b></th>"
                . "<th> <b>Idade  </b></th>"
                . "<th> <b>Nome Completo do Aluno </b></th>"
                . "<th> <b>Nome Completo do M&atilde;e </b></th>"
                . "<th> <b>Nome Completo do  Pai </b></th>"
                . "</tr>";

            $total_alunos = 0;
            $ordem = 1;

            foreach ($students as $s) {
                if ($s['classroom_fk'] == $c['id']) {
                    // Declara a data! :P
                    $data = $s['birthday'];

                    // Separa em dia, mês e ano
                    list($dia, $mes, $ano) = explode('/', $data);

                    // Descobre que dia é hoje e retorna a unix timestamp
                    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                    // Descobre a unix timestamp da data de nascimento do fulano
                    $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

                    // Depois apenas fazemos o cálculo já citado :)
                    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

                    if($idade >= 5 && $idade <= 14) {
                           $html .= "<tr>"
                                . "<td>" . $ordem . "</td>"
                                . "<td>" . $s['inep_id'] . "</td>"
                                . "<td>" . $s['birthday'] . "</td>"
                                . "<td>" . $idade .' anos' . "</td>"
                                . "<td>" . $s['name'] . "</td>"
                                . "<td>" . $s['filiation_1'] . "</td>"
                                . "<td>" . $s['filiation_2'] . "</td>"
                                . "</tr>";
                            $ordem++;
                            $total_alunos++;
                    }
                }
            }

            $html .= "<tr>"
                . "<td colspan= 4>" . " <b> Total de alunos nessa turma: </b>" . $total_alunos .
                "</td>"
                . "</tr>";
            $html .= "</table>" . "<br>";
            echo $html;
        }
    }
    ?>
</div>
