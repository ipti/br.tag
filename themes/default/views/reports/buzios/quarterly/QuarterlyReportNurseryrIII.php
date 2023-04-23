<?php
    /* @var $this ReportsController */
    /* @var $report mixed */
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/reports/QuarterlyReport/_initialization.js', CClientScript::POS_END);
    
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
    
    $turno =  $classroom->turn;
    if ($turno == 'M') {
        $turno = "Matutino";
    } else if ($turno == 'T') {
        $turno = "Tarde";
    } else if ($turno == 'N') {
        $turno = "Noite";
    } else if ($turno == '' || $turno == null) {
        $turno = "______________________";
    }
?>
<div class="pageA4H page" style="height: auto;">
<div class="cabecalho" style="margin: 30px 0;">
    <?php $this->renderPartial('buzios/headers/headBuziosIII'); ?>
</div>
<h4>
<?php echo Yii::t('default', 'Quarterly Report') . ' - ' . $current_year ?></h3>
<div class="container-box header-container">
    <p>Unidade Escolar: <?php echo $school->name ?></p>
    <p>Professor(a) Regente 1: _________________________________________________________________________________________________________________________________</p>
    <p>Professor(a) Regente 2: _________________________________________________________________________________________________________________________________</p>
    <p>
        <span>Etapa: <?php echo $classroom_etapa ? $classroom_etapa->name : '______________________' ?></span>
        <span style="margin-left: 100px;">Turno: <?php echo $turno ?></span>
        <span style="margin-left: 230px;">Turma: <?php echo $classroom->name ?></span>
    </p>
    <p>
        <span>Nome do Aluno (a): <?php echo $student_identification->name ?></span>
        <span style="margin-left: 450px;">Data de Nascimento: <?php echo $dateFormatCorrect ? $student_identification->birthday : date('d/m/Y', strtotime($student_identification->birthday));?></span>
    </p>
</div>
<div class="container-box learning-objectives">
<div class="first-year-container">
    <!-- PAGINA 1 -->
    <h4>
    <u>OBJETIVOS DE APRENDIZAGEM - CRECHE III</u></h3>
    <table aria-labelledby="creche table">
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
                <td>Interage com outras crianças e adultos adaptando-se ao convívio social e gradativamente respeitando regras básicas nas interações, jogos e brincadeiras</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Resolve conflitos nas interações e brincadeiras, com a orientação de um adulto.</td>
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
            <tr>
                <td>Compartilha os objetos e espaços ampliando as relações interpessoais com crianças e adultos, desenvolvendo atitudes de participação, conservação e cooperação.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table aria-labelledby="creche table">
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
            <tr>
                <td>Utiliza materiais variados com possibilidades de manipulação (argila, massa de modelar, entre outros), explorando cores, texturas, superfícies, planos, formas e volumes ao criar objetos tridimensionais.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table aria-labelledby="creche table">
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
                <td>Desloca seu corpo no espaço (orientando-se por noções como em frente, atrás, no alto, embaixo, dentro, fora, entre outros) e se envolve em brincadeiras e atividades de diferentes naturezas.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Explora formas de deslocamento no espaço (pular, saltar, dançar, entre outros), combinando movimentos e seguindo orientações.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Desenvolve progressivamente as habilidades manuais, adquirindo controle para desenhar, pintar, rasgar, folhear, entre outros.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Percebe sua dominância lateral em ações habituais e brincadeiras.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Demonstra progressiva independência no cuidado do seu corpo.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table aria-labelledby="creche table">
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
                <td>Desenvolve noções matemáticas de altura, largura, comprimento, tamanho, peso, volume, distância, temperatura e tempo.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Explora e descreve semelhanças e diferenças entre as características e propriedades dos objetos (textura, massa, tamanho, entre outros).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Participa de experimentos, observações, pesquisas e outros procedimentos científicos para a ampliação dos conhecimentos e do vocabulário.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table aria-labelledby="creche table">
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
                <td>Utiliza diferentes linguagens para comunicar-se e expressar-se (EX: gestos, expressões faciais, fala, entre outros)</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Atende quando chamado pelo nome e sobrenome.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Realiza leituras por meio de gravuras, imagens, ilustrações, entre outros.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Formula e responde perguntas sobre fatos da história narrada, identificando cenários, personagens e principais acontecimentos.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Desenvolve gradativamente a ideia de representação por meio da produção de rabiscos e garatujas na realização de tentativas de escritas não convencionais.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Desenvolve a capacidade de lembrar e executar ações em passos sequenciais, seguindo instruções verbais desde ordens simples até as mais longas.</td>
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
            <tr>
                <td>Reconta histórias.</td>
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
    <div class="container-box student-performance" style="margin-top: 1000px;">
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
</div>

<style>
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