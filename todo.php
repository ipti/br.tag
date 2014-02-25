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
 * 
 * @done S2 - Add validação para os campos que esão faltando
 * @done S2 - Popar a interrogação durante um intervalo de tempo ao falhar na regra do javascript
 * @done S2 Traduzir breadcrumbs (migalhas de pão)
 * @done S2 - Criar nova branch
 * @done S2 - 32 - O Cadastro deve ser feito de forma básica, só contendo o nome e dados de acesso.
 * @done S2 - Atualizar Model do Usuário
 * @done S2 - Criar formulário de cadastro de usuários.
 * @done S2 - Criar validações dos campos de cadastro.
 * @done S2 - Colocar helpers.
 * @done S2 - Fazer associação de escolas com usuários.
 * @done S2 - Criar action de criar usuários.
 * 
 * 
 * 
 * @done S2 - As vezes o botão de remover professor da turma não aparece.
 * 
 * @done S2 - Gerar crud das tabelas do banco
 * @done S2 - Gerar modelos das tabelas do banco
 * @done S2 - Traduzir tela de criar usuário
 * @done S2 - Visualizar todas as escolas pelo admin
 * @done S2 - Criar tabelas de aulas e faltas
 * @done S2 - inserir aba de frequência no fullmenu
 * @done S2 - Criar tela de criar quadro de aulas
 * @done S2 - Gerar calendário a partir do quadro de aulas
 * @done S2 - Criar tela de exibir quadro de aulas
 * 
 * @done S2 - Problemas do login - Limpagem do banco
 * @done S2 - Problemas do Login - Cadastro do admin
 * 
 * @done S2 - Adiconar links de Frequência no menu
 * @done S2 - Alterar links do menu de Frequência (ClassBoard e Classes)
 * 
 * @done S2 - Criar estrutura da tela de Frequência - ClassBoard.
 * @done S2 - Traduzir a tela de ClassBoard.
 * 
 * @done S2 - Iniciar a tabela pela segunda
 * @done S2 - Aumentar tamanho das linhas do calendário
 * @done S2 - inserir linhas entre a tabela
 * @done S2 - Adicionar Campo de Filtro de Turma à Tela de Frequência - ClassBoard.
 * 
 * @done S2 - Adicionar Função do Filtro de Turma à Tela de Frequência - ClassBoard.
 * 
 * @done S2 - Adicionar Turno como atributo de Classroom
 * 
 * @todo S2 - Ajeitar o design dos eventos
 * @done S2 - Deixar o modal de update correto
 * @done S2 - Deixar o modal correto
 * @done S2 - Alterar a tela de classboard para trazer os atributos necessários
 * 
 * @done S2 - Deixar a tabela editável
 * @done S2 - Remover cursor pointer dos labels
 * @done S2 - filtrar a lista de classes ao selecionar a escola (bug, unico que nao filtra) 
 * @done S2 - Modificar o CSS do tema para melhor visualizar o quadro de horário
 * 
 * @done s2 - Título da página de Admin não está no arquivo de tradução
 * @done s2 - Estrutura de breadcrumb da tela de usuário assim: Home -> Administração -> Usuários -> Criar 
 * 
 * @done s2 - Desminificar template.min.css e mudar a chamada de arquivo no fullmenu
 * @done s2 - BUG - Quadro de aulas fica por cima do título ao rolar para baixo
 * @done s2 - Botão de New Class deve ficar fixo ao rolar página para baixo
 * 
 * 
 * @todo S2 - Alterar permissões no ACL.
 * @todo S2 - Alterar permissões de uso de ClassBoard.
 * @todo S2 - Alterar permissões de uso de Classes.
 * @todo S2 - Alterar permissões de uso de ClassesFaults.
 * 
 * @todo S2 - Cadastrar aulas previstas por disciplina - ClassBoard.
 * @todo S2 - Colocar lista de aulas previstas por disciplina - ClassBoard.
 * @done s2 - Adicionar professor na tabela de ClassBoard.
 * @done s2 - Gerar novo modelo de ClassBoard.
 * 
 * @done s2 - Colocar Tela de Classboard no ClassRoom
 * @done s2 - Problema na hora de renderizar o calendário, pro algum motivo não esta exibindo(tem que "mexer" a tela para exibir)
 * @done s2 - Transformar TeachingData em um Modal
 * @done s2 - Salvar eventos em lote ao cadastrar
 * @done s2 - Modificar Save do classroom
 * @done s2 - Modificar Save do classboard para salvar o professor
 * @done s2 - Modificar Modais para selecionar o professor
 * @done s2 - Modificar Modais para salvar apenas em lote ou salvar em live(lote para create, live para update)
 * 
 * 
 * @done s2 - Remover Classboard do menu.
 * 
 * @done s2 - Mostrar o professor da aula no ClassBoard
 * @done s2 - Modificar lista de disciplinas do EDCenso para as do Classroom
 * @todo s2 - Enviar alerta ao chocar horários informando qual horário esta chocando.
 * 
 * @todo s2 - Criar tela de Frenquência
 * @todo s2 - Criar filtros para selecionar Classroom e Discipline - Frequência
 * @todo s2 - Criar gerador de aulas que faltam.
 * @todo s2 - Cadastrar Faltas
 * @todo s2 - Justificar Faltas
 * @todo s2 - Cadastrar que dia não houve aula
 * @todo s2 - Cadastrar reposição
 * @done s2 - redirecionar a mudança de escola pra pagina inicial
 * 
 * 
 * 
 * * * RESPONSIVE DESIGN * * *
 * 
 * @todo S2 JOSÉ AGNALDO - Bugs no alinhamento dos campos no modo responsive (todas as telas)
 * @done S2 - Menu lateral sem ocultar
 * @todo S2 JOSÉ AGNALDO - Posição do logotipo
 * @todo S2 JOSÉ AGNALDO - Botões de Next e Preview ficam desalinhados ao diminuir e aumentar tela
 * @todo S2 JOSÉ AGNALDO - Retirar CSS desnecessaŕio
 * @todo S2 JOSÉ AGNALDO - Retirar JS desnecessário
 * 
 * * * USER EXPERIENCE * * *
 * 
 * @todo S2 JOSÉ AGNALDO - Frases de ajuda baseada nas ações do campo e não no tipo de dado a ser preenchido.
 * @todo S2 JOSÉ AGNALDO - Validação dos campos com explicação do tipo de erro e a forma correta de preenchimento.
 * @todo S2 JOSÉ AGNALDO - Erros de preenchimento podem ser validados antes de enviar o form via javascript (o tema possui ferramenta pronta)
 * @done S2 - Traduzir frases em inglês do Select2
 * @todo s2 JOSÉ AGNALDO - Subtítulos com descrição da tela atual
 * @todo s2 JOSÉ AGNALDO - Mover aviso de campos obrigatórios
 * @done s2 - botões de Próximo e Anterior não ficam lado a lado da tela de adicionar escola
 *
 * 
 * @later S3 - Criar action de update de usuários.
 * @later S3 - Criar tela de listagem de usuários.
 * @later S3 - Criar action de listagem de usuários.
 * @later S3 - Criar action de remoção de usuários.
 * 
 * @done S2 - Evitar scroll no modal da class board
 * 
 * OBS - Quando a sessão de usuário acaba algumas páginas ou tarefas (como escolher uma outra escola) exibe erros de PHP em vez de redirecionar pra tela de login
 * 
*/?>
