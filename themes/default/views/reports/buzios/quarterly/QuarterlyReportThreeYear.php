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
        <?php $this->renderPartial('buzios/headers/headBuziosII'); ?>
    </div>
    <h4><?php echo Yii::t('default', 'Quarterly Report') . ' - ' . $current_year ?></h3>
        <div class="container-box header-container"  style="margin-bottom: 70px;">
            <p>Unidade Escolar: <?php echo $school->name ?></p>
            <p>Professor(a) Regente 1: _________________________________________________________________________________________________________________________________</p>
            <p>Professor(a) Regente 2: _________________________________________________________________________________________________________________________________</p>
            <p>
                <span class="pull-left">Ano de escolaridade: <?php echo $current_year ?></span>
                <div class="pull-right" style="margin-right: 40px;">
                    <span style="margin-right: 300px;">Turno: <?php echo $turno ?></span>
                    <span>Turma: <?php echo $classroom->name ?></span>
                </div>
            </p>
            <div class="container-box header-student-info" style="margin-top: 60px">
                <span class="pull-left">Aluno (a): <?php echo $student_identification->name ?></span>
                <div class="pull-right" style="margin-right: 10px;">
                    <span>Turma: <?php echo $classroom->name ?></span>
                    <span style="margin-left:20px">Ano letivo: <?php echo $current_year ?></span>
                </div>
            </div>
        </div>
        <div class="container-box annual-summary">
            <table aria-labelledby="Three Year">
                <thead>
                    <tr>
                        <th scope="col" colspan="5">Resumo Anual</th>
                    </tr>
                    <tr>
                        <th scope="col">Total de dias letivos</th>
                        <th scope="col">Total de aulas dadas</th>
                        <th scope="col">Total de carga horária</th>
                        <th scope="col">Total de faltas</th>
                        <th scope="col">% de frequência anual</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="background-color: white;height:20px"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container-box learning-objectives">
            <!-- 1º ANO -->
            <div class="first-year-container">
                <!-- PAGINA 1 -->
                <h4><u>OBJETIVOS DE APRENDIZAGEM - LÍNGUA PORTUGUESA - 3º ANO</u></h3>
                    <table aria-labelledby="Three Year">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70%;"><u>Eixo: Análise Linguística</u></th>
                                <th scope="col"><u>1º tri</u></th>
                                <th scope="col"><u>2º tri</u></th>
                                <th scope="col"><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1. Conhece a ordem alfabética e seus usos em diferentes contextos.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2. Escreve palavras, frases, textos curtos nas formas impressa e cursiva, alfabeticamente.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3. Lê com entonação, ritmo e fluência.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4. Compreende e faz uso das convenções gráficas: orientação, alinhamento e segmentação.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5. Identifica e faz uso de letras maiúsculas e minúsculas nos textos produzidos, segundo convenções.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>6. Conhece e faz uso das grafias de palavras, de modo a escrever ortograficamente.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>7. Reconhece e emprega corretamente os sinais gráficos e de pontuação.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table aria-labelledby="Three Year">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70%;"><u>Eixo: Leitura/Escuta</u></th>
                                <th scope="col"><u>1º tri</u></th>
                                <th scope="col"><u>2º tri</u></th>
                                <th scope="col"><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>8. Lê e compreende, com certa autonomia, textos de gêneros variados, desenvolvendo o gosto pela leitura.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>9. Localiza informações explícitas em textos de diferentes gêneros, temáticas, lidos com autonomia.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>10. Realiza inferências em textos de diferentes gêneros, lidos com autonomia ou por outro leitor.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>11. Interpreta frases e expressões em textos de diferentes gêneros, lidos com autonomia.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>12. Reconhece finalidades de textos lidos e apreende os assuntos tratados neles.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table aria-labelledby="Three Year">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70%;"><u>Eixo: Oralidade</u></th>
                                <th scope="col"><u>1º tri</u></th>
                                <th scope="col"><u>2º tri</u></th>
                                <th scope="col"><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>13. Relata, com coerência, experiências vividas, usando palavras ou expressões que estabeleçam a coesão, como: progressão de tempo, marcação do espaço e relações de causalidade.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>14. Planeja e participa de interações orais em sala de aula, questionando, sugerindo, argumentando e respeitando os turnos de fala.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table aria-labelledby="Three Year">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70%;"><u>Eixo: Produção Escrita</u></th>
                                <th scope="col"><u>1º tri</u></th>
                                <th scope="col"><u>2º tri</u></th>
                                <th scope="col"><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>15. Escreve, espontaneamente ou por ditado (e autoditado), palavras e frases de forma alfabética – usando letras/grafemas que representem fonemas.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>16. Planeja e produz textos de diferentes gêneros para atender diferentes finalidades.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
            </div>
            <!-- PAGINA 2 -->
            <div class="container-box header-student-info" style="margin-top: 400px;">
                <span class="pull-left">Aluno (a): <?php echo $student_identification->name ?></span>
                <div class="pull-right" style="margin-right: 10px;">
                    <span>Turma: <?php echo $classroom->name ?></span>
                    <span style="margin-left:20px">Ano letivo: <?php echo $current_year ?></span>
                </div>
            </div>
            <h4><u>OBJETIVOS DE APRENDIZAGEM - HISTÓRIA - 3º ANO</u></h3>
                <table aria-labelledby="Three Year">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 70%;"><u>Eixo: Linguagem e procedimentos de pesquisa</u></th>
                            <th scope="col"><u>1º tri</u></th>
                            <th scope="col"><u>2º tri</u></th>
                            <th scope="col"><u>3º tri</u></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1. Compara diferentes registros de memória e da história da família e/ou da comunidade, registradas em diferentes fontes, relacionando os acontecimentos do passado e do presente, identificando o papel de diferentes grupos.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2. Produz fontes de memória (desenhos, relatos escritos, fotografias, dentre outras possibilidades) que poderão contribuir com a construção da história da comunidade.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3. Utiliza diferentes instrumentos de organização e contagem do tempo das pessoas (calendários, relógios, agendas, quadro de horários).</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>4. Compreende a existência dos marcos e patrimônios históricos e culturais de sua cidade, conhecendo-os e registrando-os a partir de múltiplas linguagens.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>5. Identifica os grupos populacionais que formam a cidade, o município e a região (em especial culturas africanas, indígenas e de migrantes) as relações estabelecidas entre eles e os eventos que marcam a formação da cidade, como fenômenos migratórios (vida rural e urbana), desmatamentos, estabelecimentos de grandes empresas, etc.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <table aria-labelledby="Three Year">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 70%;"><u>Eixo: Conhecimentos históricos</u></th>
                            <th scope="col"><u>1º tri</u></th>
                            <th scope="col"><u>2º tri</u></th>
                            <th scope="col"><u>3º tri</u></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>6. Identifica as diferentes atividades realizadas na cidade e no campo (considerando o uso da tecnologia nesses diferentes contextos) para fins de produção, comércio, cultura, educação e lazer, comparando-as com as de outros tempos e espaços.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>7. Compreende transformações em espaços da comunidade.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>8. Identifica as diferenças entre, o espaço doméstico, os espaços públicos e as áreas de conservação ambiental, compreendendo a importância dessa distinção.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <h4 style="margin-top: 10px;"><u>OBJETIVOS DE APRENDIZAGEM - GEOGRAFIA - 3º ANO</u></h3>
                    <table aria-labelledby="Three Year">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70%;"><u>Eixo: O sujeito e seu lugar no mundo</u></th>
                                <th scope="col"><u>1º tri</u></th>
                                <th scope="col"><u>2º tri</u></th>
                                <th scope="col"><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1. Conhece e valoriza as relações entre as pessoas e o lugar: os elementos da cultura, os papéis sociais, as relações afetivas e de identidade com o lugar onde vivem, bem como as mudanças ao longo do tempo.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2. Conhece a formação natural, cultural e histórica e identifica as principais características geográficas do município onde estão situados lugares e grupos de sua vivência.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3. Identifica em seu cotidiano, elementos geográficos e suas mudanças ao longo do tempo, associando mudança de vestiário e de hábitos alimentares em sua comunidade ao longo do ano, decorrentes da variação de temperatura e umidade do ambiente.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4. Compara diferentes meios de transporte e de comunicação, indicando o seu papel na conexão entre lugares, e discute os riscos para a vida e para o ambiente e seu uso responsável.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table aria-labelledby="Three Year">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70%;"><u>Eixo: Mundo do Trabalho</u></th>
                                <th scope="col"><u>1º tri</u></th>
                                <th scope="col"><u>2º tri</u></th>
                                <th scope="col"><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>5. Relaciona o dia e a noite a diferentes tipos de atividades sociais (horário escolar, comercial, sono, de trabalho, etc).</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table aria-labelledby="Three Year">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70%;"><u>Eixo: Natureza, ambiente e qualidade de vida</u></th>
                                <th scope="col"><u>1º tri</u></th>
                                <th scope="col"><u>2º tri</u></th>
                                <th scope="col"><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>6. Identifica razões e os processos pelos quais os grupos locais, a sociedade, os processos naturais e históricos transformam a natureza ao longo do tempo.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>7. Respeita e promove regras de convívio social e ambiental, exercitando cuidados com o outro e com os espaços coletivos.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>8. Descreve as características da paisagem local, comparando-as com outras paisagens.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>9. Relaciona a produção de lixo doméstico ou da escola aos problemas causados pelo consumo excessivo e constrói propostas para o consumo consciente, considerando a ampliação de hábitos de redução, reuso e reciclagem/descarte de materiais consumidos em casa, na escola e/ou no entorno.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table aria-labelledby="Three Year">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70%;"><u>Eixo: Formas de representação e pensamento espacial</u></th>
                                <th scope="col"><u>1º tri</u></th>
                                <th scope="col"><u>2º tri</u></th>
                                <th scope="col"><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10. Constrói e utiliza referenciais espaciais para localizar elementos do local de vivência, considerando referenciais espaciais (frente e atrás, esquerda e direita, em cima e embaixo, dentro e fora) e tendo o corpo como referência.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>11. Identifica e elabora diferentes formas de representação (desenhos, mapas mentais, maquetes) para representar componentes da paisagem dos lugares de vivência de modo a deslocar-se com autonomia.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>12. Identifica objetos e lugares de vivência (escola e moradia) em imagens aéreas e mapas (visão vertical) e fotografias (visão oblíqua).</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- PAGINA 3 -->
                    <div class="container-box header-student-info" style="margin-top: 300px;">
                        <span class="pull-left">Aluno (a): <?php echo $student_identification->name ?></span>
                        <div class="pull-right" style="margin-right: 10px;">
                            <span>Turma: <?php echo $classroom->name ?></span>
                            <span style="margin-left:20px">Ano letivo: <?php echo $current_year ?></span>
                        </div>
                    </div>
                    <h4><u>OBJETIVOS DE APRENDIZAGEM - MATEMÁTICA - 3º ANO</u></h3>
                        <table aria-labelledby="Three Year">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 70%;"><u>Eixo: Números e operações</u></th>
                                    <th scope="col"><u>1º tri</u></th>
                                    <th scope="col"><u>2º tri</u></th>
                                    <th scope="col"><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1. Identifica números nos diferentes contextos em que se encontram, em diferentes funções.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2. Compara números naturais em situações cotidianas, com e sem suporte da reta numérica.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>3. Lê, escreve e compara números naturais até a ordem de unidade de milhar, estabelecendo relações entre os registros numéricos e em língua materna.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>4. Realiza agrupamentos de base decimal: unidades, dezenas e centenas.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>5. Identifica características do sistema de numeração decimal, utilizando a composição e a decomposição de número natural de até quatro ordens.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>6. Resolve e elabora problemas de adição e subtração envolvendo os significados de juntar, acrescentar, separar, retirar, comparar e completar quantidades, em situações de contexto familiar e utilizando o cálculo mental, estratégias ou outras formas de registro pessoal.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>7. Resolve e elabora problemas de multiplicação com os significados de adição de parcelas iguais e elementos apresentados em disposição retangular, utilizando diferentes estratégias de cálculo e registro.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table aria-labelledby="Three Year">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 70%;"><u>Eixo: Geometria</u></th>
                                    <th scope="col"><u>1º tri</u></th>
                                    <th scope="col"><u>2º tri</u></th>
                                    <th scope="col"><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>8. Descreve a localização de pessoas e de objetos no espaço segundo um dado ponto de referência, compreendendo que, para a utilização de termos que se referem à posição, como direita, esquerda, em cima, embaixo, é necessário explicitar-se o referencial.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>9. Reconhece, nomeia e compara figuras geométricas espaciais (cubo, bloco retangular, pirâmide, cone, cilindro e esfera), relacionando-as com objetos do mundo físico e associando prismas e pirâmides a suas planificações.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table aria-labelledby="Three Year">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 70%;"><u>Eixo: Grandezas e Medidas</u></th>
                                    <th scope="col"><u>1º tri</u></th>
                                    <th scope="col"><u>2º tri</u></th>
                                    <th scope="col"><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>10. Estima, medi e compara capacidade, massa e comprimento, utilizando estratégias pessoais e unidades de medida não padronizadas ou padronizadas (litro, mililitro, grama, quilograma, metro e centímetro).</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>11. Identifica e ordena períodos do dia, dias da semana, meses do ano, datas e relações entre esses períodos utilizando diferentes instrumentos de medida de tempo.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>12. Lê, identifica, compara e registra horas (hora, meia hora e quarto de hora) e duração de eventos (horário de início e fim) em relógios analógicos e digitais.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>13. Resolve e elabora problemas que envolvam a comparação e a equivalência de valores monetários do sistema brasileiro em situações de compra, venda e troca.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table aria-labelledby="Three Year">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 70%;"><u>Eixo: Probalidade e Estatística</u></th>
                                    <th scope="col"><u>1º tri</u></th>
                                    <th scope="col"><u>2º tri</u></th>
                                    <th scope="col"><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>14. Lê, interpreta e compara dados e informações apresentadas em tabelas de dupla entrada, gráficos de barras ou de colunas, envolvendo resultados de pesquisas significativas, utilizando termos como maior e menor frequência, apropriando-se desse tipo de linguagem para compreender aspectos da realidade sociocultural significativos.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>15. Resolve problemas cujos dados estão apresentados em tabelas e gráficos.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table aria-labelledby="Three Year">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 70%;"><u>Eixo: Pensamento Algébrico</u></th>
                                    <th scope="col"><u>1º tri</u></th>
                                    <th scope="col"><u>2º tri</u></th>
                                    <th scope="col"><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>16. Constrói sequências de números naturais em ordem crescente ou decrescente a partir de um número qualquer, utilizando uma regularidade estabelecida.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>17. Identifica regularidades em sequências ordenadas de números naturais, resultantes da realização de adições ou subtrações sucessivas, por um mesmo número, descrevendo uma regra de formação da sequência e determinando elementos faltantes ou seguintes.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <h4 style="margin-top: 10px;"><u>OBJETIVOS DE APRENDIZAGEM - CIÊNCIAS - 3º ANO</u></h3>
                            <table aria-labelledby="Three Year">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 70%;"><u>Eixo: Matéria e Energia</u></th>
                                        <th scope="col"><u>1º tri</u></th>
                                        <th scope="col"><u>2º tri</u></th>
                                        <th scope="col"><u>3º tri</u></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1. Participa de situações em que os conceitos e procedimentos científicos são mobilizados para tomar decisões acerca de situações sociais e atuais relevantes, como reaproveitamento de materiais e alimentos.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2. Percebe o impacto da ciência e da tecnologia na vida das pessoas, comunicando oralmente ou por meio de registro gráfico.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3. Lê textos simples de divulgação científica, formulando perguntas e levantando suposições sobre curiosidades da ciência e da tecnologia.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>4. Reconhece e identifica em diferentes ambientes os recursos naturais disponíveis e o uso que se faz deles.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5. Interpreta imagens e elabora textos escritos sobre o ambiente e os recursos naturais.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table aria-labelledby="Three Year">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 70%;"><u>Eixo: Vida e Evolução</u></th>
                                        <th scope="col"><u>1º tri</u></th>
                                        <th scope="col"><u>2º tri</u></th>
                                        <th scope="col"><u>3º tri</u></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>6. Reconhece e compreende a alimentação saudável e a higiene pessoal como elementos fundamentais para o crescimento e desenvolvimento satisfatório do corpo.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>7. Questiona hábitos alimentares e atividades físicas, relacionando-os à saúde.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>8. Comunica oralmente e/ou por meio de registros, medidas de prevenção às doenças causadas pela falta ou deficiência de saneamento, coletadas por meio de leituras e observações.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table aria-labelledby="Three Year">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 70%;"><u>Eixo: Terra e Universo</u></th>
                                        <th scope="col"><u>1º tri</u></th>
                                        <th scope="col"><u>2º tri</u></th>
                                        <th scope="col"><u>3º tri</u></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>9. Identifica características da Terra (como seu formato esférico, a presença de água, solo, etc.), com base na observação, manipulação e comparação de diferentes formas de representação do planeta (mapas, globos, fotografias, etc.).</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>10. Identiﬁca e nomeia diferentes escalas de tempo: os períodos diários (manhã, tarde, noite) e a sucessão de dias, semanas, meses e anos.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>11. Utiliza quadros ou tabelas para sistematizar resultados de um levantamento de dados.</td>
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
                            <!-- PAGINA 4 -->
                            <div class="container-box header-student-info" style="margin-top: 1500px;">
                                <span class="pull-left">Aluno (a): <?php echo $student_identification->name ?></span>
                                <div class="pull-right" style="margin-right: 10px;">
                                    <span>Turma: <?php echo $classroom->name ?></span>
                                    <span style="margin-left:20px">Ano letivo: <?php echo $current_year ?></span>
                                </div>
                            </div>
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
                                        <span style="margin-left: -15px;">Profº Supervisor(a) Escolar</span>
                                        <span>Profº Orientador(a) Educacional</span>
                                    </p>
                                    <p style="margin-top: 30px;">
                                        <span>Assinatura do Responsável:</span>
                                        <span>________________________________________________________________________________________________________________
                                        </span>
                                    </p>
                                </div>
                                <p style="margin-top: 0px;"><u>Espaço destinado a descrição do aluno durante o trimestre</u></p>
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
                                        <span style="margin-left: -15px;">Profº Supervisor(a) Escolar</span>
                                        <span>Profº Orientador(a) Educacional</span>
                                    </p>
                                    <p style="margin-top: 30px;">
                                        <span>Assinatura do Responsável:</span>
                                        <span>________________________________________________________________________________________________________________
                                        </span>
                                    </p>
                                </div>
                                <p style="margin-top: 0px;"><u>Espaço destinado a descrição do aluno durante o trimestre</u></p>
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
                                        <span style="margin-left: -15px;">Profº Supervisor(a) Escolar</span>
                                        <span>Profº Orientador(a) Educacional</span>
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