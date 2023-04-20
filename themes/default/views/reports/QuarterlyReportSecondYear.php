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
        <?php $this->renderPartial('headBuzios'); ?>
    </div>
    <h4><?php echo Yii::t('default', 'Quarterly Report') . ' - ' . $current_year ?></h3>
        <div class="container-box header-container">
            <p>Unidade Escolar: <?php echo $school->name ?></p>
            <p>Professor(a) Regente 1: _________________________________________________________________________________________________________________________________</p>
            <p>Professor(a) Regente 2: _________________________________________________________________________________________________________________________________</p>
            <p>
                <span>Ano de escolaridade: <?php echo $current_year ?></span>
                <span style="margin-left: 250px;">Turno: <?php echo $turno ?></span>
                <span style="margin-left: 230px;">Turma: <?php echo $classroom->name ?></span>
            </p>
        </div>
        <div class="container-box annual-summary">
            <table>
                <thead>
                    <tr>
                        <th colspan="5">Resumo Anual</th>
                    </tr>
                    <tr>
                        <th>Total de dias letivos</th>
                        <th>Total de aulas dadas</th>
                        <th>Total de carga horária</th>
                        <th>Total de faltas</th>
                        <th>% de frequência anual</th>
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
                <h4><u>OBJETIVOS DE APRENDIZAGEM - LÍNGUA PORTUGUESA - 2º ANO</u></h3>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;"><u>Eixo: Análise Linguística</u></th>
                                <th><u>1º tri</u></th>
                                <th><u>2º tri</u></th>
                                <th><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1. Escreve o próprio nome e o sobrenome, utilizando-o como referência para escrever e ler outras palavras.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2. Reconhece e nomeia letras do alfabeto, distinguindo-as de outros sinais gráficos.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3. Compara palavras, identificando semelhanças e diferenças entre sons de sílabas iniciais, mediais e finais.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4. Conhece, diferencia e relaciona letras em formato impressa e cursiva, maiúsculas e minúsculas.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5. Realiza análise fonológica de palavras, segmentando-as oralmente em unidades menores (partes de palavras e/ou sílabas), identificando rimas e aliterações.</td>
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
                                <td>7. Compreende e faz uso das convenções gráficas: orientação, alinhamento e segmentação.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;"><u>Eixo: Leitura/Escuta</u></th>
                                <th><u>1º tri</u></th>
                                <th><u>2º tri</u></th>
                                <th><u>3º tri</u></th>
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
                                <td>10. Compreende textos de diferentes gêneros lidos pelo professor ou outro leitor experiente, considerando a situação comunicativa e o tema/assunto do texto.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>11. Reconhece finalidades de textos lidos e apreende os assuntos tratados neles.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;"><u>Eixo: Oralidade</u></th>
                                <th><u>1º tri</u></th>
                                <th><u>2º tri</u></th>
                                <th><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12. Reconta oralmente histórias com sequência lógica.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>13. Planeja e participa de interações orais em sala de aula, questionando, sugerindo, argumentando e respeitando os turnos de fala.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;"><u>Eixo: Produção Escrita</u></th>
                                <th><u>1º tri</u></th>
                                <th><u>2º tri</u></th>
                                <th><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>14. Escreve, espontaneamente ou por ditado (e autoditado), palavras e frases de forma alfabética – usando letras/grafemas que representem fonemas.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>15. Planeja e produz textos de diferentes gêneros para atender diferentes finalidades.</td>
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
                <span style="margin-right: 10px;margin-left: 40%">Turma: <?php echo $classroom->name ?></span>
                <span class="pull-right" style="margin-right: 10px;">Ano letivo: <?php echo $current_year ?></span>
            </div>
            <h4><u>OBJETIVOS DE APRENDIZAGEM - HISTÓRIA - 2º ANO</u></h3>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 70%;"><u>Eixo: Linguagem e procedimentos de pesquisa</u></th>
                            <th><u>1º tri</u></th>
                            <th><u>2º tri</u></th>
                            <th><u>3º tri</u></th>
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
                            <td>2. Produz fontes de memórias (desenho, relatos escritos, fotografias, dentre outras possibilidades) que poderão contribuir com a construção da história da comunidade.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>3. Identifica os grupos populacionais que formam a cidade, o município e a região (em especial culturas africanas, indígenas e de migrantes) as relações estabelecidas entre eles e os eventos que marcam a formação da cidade, como fenômenos migratórios (vida rural e urbana), desmatamentos, estabelecimentos de grandes empresas, etc.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>4. Identifica e organiza, temporalmente, fatos da vida cotidiana, usando noções relacionadas ao antes, durante, ao mesmo tempo e depois.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>5. Utiliza diferentes instrumentos de organização e contagem do tempo das pessoas (calendários, relógios, agendas, quadro de horários).</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 70%;"><u>Eixo: Conhecimentos históricos</u></th>
                            <th><u>1º tri</u></th>
                            <th><u>2º tri</u></th>
                            <th><u>3º tri</u></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>6. Identifica as diferenças entre os variados ambientes em que vive (doméstico, escolar e da comunidade), reconhecendo as especificidades dos hábitos e das regras que os regem.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>7. Percebe como transformações ocorridas na cidade e no campo, no passado, interferem nos modos de vida de seus habitantes no presente.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>8. Identifica e compara diferentes formas de trabalho na comunidade em que vive e os impactos causados no ambiente.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <h4 style="margin-top: 10px;"><u>OBJETIVOS DE APRENDIZAGEM - GEOGRAFIA - 2º ANO</u></h3>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;"><u>Eixo: O sujeito e seu lugar no mundo</u></th>
                                <th><u>1º tri</u></th>
                                <th><u>2º tri</u></th>
                                <th><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1. Reconhece-se como sujeito e como parte integrante dos lugares de vivências e dos diversos grupos sociais aos quais pertence, identificando semelhanças e diferenças entre esses lugares e grupos.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2. Conhece e valoriza as relações entre as pessoas e o lugar: os elementos da cultura, os papéis sociais, as relações afetivas e de identidade com o lugar onde vivem, bem como as mudanças ao longo do tempo.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3. Conhece a formação natural, cultural e histórica e identifica as principais características geográficas do município onde estão situados lugares e grupos de sua vivência.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4. Reconhece e identifica historicamente e atualmente a existência e importância dos grupos quilombola e indígena na identidade e cultura locais.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5. Identifica, em seu cotidiano, elementos geográficos e suas mudanças ao longo do tempo, associando mudança de vestiário e hábitos alimentares em sua comunidade ao longo do ano, decorrentes da variação de temperatura e umidade no ambiente.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;"><u>Eixo: Mundo do Trabalho</u></th>
                                <th><u>1º tri</u></th>
                                <th><u>2º tri</u></th>
                                <th><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>6. Descreve atividades de trabalho relacionadas com o dia a dia da sua comunidade.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>7. Relaciona o dia e a noite a diferentes tipos de atividades sociais (horário escolar, comercial, sono, de trabalho, etc).</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;"><u>Eixo: Natureza, ambiente e qualidade de vida</u></th>
                                <th><u>1º tri</u></th>
                                <th><u>2º tri</u></th>
                                <th><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>8. Identifica razões e os processos pelos quais os grupos locais, a sociedade, os processos naturais e históricos transformam a natureza ao longo do tempo.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>9. Respeita e promove regras de convívio social e ambiental, exercitando cuidados com o outro, com os espaços coletivos.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>10. Investiga os usos dos recursos naturais, com destaque para os usos da água em atividades cotidianas (alimentação, higiene, cultivo de plantas etc.), e discute os problemas ambientais provocados por esses usos.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;"><u>Eixo: Formas de representação e pensamento espacial</u></th>
                                <th><u>1º tri</u></th>
                                <th><u>2º tri</u></th>
                                <th><u>3º tri</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11. Constrói e utiliza referenciais espaciais para localizar elementos do local de vivência, considerando referenciais espaciais (frente e atrás, esquerda e direita, em cima e embaixo, dentro e fora) e tendo o corpo como referência.</td>
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
                        <span style="margin-right: 10px;margin-left: 40%">Turma: <?php echo $classroom->name ?></span>
                        <span class="pull-right" style="margin-right: 10px;">Ano letivo: <?php echo $current_year ?></span>
                    </div>
                    <h4><u>OBJETIVOS DE APRENDIZAGEM - MATEMÁTICA - 2º ANO</u></h3>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 70%;"><u>Eixo: Números e operações</u></th>
                                    <th><u>1º tri</u></th>
                                    <th><u>2º tri</u></th>
                                    <th><u>3º tri</u></th>
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
                                    <td>2. Associa a denominação de números à sua representação simbólica (do registro com algarismos ao registro com a Língua Materna e vice-versa).</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>3. Identifica relações em dúzia e meia dúzia, dezena e meia dezena, centena e meia centena.</td>
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
                                    <td>5. Resolve e elabora problemas de adição e subtração envolvendo os significados de juntar, acrescentar, separar, retirar, comparar e completar quantidades, em situações de contexto familiar e utilizando o cálculo mental, estratégias ou outras formas de registro pessoal.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 70%;"><u>Eixo: Geometria</u></th>
                                    <th><u>1º tri</u></th>
                                    <th><u>2º tri</u></th>
                                    <th><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>6. Identifica e descreve a localização e a movimentação de pessoas e objetos no espaço, identificando mudanças de direções e sentidos, considerando pontos de referência.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>7. Descreve a localização de pessoas e de objetos no espaço em relação à sua própria posição, utilizando termos como à direita, à esquerda, em frente, atrás.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>8. Reconhece, nomeia e compara figuras planas e espaciais por meio de características comuns, mesmo que apresentadas em diferentes posições.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 70%;"><u>Eixo: Grandezas e Medidas</u></th>
                                    <th><u>1º tri</u></th>
                                    <th><u>2º tri</u></th>
                                    <th><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>9. Identifica e ordena períodos do dia, dias da semana, meses do ano, datas e relações entre esses períodos utilizando diferentes instrumentos de medida de tempo.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>10. Lê horas (hora e meia hora) comparando relógios digitais e analógicos.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>11. Estabelece equivalência de valores entre moedas e cédulas do Sistema Monetário Brasileiro para resolver situações cotidianas.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 70%;"><u>Eixo: Probalidade e Estatística</u></th>
                                    <th><u>1º tri</u></th>
                                    <th><u>2º tri</u></th>
                                    <th><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>12. Lê, interpreta e transpõe informações simples em diferentes configurações (tipo: anúncios, gráficos, tabelas e propagandas).</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>13. Resolve problemas cujos dados estão apresentados em tabelas e gráficos de barras ou de colunas.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 70%;"><u>Eixo: Pensamento Algébrico</u></th>
                                    <th><u>1º tri</u></th>
                                    <th><u>2º tri</u></th>
                                    <th><u>3º tri</u></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>14. Descreve um padrão (ou regularidade) de sequências repetitivas, por meio de palavras, símbolos ou desenhos.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>15. Constrói sequências de números naturais em ordem crescente ou decrescente a partir de um número qualquer, utilizando uma regularidade estabelecida.</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <h4 style="margin-top: 10px;"><u>OBJETIVOS DE APRENDIZAGEM - CIÊNCIAS - 2º ANO</u></h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 70%;"><u>Eixo: Matéria e Energia</u></th>
                                        <th><u>1º tri</u></th>
                                        <th><u>2º tri</u></th>
                                        <th><u>3º tri</u></th>
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
                                        <td>4. Interpreta imagens e elabora textos escritos sobre o ambiente e os recursos naturais.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 70%;"><u>Eixo: Vida e Evolução</u></th>
                                        <th><u>1º tri</u></th>
                                        <th><u>2º tri</u></th>
                                        <th><u>3º tri</u></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>5. Compreende que os seres humanos se desenvolvem ao longo da vida, passando por fases com características próprias, identificando e descrevendo algumas transformações do corpo.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>6. Compara características físicas entre os colegas, reconhecendo a diversidade e a importância da valorização, do acolhimento e do respeito às diferenças.</td>
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
                                </tbody>
                            </table>
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 70%;"><u>Eixo: Terra e Universo</u></th>
                                        <th><u>1º tri</u></th>
                                        <th><u>2º tri</u></th>
                                        <th><u>3º tri</u></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>8. Identiﬁca e nomeia diferentes escalas de tempo: os períodos diários (manhã, tarde, noite) e a sucessão de dias, semanas, meses e anos.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>9. Utiliza quadros ou tabelas para sistematizar resultados de um levantamento de dados.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="margin-top: 5px;">
                                <thead style="background: none;">
                                    <tr>
                                        <th><u>LEGENDA:</u></th>
                                        <th><u>S - SIM:</u></th>
                                        <th><u>P - PARCIALMENTE:</u></th>
                                        <th><u>N - NÃO:</u></th>
                                        <th><u>ANT - AINDA NÃO TRABALHADO:</u></th>
                                    </tr>
                                </thead>
                            </table>
                            <!-- PAGINA 4 -->
                            <div class="container-box header-student-info" style="margin-top: 300px;">
                                <span class="pull-left">Aluno (a): <?php echo $student_identification->name ?></span>
                                <span style="margin-right: 10px;margin-left: 40%">Turma: <?php echo $classroom->name ?></span>
                                <span class="pull-right" style="margin-right: 10px;">Ano letivo: <?php echo $current_year ?></span>
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