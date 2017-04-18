<?php
/* @var $this ReportsController */
/* @var $classroom mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/IncompatibleStudentAgeByClassroomReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Incompatible Student Age By Classroom'); ?></h3>


<?php

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

        $html .= "<table class= 'table table-bordered table-striped' >";
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

        if ($c['stage'] == 'Educação Infantil - Creche (0 a 3 anos)') {
            $min_age = 0;
            $max_age = 3;
        } else if ($c['stage'] == utf8_encode('Educa��o Infantil - Pr�-escola (4 e 5 anos)')) {
            $min_age = 4;
            $max_age = 5;
        } else if ($c['stage'] == utf8_encode('Educa��o Infantil - Unificada (0 a 5 anos)')) {
            $min_age = 0;
            $max_age = 5;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 1� S�rie')) {
            $min_age = 6;
            $max_age = 6;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 2� S�rie')) {
            $min_age = 7;
            $max_age = 7;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 3� S�rie')) {
            $min_age = 8;
            $max_age = 8;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 4� S�rie')) {
            $min_age = 9;
            $max_age = 9;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 5� S�rie')) {
            $min_age = 10;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 6� S�rie')) {
            $min_age = 11;
            $max_age = 11;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 7� S�rie')) {
            $min_age = 12;
            $max_age = 12;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - 8� S�rie')) {
            $min_age = 13;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 1� Ano')) {
            $min_age = 6;
            $max_age = 6;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 2� Ano')) {
            $min_age = 7;
            $max_age = 7;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 3� Ano')) {
            $min_age = 8;
            $max_age = 8;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 4� Ano')) {
            $min_age = 9;
            $max_age = 9;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 5� Ano')) {
            $min_age = 10;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 6� Ano')) {
            $min_age = 11;
            $max_age = 11;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 7� Ano')) {
            $min_age = 12;
            $max_age = 12;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 8� Ano')) {
            $min_age = 13;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 9� Ano')) {
            $min_age = 14;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - 1� S�rie')) {
            $min_age = 15;
            $max_age = 15;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - 2� S�rie')) {
            $min_age = 16;
            $max_age = 16;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - 3� S�rie')) {
            $min_age = 17;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - 4� S�rie')) {
            $min_age = 18;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - N�o Seriada')) {
            $min_age = 15;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Curso T�cnico Integrado (Ensino M�dio Integrado) 1� S�rie')) {
            $min_age = 15;
            $max_age = 15;
        } else if ($c['stage'] == utf8_encode('Curso T�cnico Integrado (Ensino M�dio Integrado) 2� S�rie')) {
            $min_age = 16;
            $max_age = 16;
        } else if ($c['stage'] == utf8_encode('Curso T�cnico Integrado (Ensino M�dio Integrado) 3� S�rie')) {
            $min_age = 17;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('Curso T�cnico Integrado (Ensino M�dio Integrado) 4� S�rie')) {
            $min_age = 18;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Curso T�cnico Integrado (Ensino M�dio Integrado) N�o Seriada')) {
            $min_age = 15;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - Normal/Magist�rio 1� S�rie')) {
            $min_age = 15;
            $max_age = 15;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - Normal/Magist�rio 2� S�rie')) {
            $min_age = 16;
            $max_age = 16;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - Normal/Magist�rio 3� S�rie')) {
            $min_age = 17;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('Ensino M�dio - Normal/Magist�rio 4� S�rie')) {
            $min_age = 18;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('Curso T�cnico  - Concomitante')) {
            $min_age = 15;
            $max_age = 16;
        } else if ($c['stage'] == utf8_encode('Curso T�cnico  - Subsequente')) {
            $min_age = 17;
            $max_age = 18;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Anos iniciais')) {
            $min_age = 6;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Anos finais')) {
            $min_age = 11;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Ensino Médio')) {
            $min_age = 15;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - Anos iniciais')) {
            $min_age = 6;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - Anos finais')) {
            $min_age = 11;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - Ensino Médio')) {
            $min_age = 15;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Anos iniciais e Anos finais')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - Anos iniciais e Anos finais')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - integrado à Educação Profissional de Nível Fundamental - FIC')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - integrado à Educação Profissional de Nível Fundamental - FIC')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - integrada à Educação Profissional de Nível Médio')) {
            $min_age = 15;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA Semi Presencial - integrada à Educação Profissional de Nível Médio')) {
            $min_age = 15;
            $max_age = 17;
        } //duvidas
        else if ($c['stage'] == utf8_encode('Educa��o Profissional Mista - Concomitante e Subsequente')) {
            $min_age = 6;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA Presencial - Ensino Fundamental - Projovem Urbano')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Segmento Técnico da EJA integrada')) {
            $min_age = 6;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('EJA - Ensino Fundamental -  Anos iniciais')) {
            $min_age = 6;
            $max_age = 10;
        } else if ($c['stage'] == utf8_encode('EJA - Ensino Fundamental -  Anos finais')) {
            $min_age = 11;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA - Ensino M�dio')) {
            $min_age = 11;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('EJA - Ensino Fundamental  - Anos iniciais e Anos finais')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Curso FIC integrado na modalidade EJA � N�vel Fundamental (EJA integrada � Educa��o Profissional de ')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Curso T�cnico Integrado na Modalidade EJA (EJA integrada � Educa��o Profissional de N�vel M�dio)')) {
            $min_age = 15;
            $max_age = 17;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - Multi')) {
            $min_age = 6;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 anos - Corre��o de Fluxo')) {
            $min_age = 6;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - Multi')) {
            $min_age = 6;
            $max_age = 13;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - Corre��o de Fluxo')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 8 e 9 anos - Multi 8 e 9 anos')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Ensino Fundamental de 9 anos - 9� Ano')) {
            $min_age = 6;
            $max_age = 14;
        } else if ($c['stage'] == utf8_encode('Educa��o Infantil e Ensino Fundamental (8 e 9 anos) Multietapa')) {
            $min_age = 6;
            $max_age = 14;
        }

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