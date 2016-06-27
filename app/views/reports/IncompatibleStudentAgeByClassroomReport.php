<?php
/* @var $this ReportsController */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/IncompatibleStudentAgeByClassroomReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>
<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Incompatible Student Age By Classroom'); ?></h3>
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

$min_age = 0;
$max_age = 0;

if(count($classroom) == 0){
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

        $html .= "<table class= table table-bordered table-striped >";
        $html .= "<tr>"
            . "<th> <b>Ordem </b></th>"
            . "<th> <b>Identifica&ccedil;&atilde;o &Uacute;nica </b></th>"
            . "<th> <b>Data de Nascimento  </b></th>"
            . "<th> <b>Idade  </b></th>"
            . "<th> <b>Nome Completo do Aluno </b></th>"
            . "</tr>";

        $total_alunos = 0;
        $ordem = 1;

        //mapeamento feito atraves da relacao idade x serie mostrada em http://www.escolaparalelo.com/idadexserie.html

        if ($c['stage'] == utf8_encode('Educação Infantil - Creche (0 a 3 anos)')) {
            $min_age = 0;
            $max_age = 3;
        } else if ($c['stage'] == utf8_encode('Educação Infantil - Pré-escola (4 e 5 anos)')) {
            $min_age = 4;
            $max_age = 5;
        } else if ($c['stage'] == utf8_encode('Educação Infantil - Unificada (0 a 5 anos)')) {
            $min_age = 0;
            $max_age = 5;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 1ª Série')) {
            $min_age = 6;
            $max_age = 6;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 2ª Série')) {
            $min_age = 7;
            $max_age = 7;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 3ª Série')) {
            $min_age = 8;
            $max_age = 8;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 4ª Série')) {
            $min_age = 9;
            $max_age = 9;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 5ª Série')) {
            $min_age = 10;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 6ª Série')) {
            $min_age = 11;
            $max_age = 11;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 7ª Série')) {
            $min_age = 12;
            $max_age = 12;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 8ª Série')) {
            $min_age = 13;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 1º Ano')) {
            $min_age = 6;
            $max_age = 6;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 2º Ano')) {
            $min_age = 7;
            $max_age = 7;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 3º Ano')) {
            $min_age = 8;
            $max_age = 8;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 4º Ano')) {
            $min_age = 9;
            $max_age = 9;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 5º Ano')) {
            $min_age = 10;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 6º Ano')) {
            $min_age = 11;
            $max_age = 11;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 7º Ano')) {
            $min_age = 12;
            $max_age = 12;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 8º Ano')) {
            $min_age = 13;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 9º Ano')) {
            $min_age = 14;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - 1ª Série')) {
            $min_age = 15;
            $max_age = 15;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - 2ª Série')) {
            $min_age = 16;
            $max_age = 16;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - 3ª Série')) {
            $min_age = 17;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - 4ª Série')) {
            $min_age = 18;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - Não Seriada')) {
            $min_age = 15;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Curso Técnico Integrado (Ensino Médio Integrado) 1ª Série')) {
            $min_age = 15;
            $max_age = 15;
        } else if ($c['stage'] == utf8_encode('Curso Técnico Integrado (Ensino Médio Integrado) 2ª Série')) {
            $min_age = 16;
            $max_age = 16;
        } else if ($c['stage'] == utf8_encode('Curso Técnico Integrado (Ensino Médio Integrado) 3ª Série')) {
            $min_age = 17;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('Curso Técnico Integrado (Ensino Médio Integrado) 4ª Série')) {
            $min_age = 18;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Curso Técnico Integrado (Ensino Médio Integrado) Não Seriada')) {
            $min_age = 15;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - Normal/Magistério 1ª Série')) {
            $min_age = 15;
            $max_age = 15;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - Normal/Magistério 2ª Série')) {
            $min_age = 16;
            $max_age = 16;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - Normal/Magistério 3ª Série')) {
            $min_age = 17;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('Ensino Médio - Normal/Magistério 4ª Série')) {
            $min_age = 18;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Curso Técnico  - Concomitante')) {
            $min_age = 15;
            $max_age = 16;
        } else if ($c['stage'] == utf8_encode('Curso Técnico  - Subsequente')) {
            $min_age = 17;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Anos iniciais')) {
            $min_age = 6;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Anos finais')) {
            $min_age = 11;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Ensino MÃ©dio')) {
            $min_age = 15;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - Anos iniciais')) {
            $min_age = 6;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - Anos finais')) {
            $min_age = 11;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - Ensino MÃ©dio')) {
            $min_age = 15;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Anos iniciais e Anos finais')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - Anos iniciais e Anos finais')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - integrado Ã  EducaÃ§Ã£o Profissional de NÃ­vel Fundamental - FIC')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - integrado Ã  EducaÃ§Ã£o Profissional de NÃ­vel Fundamental - FIC')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - integrada Ã  EducaÃ§Ã£o Profissional de NÃ­vel MÃ©dio')) {
            $min_age = 15;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - integrada Ã  EducaÃ§Ã£o Profissional de NÃ­vel MÃ©dio')) {
            $min_age = 15;
            $max_age = 17;
        } //duvidas
        else if ($c['stage'] == utf8_encode('Educação Profissional Mista - Concomitante e Subsequente')) {
            $min_age = 6;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Ensino Fundamental - Projovem Urbano')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Segmento TÃ©cnico da EJA integrada')) {
            $min_age = 6;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA - Ensino Fundamental -  Anos iniciais')) {
            $min_age = 6;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('EJA - Ensino Fundamental -  Anos finais')) {
            $min_age = 11;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA - Ensino Médio')) {
            $min_age = 11;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA - Ensino Fundamental  - Anos iniciais e Anos finais')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Curso FIC integrado na modalidade EJA – Nível Fundamental (EJA integrada à Educação Profissional de ')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Curso Técnico Integrado na Modalidade EJA (EJA integrada à Educação Profissional de Nível Médio)')) {
            $min_age = 15;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - Multi')) {
            $min_age = 6;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - Correção de Fluxo')) {
            $min_age = 6;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - Multi')) {
            $min_age = 6;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - Correção de Fluxo')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 e 9 anos - Multi 8 e 9 anos')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 9º Ano')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Educação Infantil e Ensino Fundamental (8 e 9 anos) Multietapa')) {
            $min_age = 6;
            $max_age = 14;
        }

        foreach ($students as $s) {
            if ($s['classroom_fk'] == $c['id']) {
                // Declara a data! :P
                $data = $s['birthday'];

                // Separa em dia, mês e ano
                list($dia, $mes, $ano) = explode('/', $data);

                // Descobre que dia é hoje e retorna a unix timestamp
                $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                // Descobre a unix timestamp da data de nascimento do fulano
                $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

                // Depois apenas fazemos o cálculo já citado :)
                $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

                if ($idade >= $min_age && $idade <= $max_age) {

                } else {
                    $html .= "<tr>"
                        . "<td>" . $ordem . "</td>"
                        . "<td>" . $s['inep_id'] . "</td>"
                        . "<td>" . $s['birthday'] . "</td>"
                        . "<td>" . $idade . ' anos' . "</td>"
                        . "<td>" . $s['name'] . "</td>"
                        . "</tr>";
                    $ordem++;
                    $total_alunos++;
                }
            }
        }

        $min_age = 0;
        $max_age = 0;

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