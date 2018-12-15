<?php
/* @var $this ReportsController */
/* @var $classrooms mixed */
/* @var $matricula1 mixed */
/* @var $matricula2 mixed */


$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentComparativeAnalysisReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
?>

<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Enrollment Comparative Analysis'); ?></h3>

    <p> <b>Escola:</b> <?php echo $school->inep_id ?>-<?php echo $school->name ?> </p>
    <p> <b>Estado:</b> <?php echo $school->edcensoUfFk->name; ?> </p>
    <p> <b>Munic&iacute;pio:</b> <?php echo $school->edcensoCityFk->name; ?> </p>
    <p> <b>Localiza&ccedil;&atilde;o:</b> <?php echo $school->location == 0? "Rural" : "Urbana" ?> </p>
    <p> <b>Depend&ecirc;ncia Administrativa:</b> <?php echo $school->administrative_dependence == 1 ? "Federal" :
            $school->administrative_dependence == 2? "Estadual" :
                $school->administrative_dependence == 3? "Municipal": "Estadual"
        ?> </p>

    <table class="table table-bordered table-striped">
            <tr>
               <th> <b>Matr&iacute;culas Educacenso <?php echo Yii::app()->user->year-1 ?> </b></th>
               <th> <b>Matr&iacute;culas Educacenso <?php echo Yii::app()->user->year ?> </b></th>
               <th> <b>Produ&ccedil;&atilde;o(%) </b></th>

            </tr>

        <?php
            $contador1 = 0;
            $contador2 = 0;
            $html = '';
            foreach ($classrooms as $classroom) {
                foreach($matricula1 as $m){
                    if($m['classe_id'] == $classroom['id']) {
                        $contador1 += $m['contador'];
                    }
                }

                foreach($matricula2 as $n){
                    if($n['classe_id'] == $classroom['id']) {
                        $contador2 += $n['contador'];
                    }
                }
            }
            $producao =0;
            if($contador1 == 0){
                if($contador2 == 0){
                    $producao = 0;
                }
                else{
                    $producao = 100;
                }
            }
            else{
                if($contador2 == 0){
                    $producao = - $contador2*100 / $contador1;
                }
                else{
                    $producao = $contador2*100 / $contador1;
                }
            }

            $html .= "<tr>"
                . "<td>" . $contador1 . "</td>"
                . "<td>" . $contador2 . "</td>"
                . "<td>" . round($producao) . "%"  . "</td>"

                . "</tr>";

            echo $html;
            ?>
        </table>

    <br> Comparativo de matriculas por modalidade e etapa-serie <?php echo Yii::app()->user->year-1 ?> e <?php echo Yii::app()->user->year ?>  </br>

   <table class="table table-bordered table-striped">
          <tr>
               <td> <b>Ordem </b></td>
               <td> <b>Modalidade</b></td>
               <td> <b>Etapa </b></td>
               <td> <b>Matr&iacute;cula  <?php echo Yii::app()->user->year-1?> (a) </b></td>
               <td> <b>Matr&iacute;cula <?php echo Yii::app()->user->year?> (b) </b></td>
               <td> <b>Produ&ccedil;&atilde;o(%) </b></td>
               <td> <b>Diferen&ccedil;a b-a </b></td>

          </tr>
    <?php
            $ordem = 1;
            $html = "";
            $contador_matricula1 =0;
            $contador_matricula2 =0;
            $total_matricula1 =0;
            $total_matricula2 =0;
            $total_diferenca = 0;


            foreach ($classrooms as $classroom) {
                foreach($matricula1 as $m){
                    if($m['classe_id'] == $classroom['id']) {
                        $contador_matricula1 = $m['contador'];
                    }
                }
                foreach($matricula2 as $n){
                    if($n['classe_id'] == $classroom['id']) {
                       $contador_matricula2 = $n['contador'];
                    }
                }
                $diferenca = $contador_matricula2 - $contador_matricula1;
                $producao = 0;

                if($contador_matricula1 == 0){
                    if($contador_matricula2 == 0){
                        $producao = 0;
                    }
                    else{
                        $producao = 100;
                    }
                }
                else{
                    if($contador_matricula2 == 0){
                        $producao = - ($contador_matricula2*100 / $contador_matricula1);
                    }
                    else{
                        $producao = $contador_matricula2*100 / $contador_matricula1;
                    }
                }
                $html .= "<tr>"
                        . "<td>" . $ordem . "</td>"
                        . "<td>" . $classroom['modality'] . "</td>"
                        . "<td>" . $classroom['stage'] . "</td>"
                        ."<td>" .  $contador_matricula1 . "</td>"
                        ."<td>" .  $contador_matricula2 . "</td>"
                        ."<td>" . round($producao) . "%"  . "</td>"
                        ."<td>" .  $diferenca . "</td>"

                        . "</tr>";
                    $ordem++;
                    $total_matricula1 +=$contador_matricula1;
                    $total_matricula2 +=$contador_matricula2;
                    $contador_matricula1 =0;
                    $contador_matricula2 =0;

            }
            $total_producao = 0;
                if($total_matricula1 == 0){
                    if($total_matricula2 == 0){
                        $total_producao = 0;
                    }
                    else{
                        $total_producao = 100;
                    }
                }
                else{
                    if($total_matricula2 == 0){
                        $total_producao = - ($total_matricula2*100 / $total_matricula1);
                    }
                    else{
                        $total_producao = $total_matricula2*100 / $total_matricula1;
                    }
                }

            $total_diferenca = $total_matricula2 - $total_matricula1;
            echo $html;
            $html = "";

            $html .= "<tr>"
                . "<td colspan=3>Totalizacao</td>"
                ."<td>" . $total_matricula1 . "</td>"
                ."<td>" .  $total_matricula2. "</td>"
                ."<td>" . round($total_producao) . "%"  . "</td>"
                ."<td>" .  $total_diferenca  ."</td>"
                . "</tr>";

            echo $html;
        ?>
    </table>

    <br> Comparativo de matriculas por tipo de atendimento da turma <?php echo Yii::app()->user->year-1 ?> e <?php echo Yii::app()->user->year ?> </br>

    <table class="table table-bordered table-striped">
        <tr>
            <td> <b>Ordem</b> </td>
            <td> <b>Tipo de atendimento </b></td>
            <td> <b>Matr&iacute;cula  <?php echo Yii::app()->user->year-1?> (a) </b></td>
            <td> <b>Matr&iacute;cula <?php echo Yii::app()->user->year?> (b) </b></td>
            <td> <b>Produ&ccedil;&atilde;o(%) </b></td>
            <td> <b>Diferen&ccedil;a b-a </b></td>
        </tr>

        <?php

        $html = "";
        $matriculasAnoAnterior = array(
            'tipo0' => 0,
            'tipo1' => 0,
            'tipo2' => 0,
            'tipo3' => 0,
            'tipo4' => 0,
            'tipo5' => 0
        );
        $matriculasAnoAtual = array(
            'tipo0' => 0,
            'tipo1' => 0,
            'tipo2' => 0,
            'tipo3' => 0,
            'tipo4' => 0,
            'tipo5' => 0
        );

        foreach ($classrooms as $classroom) {
            foreach ($matricula1 as $m1) {
                if ($m1['classe_id'] == $classroom['id']) {
                    if ($classroom["assistance_type"] == 0) {
                        $matriculasAnoAnterior['tipo0'] +=$m1['contador'];

                    } else if ($classroom["assistance_type"] == 1) {
                        $matriculasAnoAnterior['tipo1']+=$m1['contador'];
                    } else if ($classroom["assistance_type"] == 2) {
                        $matriculasAnoAnterior['tipo2']+=$m1['contador'];
                    } else if ($classroom["assistance_type"] == 3) {
                        $matriculasAnoAnterior['tipo3']+=$m1['contador'];
                    } else if ($classroom["assistance_type"] == 4) {
                        $matriculasAnoAnterior['tipo4']+=$m1['contador'];
                    } else if ($classroom["assistance_type"] == 5) {
                        $matriculasAnoAnterior['tipo5']+=$m1['contador'];
                    }
                }
            }

            foreach ($matricula2 as $m2) {
                if ($m2['classe_id'] == $classroom['id']) {
                    if ($classroom["assistance_type"] == 0) {
                        $matriculasAnoAtual['tipo0']+=$m2['contador'];
                    } else if ($classroom["assistance_type"] == 1) {
                        $matriculasAnoAtual['tipo1']+=$m2['contador'];
                    } else if ($classroom["assistance_type"] == 2) {
                        $matriculasAnoAtual['tipo2']+=$m2['contador'];
                    } else if ($classroom["assistance_type"] == 3) {
                        $matriculasAnoAtual['tipo3']+=$m2['contador'];
                    } else if ($classroom["assistance_type"] == 4) {
                        $matriculasAnoAtual['tipo4']+=$m2['contador'];
                    } else if ($classroom["assistance_type"] == 5) {
                        $matriculasAnoAtual['tipo5']+=$m2['contador'];
                    }
                }
            }
        }
        $tipo_de_atendimento = array(
            0 => "N&atilde;o se Aplica",
            1 => "Classe hospitalar",
            2 => "Unidade de interna&ccedil;&atilde;o socioeducativa",
            3 => "Unidade prisional",
            4 => "Atividade complementar",
            5 => "Atendimento Educacional Especializado"
        );

        $ordem = 0;
        for($i=0; $i<6 ;$i++){
            if($matriculasAnoAnterior['tipo'.strval($i)] == 0 && $matriculasAnoAtual['tipo'.strval($i)] == 0){}
            else {

                $producao = 0;

                if ($matriculasAnoAnterior['tipo' . strval($i)] == 0) {
                    if ($matriculasAnoAtual['tipo' . strval($i)] == 0) {
                        $producao = 0;
                    } else {
                        $producao = 100;
                    }
                } else {
                    if ($matriculasAnoAtual['tipo' . strval($i)] == 0) {
                        $producao = -($matriculasAnoAtual['tipo' . strval($i)] * 100 / $matriculasAnoAnterior['tipo' . strval($i)]);
                    } else {
                        $producao = $matriculasAnoAtual['tipo' . strval($i)] * 100 / $matriculasAnoAnterior['tipo' . strval($i)];
                    }
                }

                $diferenca = $matriculasAnoAtual['tipo' . strval($i)] - $matriculasAnoAnterior['tipo' . strval($i)];
                $html = "";
                $ordem++;
                $html .= "<tr>"
                    . "<td>" . $ordem . "</td>"
                    . "<td>" . $tipo_de_atendimento[$i] . "</td>"
                    . "<td>" . $matriculasAnoAnterior['tipo' . strval($i)] . "</td>"
                    . "<td>" . $matriculasAnoAtual['tipo' . strval($i)] . "</td>"
                    . "<td>" . round($producao) . "%" . "</td>"
                    . "<td>" . $diferenca . "</td>"
                    . "</tr>";
                echo $html;
            }
        }
        ?>
    </table>
    <div id="rodape"><?php $this->renderPartial('footer'); ?></div>
</div>