<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/QuarterlyReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$turno =  $classroom->turn;
if ($turno == 'M') {
   $turno = "Matutino";
} else if ($turno == 'T') {
   $turno = "Vespertino";
} else if ($turno == 'N') {
   $turno = "Noturno";
} else if ($turno == '' || $turno == null) {
   $turno = "______________________";
}

?>
<div class="pageA4H page" style="height: auto;">
   <!-- CRECHE II -->
   <div class="cabecalho" style="margin: 30px 0;">
      <?php $this->renderPartial('buzios/headers/headBuziosIII'); ?>
   </div>
   <h4>
      <?php echo Yii::t('default', 'Quarterly Report') . ' - ' . $current_year ?></h3>
      <div class="row-fluid hidden-print">
        <div class="span12">
            <div class="buttons">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;"><img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i></a>
            </div>
        </div>
      </div>
      <div class="container-box header-container" style="margin-bottom: 200px;">
         <p>Unidade Escolar: <?php echo $school->name ?></p>
         <?php
         for ($i = 1; $i <= 2; $i++) {
            if ($regentTeachers[$i - 1]) {
               echo "<p>Professor(a) Regente " . $i . ": " . $regentTeachers[$i - 1]['instructor_name'] . "</p>";
            } else {
               echo "<p>Professor(a) Regente " . $i . ": _________________________________________________________________________________________________________________________________</p>";
            }
         }
         ?>
         <p>
            <span class="pull-left">Etapa: <?php echo $classroom_etapa ? $classroom_etapa->name : '______________________' ?></span>
         <div class="pull-right" style="margin-right: 40px;">
            <span style="margin-right: 100px;">Turno: <?php echo $turno ?></span>
            <span>Turma: <?php echo $classroom->name ?></span>
         </div>
         </p>
         <p style="margin-top: 60px;">
            <span class="pull-left">Nome do Aluno (a): <?php echo $student_identification->name ?></span>
            <span class="pull-right" style="margin-right: 40px;">Data de Nascimento: <?php echo $dateFormatCorrect ? $student_identification->birthday : date('d/m/Y', strtotime($student_identification->birthday)); ?></span>
         </p>
      </div>
      <div class="container-box learning-objectives">
         <!-- 1º ANO -->
         <div class="first-year-container">
            <!-- PAGINA 1 -->
            <h4>
               <u>OBJETIVOS DE APRENDIZAGEM - CRECHE II</u></h3>
               <table aria-labelledby="Second Year Table">
                  <thead>
                     <tr>
                        <th scope="col" style="width: 70%;"><u>Campo de experiências: O Eu, O Outro e O Nós</u></th>
                        <th scope="col"><u>1º tri</u></th>
                        <th scope="col"><u>2º tri</u></th>
                        <th scope="col"><u>3º tri</u></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Comunica necessidades, desejos e emoções, utilizando gestos, balbucios e palavras.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Desenvolve o controle progressivo de suas necessidades fisiológicas (esfincterianas, alimentares, de sono, entre outros).</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Interage com outras crianças e adultos adaptando-se ao convívio social e gradativamente respeitando regras básicas nas interações, jogos e brincadeiras.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Realiza gradativamente, com ganho de autonomia, atividades de alimentação e higienização.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                  </tbody>
               </table>
               <table aria-labelledby="Second Year Table">
                  <thead>
                     <tr>
                        <th scope="col" style="width: 70%;"><u>Campo de experiências: Traços, Sons, Cores e Formas</u></th>
                        <th scope="col"><u>1º tri</u></th>
                        <th scope="col"><u>2º tri</u></th>
                        <th scope="col"><u>3º tri</u></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Traça marcas gráficas, em diferentes suportes, usando instrumentos riscantes e tintas.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Explora diferentes fontes sonoras e materiais para acompanhar brincadeiras cantadas, canções, músicas e melodias.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                  </tbody>
               </table>
               <table aria-labelledby="Second Year Table">
                  <thead>
                     <tr>
                        <th scope="col" style="width: 70%;"><u>Campo de experiências: Corpo, Gestos e Movimentos</u></th>
                        <th scope="col"><u>1º tri</u></th>
                        <th scope="col"><u>2º tri</u></th>
                        <th scope="col"><u>3º tri</u></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Percebe o próprio corpo e como ele se movimenta e se expressa.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Utiliza os movimentos de preensão, encaixe e lançamento, ampliando suas possibilidades de manuseio de diferentes materiais e objetos.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Participa de atividades que envolvam sensações táteis e percepção das partes do corpo.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Vivencia as etapas de desenvolvimento psicomotor: arrastar, rolar, engatinhar e andar.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Desloca seu corpo no espaço (orientando-se por noções como em frente, atrás, no alto, embaixo, dentro, fora, entre outros) e se envolve em brincadeiras e atividades de diferentes naturezas.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                  </tbody>
               </table>
               <table aria-labelledby="Second Year Table">
                  <thead>
                     <tr>
                        <th scope="col" style="width: 70%;"><u>Campo de experiências: Espaços, Tempos, Quantidades, Relações e Transformações</u></th>
                        <th scope="col"><u>1º tri</u></th>
                        <th scope="col"><u>2º tri</u></th>
                        <th scope="col"><u>3º tri</u></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Observa e explora a paisagem local.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Desenvolve noções de orientação espacial por meio de experiências de deslocamento de si e dos objetos, explorando direção e posição no espaço.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                  </tbody>
               </table>
               <table aria-labelledby="Second Year Table">
                  <thead>
                     <tr>
                        <th scope="col" style="width: 70%;"><u>Campo de experiências: Escuta, Fala, Pensamento e Imaginação</u></th>
                        <th scope="col"><u>1º tri</u></th>
                        <th scope="col"><u>2º tri</u></th>
                        <th scope="col"><u>3º tri</u></th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Utiliza diferentes linguagens para comunicar-se e expressar-se (gestos, expressões faciais, fala, entre outros).</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Atende quando chamado pelo nome.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Desenvolve a capacidade de lembrar e executar ações em passos sequenciais, seguindo instruções orais desde ordens simples até as mais longas</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                     <tr>
                        <td>Desenha livremente representando intenções comunicativas.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                  </tbody>
               </table>
               <table aria-labelledby="Legenda table" style="margin-top: 5px;">
                  <thead style="background: none;">
                     <tr>
                        <th scope="col"><u>LEGENDA:</u></th>
                        <th scope="col"><u>S - SIM:</u></th>
                        <th scope="col"><u>P - PARCIALMENTE:</u></th>
                        <th scope="col"><u>N - NÃO:</u></th>
                        <th scope="col"><u>ANT - AINDA NÃO TRABALHADO:</u></th>
                     </tr>
                  </thead>
               </table>
               <div class="container-box student-performance">
                  <p style="margin-top: 0px;"><u>Espaço destinado a descrição do aluno durante o trimestre</u></p>
                  <p style="margin-top: 40px;margin-bottom:20px;"><span class="pull-left" style="margin-left: 20px;">1º Trimestre</span>
                     <span style="margin-right: 10px;margin-left: 300px">Aulas dadas:___________________</span>
                     <span class="pull-right" style="margin-right: 100px;">Faltas:___________________</span>
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <div class="container-box signatures-container" style="margin-top: 30px !important;">
                     <p>
                        <span>_______________________________</span>
                        <span>_______________________________</span>
                        <span>_____________________________________</span>
                        <span>_____________________________________</span>
                     </p>
                     <p>
                        <span style="margin-left: -2px;">Professor(a) Regente 1</span>
                        <span style="margin-left: -30px;">Professor(a) Regente 2</span>
                        <span style="margin-left: -15px;">Supervisor(a) Escolar</span>
                        <span>Orientador(a) Educacional</span>
                     </p>
                     <p style="margin-top: 30px;">
                        <span>Assinatura do Responsável:</span>
                        <span>________________________________________________________________________________________________________________
                        </span>
                     </p>
                  </div>
                  <p style="margin-top: 10px;margin-bottom:20px;"><span class="pull-left" style="margin-left: 20px;">2º Trimestre</span>
                     <span style="margin-right: 10px;margin-left: 300px">Aulas dadas:___________________</span>
                     <span class="pull-right" style="margin-right: 100px;">Faltas:___________________</span>
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <div class="container-box signatures-container" style="margin-top: 30px !important;">
                     <p>
                        <span>_______________________________</span>
                        <span>_______________________________</span>
                        <span>_____________________________________</span>
                        <span>_____________________________________</span>
                     </p>
                     <p>
                        <span style="margin-left: -2px;">Professor(a) Regente 1</span>
                        <span style="margin-left: -30px;">Professor(a) Regente 2</span>
                        <span style="margin-left: -15px;">Supervisor(a) Escolar</span>
                        <span>Orientador(a) Educacional</span>
                     </p>
                     <p style="margin-top: 30px;">
                        <span>Assinatura do Responsável:</span>
                        <span>________________________________________________________________________________________________________________
                        </span>
                     </p>
                  </div>
                  <p style="margin-top: 10px;margin-bottom:20px;"><span class="pull-left" style="margin-left: 20px;">3º Trimestre</span>
                     <span style="margin-right: 10px;margin-left: 300px">Aulas dadas:___________________</span>
                     <span class="pull-right" style="margin-right: 100px;">Faltas:___________________</span>
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <p>__________________________________________________________________________________________________________________________________________________
                  </p>
                  <div class="container-box signatures-container" style="margin-top: 30px !important;">
                     <p>
                        <span>_______________________________</span>
                        <span>_______________________________</span>
                        <span>_____________________________________</span>
                        <span>_____________________________________</span>
                     </p>
                     <p>
                        <span style="margin-left: -2px;">Professor(a) Regente 1</span>
                        <span style="margin-left: -30px;">Professor(a) Regente 2</span>
                        <span style="margin-left: -15px;">Supervisor(a) Escolar</span>
                        <span>Orientador(a) Educacional</span>
                     </p>
                     <p style="margin-top: 30px;">
                        <span>Assinatura do Responsável:</span>
                        <span>________________________________________________________________________________________________________________
                        </span>
                     </p>
                     <p style="margin-top: 30px;">
                        <span>_______________________________________</span>
                     </p>
                     <p>
                        <span>Diretor (a) Escolar</span>
                     </p>
                  </div>
               </div>
               <style>
                  @media print {
                     .hidden-print {
                        display: none;
                     }

                     @page {
                        size: portrait;
                     }
                  }

                  .signatures-container {
                     margin-top: 80px !important;
                     width: 96%;
                  }

                  .signatures-container p {
                     margin: 10px 0px;
                     width: 100%;
                     align-items: center;
                     display: flex;
                  }

                  .signatures-container p span {
                     margin: 0px 10px;
                     width: 100%;
                     align-items: center;
                     display: flex;
                     justify-content: center;
                  }

                  .container-box {
                     font-size: 16px;
                     margin: 40px 0;
                  }

                  .header-container p {
                     margin-top: 20px;
                  }

                  table th {
                     text-align: center !important;
                     vertical-align: inherit !important;
                  }

                  table {
                     width: 96%;
                     margin-top: 10px;
                  }

                  .student-performance {
                     font-weight: bold;
                  }

                  .student-performance p {
                     margin-top: 10px;
                  }

                  table th,
                  table td {
                     border: 2px solid #262625;
                     padding: 4px 10px;
                  }

                  .learning-objectives table {
                     margin-top: 0px;
                  }

                  h4 {
                     text-align: center;
                     margin-bottom: 20px;
                  }

                  * {
                     color: #262625 !important;
                  }

                  .header-student-info {
                     padding: 10px 10px 0 10px;
                     width: 94%;
                     text-align: justify;
                     font-weight: bold;
                     margin-top: 100px;
                     border: 2px solid #262625;
                     height: 30px;
                  }

                  .learning-objectives table thead {
                     background-color: #C6D9F1;
                  }
               </style>

               <script>
                  function imprimirPagina() {
                     window.print();
                  }
               </script>
