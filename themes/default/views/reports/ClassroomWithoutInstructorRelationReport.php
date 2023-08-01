<?php


/* @var $this ReportsController */
/* @var $professor mixed */
/* @var $classroom mixed */
/* @var &disciplina mixed */

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/ClassroomWithoutInstructorRelationReport/_initialization.js', CClientScript::POS_END);
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

?>


<div class="pageA4H">
    <?php $this->renderPartial('head'); ?>
    <h3><?php echo Yii::t('default', 'Classroom Without Instructor Relation'); ?></h3>
    <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
    </div>

    <?php
    // Caso para quando não há turmas sem instrutor para a escola selecionada
    if (count($classroom) == 0) {
        echo "<br><span class='alert alert-primary'>N&atilde;o h&aacute; turmas para esta escola.</span>";
    } else { ?>
        <table class="table-no-instructors table table-bordered table-striped">
            <tr>
                <th> Ordem</th>
                <th> Nome da Turma</th>
                <th> Etapa</th>
                <th> Componente(s) curricular(es)/eixo(s)</th>
            </tr>

            <?php

            $ordem = 1;
        
            $classroom_number = count($classroom);

            // Laço para percorrer o vetor $classroom e preencher a tabela
            // Nesse caso, as duas tabelas já estão obedecendo a uma mesma ordem, portanto há a garantia
            // de que as linhas estão de acordo para as duas consultas $classroom e $disciplinas
            for ($i = 0; $i < $classroom_number; $i++) {
                
                // Colunas que serão apresentadas na tabela
                // $classroom[linha]['name'];
                // $classroom[linha]['stage'];
                // $disciplina[linha]['discipline'];
                
                // Criando tabela que será adicionada ao HTML na página
                $html = "";
                $html .= "<tr>"
                    . "<td>" . $ordem . "</td>"
                    . "<td>" . $classroom[$i]['name'] . "</td>"
                    . "<td>" . $classroom[$i]['stage'] . "</td>";
                    
                // Verificando caso em que não há componentes curriculares cadastradas na matriz curricular da etapa de ensino
                if($disciplina[$i]['Disciplina'] == null){
                    $html .= "<td>Não há componentes curriculares cadastradas na matriz curricular para essa etapa de ensino </td>";
                }else{
                    $html .= "<td>" . $disciplina[$i]['Disciplina'] . "</td>";
                }

                $html .= "</tr>";
                $ordem++;
                
                echo $html;
            } 

            ?>
        </table>
    <?php 
        }; // Fechando o else 
        $this->renderPartial('footer'); 
    ?>
</div>

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

<script>
    function imprimirPagina() {
      window.print();
    }
</script>