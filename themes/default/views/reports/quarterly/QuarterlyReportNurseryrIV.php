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
    <!-- CHERCHE IV -->
    <div class="cabecalho" style="margin: 30px 0;">
        <?php $this->renderPartial('headBuziosIII'); ?>
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
            <span style="margin-left: 450px;">Data de Nascimento: <?php echo date('d/m/Y', strtotime($student_identification->birthday));?></span>
        </p>
    </div>
    <div class="container-box learning-objectives">
        <div class="first-year-container">
            <!-- PAGINA 1 -->
            <h4>
            <u>OBJETIVOS DE APRENDIZAGEM - CRECHE IV</u></h3>
            <table aria-labelledby="Creche Table">"
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
                        <td>Comunica necessidades, desejos e emoções, utilizando palavras.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Desenvolve o controle progressivo de suas necessidades fisiológicas (esfincterianas, alimentares, do sono, entre outros).</td>
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
                        <td>Percebe que as pessoas têm características físicas diferentes, respeitando essas diferenças, gradativamente, demonstrando valorização de suas próprias características.</td>
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
                    <tr>
                        <td>Desenvolve gradativamente a atenção em momentos de escuta, da argumentação e do posicionamento dos pares.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table aria-labelledby="Creche Table">
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
                        <td>Traça marcas gráficas, em diferentes suportes, usando instrumentos riscantes e tintas</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Explora diferentes fontes sonoras e materiais para acompanhar brincadeiras cantadas, canções, músicas e melodias</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Cria sons com materiais, objetos e instrumentos musicais, para acompanhar diversos ritmos de músicas.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Utiliza materiais variados com possibilidades de manipulação (argila, massa de modelar, entre outros), explorando cores, texturas, superfícies, planos, formas e volumes ao criar objetos tridimensionais</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table aria-labelledby="Creche Table">
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
                        <td>Percebe o próprio corpo e como ele se movimenta e se expressa</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Utiliza os movimentos de preensão, encaixe e lançamento, ampliando suas possibilidades de manuseio de diferentes materiais e objetos</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Desloca seu corpo no espaço (orientando-se por noções como em frente, atrás, no alto, embaixo, dentro, fora, entre outros) ao se envolver em brincadeiras e atividades diferentes de diferentes naturezas.</td>
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
                        <td>Demonstra progressiva independência no cuidado do seu corpo.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Desenvolve gradativamente a coordenação motora fina para manusear objetos de registro, pintura e recorte, tais como: lápis, pincel, tesoura, entre outros.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Identifica as partes do corpo e suas funções.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table aria-labelledby="Creche Table">
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
                        <td>Desenvolve noções de orientação espacial por meio de experiências de deslocamento de si e dos objetos, explorando direção e posição no espaço.</td>
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
                        <td>Explora e descreve, semelhanças e diferenças, entre as características e propriedades dos objetos (textura, massa, tamanho, entre outros)</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Percebe e identifica os elementos da natureza (água, luz, solo, ar, entre outros), nomeando-os e relacionando-os aos seres vivos.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Estabelece e identifica reações espaciais (dentro e fora, em cima e embaixo, acima e abaixo, entre e ao lado) e temporais (antes, durante e depois).</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Identifica formas/figuras geométricas no cotidiano, por meio da observação e manipulação de objetos, elementos da natureza, sólidos e planos (triângulo, retângulo, círculo e quadrado).</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Reconhece e organiza objetos por critérios de semelhanças e diferenças, agrupando-os numa categoria (pensamento classificatório).</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Identifica e nomeia os números, gradativamente em diferentes portadores de textos da vida cotidiana.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table aria-labelledby="Creche Table">
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
                        <td>Desenvolve a capacidade de lembrar e executar ações em passos sequenciais, seguindo instruções verbais desde ordens simples até as mais longas</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Conhece e manipula diferentes instrumentos e suportes de escrita para desenhar, traçar letras e outros sinais gráficos.</td>
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
                        <td>Transmite avisos e recados de maneira clara ao receptor.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Reconta histórias ouvidas para produção de reconto escrito, tendo professor como escriba.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Reconta histórias em sequência lógica.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table style="margin-top: 5px;">
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
            <div class="container-box student-performance" style="margin-top: 500px;">
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