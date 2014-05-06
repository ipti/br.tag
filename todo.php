<?php /*
 * @done S1 - 08 - 28 - ACL Controle de permissões e login através do banco de dados, criação de usuários para os secretários na escola e perfil.
 * @done S1 - 09 - Criar Tabela de Usuário
 * @done S1 - 09 - Criar Tabela NtoN Usuário Escola
 * @done S1 - 09 - Criar Models
 * @done S1 - 09 - Criar formulário de seleção de Escola
 * @done S1 - 08 - 29 - ACL O Yii já possui modulo pronto para isso é somente configurar e inseri as permissões no banco, NÃO FAÇAM NA MÃO
 * @done S1 - 09 - Criar o modelo ACL
 * @done S1 - 08 - Configurar o modelo ACL - http://www.yiiframework.com/doc/guide/1.1/pt_br/topics.auth#sec-4
 * @done S1 - 31 - Lembrar de associar o usuário a escola para fazer as filtragem necessárias nas telas.
 * @done S1 - 08 - Filtrar a listagem das turmas por escola da sessão
 * @done S1 - 08 - Filtrar a listagem das alunos por escola da sessão
 * @done S1 - 08 - Filtrar a listagem das matrículas por escola da sessão
 * @done S1 - 08 - Filtrar a listagem das professores por escola da sessão
 * @done S1 - Adicionar os inputs nos filtros necessarios
 * @done S1 - Inserir o prompt dos campos de instrutor
 * @done S1 - AJEITAR O INSTRUCTOR
 * @done S1 - preencher os tooltip helpers da tela de escola
 * @done S1 - preencher os tooltip helpers da tela de turma
 * @done S1 - preencher os tooltip helpers da tela de matrícula
 * @done S1 - preencher os tooltip helpers da tela de aluno
 * @done S1 - preencher os tooltip helpers da tela de professor
 * @done S1 - 04 - Retirar os links para view do breadcrumb e remover o "atualizar" do final
 * @done S1 - ajeitar o filtro do enrollment np index do enrollment
 * @done S1 - Tabelas redimensionando, colocar para não redimensionar.
 * @done S1 - Ordenar todas as listas dos dados
 * @done S1 - remover botão de atualizar 
 * @done S1 - Corrigir problema de mudança de aba de forma manual do school
 * @done S1 - Corrigir problema de mudança de aba de forma manual do student
 * @done S1 - Corrigir problema de mudança de aba de forma manual do instructor
 * @done S1 - Corrigir problema de mudança de aba de forma manual do classroom
 * @done S1 - Corrigir permissões de acesso às telas
 * @done S1 - Atualização dos campos apartir da escola.
 * @done S1 - 21 - A turma precisa de um periodo letivo senão ela fica atemporal.
 * @done S1 - 23 - Lembrar de associar o professor a turma.
 * @done S1 - Organizar os campos do form_classroom
 * @done S1 - Modificar aba disciplinas para Teaching Data.
 * @done S1 - Vincular disciplinas do classroom com as do teachingdata
 * @done S1 - Criar exclusão do teachingData e verificar a não exclusão de novos dados (vem undefined) 
 * @done S1 - Criar função addInstructor que adiciona o instrutor e suas disciplinas na array 
 * @done S1 - Validar se o ano é apenas número e mandar erro
 * @done S1 - defini como ficaram os arrays de Disciplinas e TeachingData para salvar no banco
 * @done S1 - Retirar campos de Escola e Turma TD
 * @done S1 - Adicionar campo para selecionar o instrutor TD
 * @done S1 - Mudar o TeachingData para a view do ClassRoom e o seu controler tbm.
 * @done S1 - Edição de teaching data - excluir o professor TD
 * @done S1 - criar estutura da tela de TeachingData no Classroom 
 * @done S1 - colocar o ano atual como valor padrão
 * @done S1 - 08 - 08 - Os Valores deste campo são definidos de forma global e pode vim preenchidos default
 * @done S1 - 08 - 07 - A Criação da turma é feita dentro de uma escola, não precisa ser necessário selecionar uma?                             
 * @done s1 - Melhorar a diagramação desta tela e textos.
 * @done s1 30 - Vem predefinido a universidade federal de mato grosso, precisa alterar isso para um valor default.
 * @done s1 - Vem predefinido a escola, precisa alterar isso para um valor default.
 * @done s1 - Usar um único método para solicitação ajax dos 2 campos de Estados
 * @done s1 - corrigir trigger da aba de dados variáveis.
 * @done S1 - alterar redirecionamento do instructor pra index
 * @done S1 - Remover aba de teaching data no update
 * @done s1 22 - Este filtro da cidade não está funcionando.                    
 * @done s1 - Modificar o icone das abas
 * @done S1 25 - Talvez um pre-filtro com a area de autação e 
 * @done s1 - depois os cursos ORDENADOS por nome
 * @done s1 - filtrar for high_education_institution_type.
 * @done s1 - ordenar alfabeticamente.
 * @done s1 - correção na filtragem por tipo de instituição
 * @done s1 - corrigir a validação da nacionalidade 
 * @done s1 - atualizar o select2 para versão 2.3.4
 * @done s1 - ajeitar mudança de sub-abas
 * @done s1 - Deixar a seleção de instituições mais rápida 
 * @done s1 24 - Dado Variavel é melhor dados educacionais???
 * 
 * 
 * @done S2 - Colocar data padrão        
 * @done S2 - Limitar quantidade de slots que aparecem no Quadro de Horário        
 * @done S2 - Não é necessário colocar o mês (o quadro de aulas serve pro ano inteiro)         
 * @done S2 - Traduzir dias da semana e meses do fullCalendar        
 * @done S2 - Criar o evento que importa os dados do banco        
 * @done S2 - Atualizar para a nova estrutura do bando o evento que importa os dados do banco       
 * @done S2 - Criar tela de dialogo para CRIAR da aula        
 * @done S2 - Não permitir que crie eventos no mesmo horário
 * @done S2 - Reduzir caracteres do evento
 * @done S2 - Comportar o horário na tabela de classboard
 * @done S2 - Criar tela de dialogo com opções de ALTERAR e REMOVER aula        
 * @done S2 - Criar função de REMOVER aula        
 * @done S2 - Criar função de ATUALIZAR aula        
 * @done S2 - criar o evento que ATUALIZAR os dados do banco ao mover a aula        
 * @done S2 - Draggear evento grande apos renderizar e voltar pequeno nao funciona
 * @done S2 - Ajax da criação de lessons 
 * @done S2 - Ajax da criação de lessons 
 * @done S2 - Ajax da criação de lessons 
 * @done S2 - Validação da disciplina 
 * @done S2 - Validação da classroom 
 * @done S2 - Criar modal ao clicar na tabela
 * @done S2 - Corrigir problemas do submit automático
 * @done S2 - Corrigir problemas do Layout
 * @done S2 - Add validação para os campos que esão faltando
 * @done S2 - Popar a interrogação durante um intervalo de tempo ao falhar na regra do javascript
 * @done S2 - Traduzir breadcrumbs (migalhas de pão)
 * @done S2 - Criar nova branch
 * @done S2 - 32 - O Cadastro deve ser feito de forma básica, só contendo o nome e dados de acesso.
 * @done S2 - Atualizar Model do Usuário
 * @done S2 - Criar formulário de cadastro de usuários.
 * @done S2 - Criar validações dos campos de cadastro.
 * @done S2 - Colocar helpers.
 * @done S2 - Fazer associação de escolas com usuários.
 * @done S2 - Criar action de criar usuários.
 * @done S2 - As vezes o botão de remover professor da turma não aparece.
 * @done S2 - Gerar crud das tabelas do banco
 * @done S2 - Gerar modelos das tabelas do banco
 * @done S2 - Traduzir tela de criar usuário
 * @done S2 - Visualizar todas as escolas pelo admin
 * @done S2 - Criar tabelas de aulas e faltas
 * @done S2 - inserir aba de frequência no fullmenu
 * @done S2 - Criar tela de criar quadro de aulas
 * @done S2 - Gerar calendário a partir do quadro de aulas
 * @done S2 - Criar tela de exibir quadro de aulas
 * @done S2 - Problemas do login - Limpagem do banco
 * @done S2 - Problemas do Login - Cadastro do admin
 * @done S2 - Adiconar links de Frequência no menu
 * @done S2 - Alterar links do menu de Frequência (ClassBoard e Classes)
 * @done S2 - Criar estrutura da tela de Frequência - ClassBoard.
 * @done S2 - Traduzir a tela de ClassBoard.
 * @done S2 - Iniciar a tabela pela segunda
 * @done S2 - Aumentar tamanho das linhas do calendário
 * @done S2 - inserir linhas entre a tabela
 * @done S2 - Adicionar Campo de Filtro de Turma à Tela de Frequência - ClassBoard.
 * @done S2 - Adicionar Função do Filtro de Turma à Tela de Frequência - ClassBoard.
 * @done S2 - Adicionar Turno como atributo de Classroom
 * @done S2 - Deixar o modal de update correto
 * @done S2 - Deixar o modal correto
 * @done S2 - Alterar a tela de classboard para trazer os atributos necessários
 * @done S2 - Deixar a tabela editável
 * @done S2 - Remover cursor pointer dos labels
 * @done S2 - filtrar a lista de classes ao selecionar a escola (bug, unico que nao filtra) 
 * @done S2 - Modificar o CSS do tema para melhor visualizar o quadro de horário
 * @done S2 - Título da página de Admin não está no arquivo de tradução
 * @done S2 - Estrutura de breadcrumb da tela de usuário assim: Home -> Administração -> Usuários -> Criar 
 * @done S2 - Desminificar template.min.css e mudar a chamada de arquivo no fullmenu
 * @done S2 - BUG - Quadro de aulas fica por cima do título ao rolar para baixo
 * @done S2 - Botão de New Class deve ficar fixo ao rolar página para baixo
 * @done S2 - Adicionar professor na tabela de ClassBoard.
 * @done S2 - Gerar novo modelo de ClassBoard.
 * @done S2 - Colocar Tela de Classboard no ClassRoom
 * @done S2 - Problema na hora de renderizar o calendário, pro algum motivo não esta exibindo(tem que "mexer" a tela para exibir)
 * @done S2 - Transformar TeachingData em um Modal
 * @done S2 - Salvar eventos em lote ao cadastrar
 * @done S2 - Modificar Save do classroom
 * @done S2 - Modificar Save do classboard para salvar o professor
 * @done S2 - Modificar Modais para selecionar o professor
 * @done S2 - Modificar Modais para salvar apenas em lote ou salvar em live(lote para create, live para update)
 * @done S2 - Remover Classboard do menu.
 * @done S2 - Mostrar o professor da aula no ClassBoard
 * @done S2 - Modificar lista de disciplinas do EDCenso para as do Classroom 
 * @done S2 - redirecionar a mudança de escola pra pagina inicial
 * @done S2 - Evitar scroll no modal da class board
 * @done S2 - Alterar permissões no ACL.
 * @done S2 - Alterar permissões de uso de Frequency.
 * @done S2 - renomear Classes para Frequency
 * @done S2 - Cadastrar aulas previstas por disciplina - ClassBoard.
 * @done S2 - Colocar lista de aulas previstas por disciplina - ClassBoard.
 * @done S2 - Criar tela de Frenquência
 * @done S2 - Criar filtro para selecionar Classroom 
 * @done S2 - Criar filtro de Discipline
 * @done S2 - Criar filtro de month
 * @done S2 - Criar gerador de aulas que faltam.
 * @done S2 - Cadastrar Faltas
 * @done S2 - Mostrar Faltas cadastradas
 * @done S2 - Cadastrar que dia não houve aula
 * @done S2 - Frequencia do encino fundamental menor
 * @done S2 - Corrigir o bug nas novas frequencias.
 * @done S2 - Modificar CSS de impressão.
 * @done S2 - Formulários não podem dar submit caso aperte Enter
 * @done S2 - Retirar botões de excluir nas listagens de Aluno
 * @done S2 - Retirar botões de excluir nas listagens de Escola
 * @done S2 - Retirar botões de excluir nas listagens de Professor
 * @done S2 - Menu lateral sem ocultar
 * @done S2 - Traduzir frases em inglês do Select2
 * @done S2 - botões de Próximo e Anterior não ficam lado a lado da tela de adicionar escola
 * @done S2 - Corrigir tamanho do imput nas listagens de Aluno, Escola, Professor
 * @done S2 - Não mostrar escolas desativadas no select de cadastro de usuários
 * @done S2 - Após salvar matrícula não retornando pra lista de matrículas
 * @done S2 - Criar lista de alunos na turma
 * @done S2 - Frequencia não está mostrando as segundas-feiras
 * @done S2 - Classboard não cadastra domingo.
 * @done S2 - Retirar os dois pontos dos campos de datas - Remover os dois pontos(a mascara se encarrega de coloca-los)
 * @done S2 - Permitir adicionar o máximo que o Educacenso permite de materias - Modificar classes dos MultiSelects
 * @done S2 - Permitir adicionar o máximo que o Educacenso permite de materias - Modificar chamada do Select2 para os MultiSelects
 * @done S2 - Dados Educacionais "Superior" Não mostra a coluna no update - Triggar a função no momento que troca de aba
 * @done S2 - Substituir textos com "docente" para "professor" - Modificar Arquivo de Tradução
 * @done S2 - Adicionar botão voltar - Colocar Botão no Theme
 * @done S2 - Filtrar matriculas por ano - Colocar coluna
 * @done S2 - Filtrar matriculas por ano - Adicionar variável no Model
 * @done S2 - Filtrar matriculas por ano - Modificar rules do Model
 * @done S2 - Filtrar matriculas por ano - Modificar search do Model
 * @done S2 - Agrupar documentos por tipo Aluno - Reorganizar os campos
 * @done S2 - Agrupar documentos por tipo Aluno - Colocar os campos em widgets
 * @done S2 - Agrupar documentos por tipo Aluno - Mudar ícones dos Widgets 
 * @done S2 - Campo Certidão Antiga/Nova - Ao selecionar desabilitar os campos desnecessários
 * @done S2 - Campo Certidão Antiga/Nova - Triggar função no Update
 * @done S2 - Campo Certidão Antiga/Nova - Resolver conflitos com a seleção de Nacionalidade(estrangeira)
 * @done S2 - Posição do logotipo
 * @done S2 - Adicionar novas tabelas ao "LimparBanco"
 * @done S2 - Traduzir roles no criar usuário
 * @done S2 - Ao adicionar disciplina professor aparece como "undefined"
 * @done S2 - Remover "Tag" da URL
 * @done S2 - Seleção de estado dando erro - Escola - Remover Organs das dependencias de UF
 * @done S2 - Seleção de estado dando erro - Escola - Criar Dependencias de City
 * @done S2 - Seleção de estado dando erro - Escola - Colocar Dependencias de City no Rules
 * @done S2 - Seleção de estado dando erro - Escola - Modificar Ajax de UF
 * @done S2 - Seleção de estado dando erro - Escola - Modificar Ajax de City
 * @done S2 - Seleção de estado dando erro - Escola - Corrigir Conflitos
 * @done S2 - Ao adicionar disciplina SEM professor aparece como "undefined"
 * @done S2 - Campos desabilitados sem a seta lateral
 * @done S2 - Alterar os perfis de usuários
 * @done S2 - Alterar CSS do dropdown de escolas
 * @done S2 - Traduzir título e breadcrumb da página de frequência
 * @done S2 - Full calendar renderizando a cada clique, fazer um callback pra verificar se o calendário ja está renderizado
 * @done S2 - Distinguir campos obrigatorios com negrito
 * @done s2 - corrigir problema com nomes grandes de disciplina
 * @done s2 - colocar o botão de excluir na parte superior
 * 
 * 
 * 
 * ********* Os erros não apareceram nos Testes
 * @done S2 - Cartório não está aparecendo na tela de update
 * @done S2 - A segunda ainda não estava aparecendo na frequência (verificar)
 * 
 * 
 * ******************************************************
 * * DESIGN                                             *
 * ******************************************************
 * 
 * * * * RESPONSIVE DESIGN * * *
 * 
 * @todo SX - Bugs no alinhamento dos campos no modo responsive (todas as telas)
 * 
 * @todo SX - Botões de Next e Preview ficam desalinhados ao diminuir e aumentar tela
 * @todo SX - Retirar CSS desnecessaŕio
 * @todo SX - Retirar JS desnecessário
 * 
 * 
 * * * * USER EXPERIENCE * * *
 * 
 * @todo SX - Validação dos campos com explicação do tipo de erro e a forma correta de preenchimento.
 * 
 * @todo SX - Erros de preenchimento podem ser validados antes de enviar o form via javascript (o tema possui ferramenta pronta)
 *
 * 
 * * * * OTHERS * * *
 * 
 * @done S3 - Ajustar tabela de frequência.
 * 
 * @todo SX - Modificar diagramação da listagem de alunos
 * @todo SX - Ajeitar o design dos eventos
 * 
 * ******************************************************
 * 
 * 
 * ******************************************************
 * * DEVELOPMENT                                        *
 * ******************************************************
 * 
 * * * * BUGS * * *
 *  
 * @done S3 - Frequencia não marca faltas no 1º save.
 * @done S3 - Frequencia de Todas as disciplinas não ta salvando - Alterar banco para aceitar null.
 * @done S3 - Frequencia de Todas as disciplinas não ta salvando - Alterar model.
 * @done S3 - Frequencia de Todas as disciplinas não ta salvando - Alterar o GetClasses, identificando com null "todas as disciplinas"
 * @done S3 - Frequencia de Todas as disciplinas não ta salvando - Alterar o Save, diferenciar Todas as Disciplinas e apenas uma.
 * 
 * @done S3 - Ordenar lista de alunos na turma.
 * 
 * @done S3 - Correção no Limpar Banco
 * @done S3 - Conflito de Fuso Horário em diferentes SO
 * 
 * * * * CHANGES * * *
 * 
 * @done S3 - Enviar apenas Turmas atuais
 * 
 * @done S3 - Enviar apenas Matriculas atuais
 * 
 * @done S3 - Enviar apenas Alunos ativos - Identificação
 * @done S3 - Enviar apenas Alunos ativos - Documentos
 * 
 * @done S3 - Enviar apenas Professores ativos - Identificação
 * @done S3 - Enviar apenas Professores ativos - Documentos
 * @done S3 - Enviar apenas Professores ativos - Dados Variáveis
 * @done S3 - Enviar apenas Professores ativos - Dados de Docência
 * 
 * @done P2 - Criar rotina de Backup.
 * @done P2 - Colocar botão de Backup no Admin.
 * @done P2 - Fazer Backup no Login.
 * @done P2 - Criar extensão de Gerenciamento de Arquivo.
 * @done P2 - Criar rotina de Estatisticas.
 * @done P2 - Colocar botão de Estatisticas no Admin.
 * 
 * @todo SX - Trocar os campos de pesquisa da listagem por listas.
 * @todo SX - 09 - O Campo nome deve possuir uma mascara e seguir um padrão a ser definido. - Turma
 * 
 * @todo SX - Preenchimento errado deve só apagar os caracteres errados e não o nome todo
 *  
 * 
 * * * * OTHERS * * *
 * 
 * @todo Sx - Relatórios do bolsa família, virada do ano
 * @todo Sx - Relatórios do bolsa família, Tipos de Frequencia(Fundamental menor e maior)
 * 
 * @done S3 - Relatórios - Criar Menu
 * @done S3 - Relatórios - Criar Tela Inicial
 * @done S3 - Relatório bolsa Família - Criar Tela
 * @done S3 - Relatório bolsa Família - Criar Regras de Acesso
 * @done S3 - Relatório bolsa Família - Alterar perfis de ACL
 * @done S3 - Relatório bolsa Família - Criar Estrutura do Relatório
 * 
 * @done S3 - Exportação - Adicionar Coluna para agregar Botão.
 * @done S3 - Exportação - Adicionar Botão na tela de Admin.
 * @done S3 - Exportação - Criar ActionExport do admin.
 * @done S3 - Exportação - Adicionar ActionExport das rules.
 * 
 * @done S3 - Exportação - Selecionar Todas as Escolas.
 * @done S3 - Exportação - Estruturar campos das Escolas no modelo EdCenso.
 * @done S3 - Exportação - Selecionar Todas as Estruturas Escolares.
 * @done S3 - Exportação - Estruturar campos das Estruturas Escolares no modelo EdCenso.
 * 
 * @done S3 - Exportação - Selecionar Todas as Turmas.
 * @done S3 - Exportação - Estruturar campos das Turmas no modelo EdCenso.
 * 
 * @done S3 - Exportação - Selecionar Todas as Identificações dos Instrutores.
 * @done S3 - Exportação - Estruturar campos das Identificações dos Instrutores no modelo EdCenso.
 * @done S3 - Exportação - Selecionar Todas as Documentos dos Instrutores.
 * @done S3 - Exportação - Estruturar campos das Documentos dos Instrutores no modelo EdCenso.
 * @done S3 - Exportação - Selecionar Todas as Variáveis de Encino dos Instrutores.
 * @done S3 - Exportação - Estruturar campos das Variáveis de Encino dos Instrutores no modelo EdCenso.
 * @done S3 - Exportação - Selecionar Todas as Dados de Docência dos Instrutores.
 * @done S3 - Exportação - Estruturar campos das Dados de Docência dos Instrutores no modelo EdCenso.
 * 
 * @done S3 - Exportação - Selecionar Todas as Identificações dos Alunos.
 * @done S3 - Exportação - Estruturar campos das Identificações dos Alunos no modelo EdCenso.
 * @done S3 - Exportação - Selecionar Todas as Documentos dos Alunos.
 * @done S3 - Exportação - Estruturar campos das Documentos dos Alunos no modelo EdCenso.
 * 
 * @done S3 - Exportação - Selecionar Todas as Matrículas.
 * @done S3 - Exportação - Estruturar campos das Matrículas no modelo EdCenso.
 * 
 * @done S3 - Exportação - Gerar Arquivo Final
 * 
 * @todo SX - Enviar alerta ao chocar horários informando qual horário esta chocando.
 * @todo SX - Verificar choque de horários  
 * @todo SX - Verificar se o professor já esta dando aula neste horário
 * 
 * @todo SX - Justificar Faltas
 * @todo SX - Cadastrar reposição
 * 
 * @todo SX - Criar action de update de usuários.
 * @todo SX - Criar tela de listagem de usuários.
 * @todo SX - Criar action de listagem de usuários.
 * @todo SX - Criar action de remoção de usuários.
 * 
 * ******************************************************
 * 
 * 
 * 
*/?>
