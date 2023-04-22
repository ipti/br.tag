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
<!-- PRE II -->
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
    <u>OBJETIVOS DE APRENDIZAGEM - PRÉ II</u></h3>
    <table aria-labelledby="Pre Table">
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
                <td>Comunica necessidades, desejos e emoções oralmente.</td>
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
                <td>Realiza pequenas tarefas do cotidiano que envolvam ações de cooperação, solidariedade e ajuda na relação com o meio ambiente.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Percebe que as pessoas têm características físicas diferentes, respeitando essas diferenças e demonstrando valorização de suas próprias características.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Compartilha os objetos e espaços ampliando as relações interpessoais com crianças e adultos, desenvolvendo atitudes de participação, conservação e cooperação./td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Manifesta interesse e respeito por diferentes culturas e modos de vida.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Demonstra atitudes de cuidado e solidariedade na interação com crianças e adultos, bem como, respeito ao meio ambiente.</td>
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
            <tr>
                <td>Usa estratégias pautadas no respeito mútuo para lidar com conflitos nas interações com crianças e adultos.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table aria-labelledby="Pre Table">
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
                <td>Utiliza materiais variados com possibilidades de manipulação (argila, massa de modelar, entre outros), explorando cores, texturas, superfícies, planos, formas e volumes ao criar objetos tridimensionais.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Traça marcas gráficas, em diferentes suportes, usando instrumentos riscantes e tintas.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Cria sons com materiais, objetos e instrumentos musicais, para acompanhar diversos ritmos de música.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Expressa-se livremente por meio de desenho, pintura, colagem, dobradura e escultura, criando produções bidimensionais e tridimensionais.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Identifica cores primárias.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Identifica cores secundárias.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table aria-labelledby="Pre Table">
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
                <td>Utiliza os movimentos de preensão, encaixe e lançamento, ampliando suas possibilidades de manuseio de diferentes materiais e objetos.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Demonstra progressivo desenvolvimento da coordenação motora global.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Desloca seu corpo no espaço, orientando-se por noções como: em frente, atrás, no alto, embaixo, dentro, fora, entre outros, ao se envolver em brincadeiras e atividades de diferentes naturezas.</td>
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
                <td>Desenvolve progressivamente as habilidades manuais, adquirindo controle para desenhar, pintar, rasgar, folhear, entre outros.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Reelabora as brincadeiras e jogos, incluindo a criação de outros gestos, em substituição e acréscimo aos tradicionais.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Conhece as partes do corpo de modo a adquirir consciência de suas potencialidades (força, velocidade, resistência, agilidade, equilíbrio e flexibilidade).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Desenvolve gradativamente a coordenação motora fina para manusear objetos de registro, pintura e recorte, tais como: lápis, pincel, tesoura, entre outros</td>
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
                <td>Demonstra controle e adequação do uso de seu corpo em brincadeiras e jogos.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Apresenta noções de lateralidade (direita e esquerda).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table aria-labelledby="Pre Table">
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
                <td>Observar, relatar e descrever incidentes do cotidiano e fenômenos naturais (luz solar, vento, chuva, entre outros).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Identificar e representar quantidades através da linguagem verbal (objetos, desenhos, entre outros).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Apresenta conceitos básicos de tempo (agora, antes, durante, depois, ontem, hoje, amanhã, dia e noite).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Identifica relações espaciais e temporais (antes, durante e depois).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Apresenta noções de orientação espacial por meio de experiências de deslocamento de si e dos objetos, explorando direção e posição no espaço.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Explicita e representa espaços, trajetos e localização, utilizando múltiplas linguagens e em diferentes suportes.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Classifica e seria três ou mais objetos, posicionando-os do menor para o maior, do mais alto para o mais baixo, do mais largo para o menos largo ou vice-versa.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Nomeia e identifica conceitos matemáticos de altura, largura, comprimento, tamanho, peso, volume, distância, temperatura e tempo.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Compara e mede diversos objetos, espaços e pessoas utilizando instrumentos de medida não convencionais.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Registra observações, manipulações e medidas usando múltiplas linguagens (desenhos, registro por números ou escrita espontânea).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Identifica e marca a passagem do tempo, destacando datas importantes e eventos por meio da utilização de calendários.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Identifica formas/figuras geométricas no cotidiano (triângulo, retângulo, círculo e quadrado).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Reconhece números nos diferentes contextos em que se encontram, diferenciando-os de outras marcas gráficas.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Realiza contagem oral de 01 a 30.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Identifica e nomeia os números em diferentes portadores de textos da vida cotidiana.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Relaciona o número falado e escrito à quantidade que ele representa, registrando-o</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Identifica o antes, o entre e o depois em uma sequência numérica.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Percebe o significado das operações de adição envolvendo ideia de juntar e acrescentar e de subtração com ideia de retirar em situações de rotina.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Compreende a função social do dinheiro em situações de vivência de manipulação.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Participa na construção de listas, tabelas e gráficos (pictóricos e corporais).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Analisa oralmente listas, tabelas e gráficos (pictóricos e corporais).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Distingue paisagens naturais de modificadas (pela ação humana ou pela ação da natureza).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Percebe e identifica os elementos da natureza, tais como: água, luz, solo, ar, entre outros, nomeando-os e relacionando-os aos seres vivos</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Reconhece a importância dos cuidados necessários com o planeta, plantas e animais.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table aria-labelledby="Pre Table">
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
                <td>Realiza leituras por meio de gravuras, imagens, ilustrações, entre outros.</td>
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
                <td>Desenha livremente representando intenções comunicativas.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Expressa ideias, desejos e sentimentos sobre suas vivências, por meio da linguagem oral e escrita (escrita espontânea), de fotos, desenhos e outras formas de expressão</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Transmite avisos e recados de maneira clara ao receptor</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Selecionar e manusear livros (entre outros recursos gráficos).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Reconta histórias ouvidas para a produção de texto escrito, endo o professor como escriba.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Relata experiências, fatos, acontecidos e histórias ouvidas, em sequência temporal e causal.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Reconhece seu próprio nome em diferentes suportes.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Reconhece o nome de alguns colegas com o apoio de recursos visuais</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Reconhece a orientação e direção da escrita ocidental (esquerda para direita, cima para baixo).</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Reconhece a existência de textos escritos e sua função social.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Participar de interações orais, questionando, sugerindo e respeitando os turnos de fala.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Escreve seu próprio nome sem apoio de recurso visual.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Diferencia letras, números e símbolos.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Conhece o alfabeto associando-o a nomes familiares.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Realiza leituras por meio de gravuras, imagens, ilustrações, entre outros</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Produzir textos coletivos tendo o adulto como escriba.</td>
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