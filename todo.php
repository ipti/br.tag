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
 * 
 * @done S1 - 21 - A turma precisa de um periodo letivo senão ela fica atemporal.
 * @done S1 - 23 - Lembrar de associar o professor a turma.
 * @done S1 - Organizar os campos do form_classroom
 * @done S1 - Modificar aba disciplinas para Teaching Data.
 * @done S1 - Vincular disciplinas do classroom com as do teachingdata
 * 
 * @done S2 - Add validação para os campos que esão faltando
 * @done S2 - Popar a interrogação durante um intervalo de tempo ao falhar na regra do javascript
 * @done S2 Traduzir breadcrumbs (migalhas de pão)
 * 
 * @done S2 - Criar nova branch
 * @Francisco S2 - 32 - O Cadastro deve ser feito de forma básica, só contendo o nome e dados de acesso.
 * @done S2 - Atualizar Model do Usuário
 * @Francisco S2 - Criar formulário de cadastro de usuários.
 * @todo S2 - Criar tela de listagem de usuários.
 * @Francisco S2 - Fazer associação de escolas com usuários.
 * @Francisco S2 - Criar action de criar usuários.
 * @todo S2 - Criar action de update de usuários.
 * @todo S2 - Criar action de listagem de usuários.
 * @todo S2 - Criar action de remoção de usuários.
 * @todo S2 - 33 - Criar um sistema de frequencia como no tag antigo lembrando de associar esta frequencia ao aluno e a turma, inicialmente de forma básica.
 * @todo S2 - 34 - A frequencia pode ser feita utilizando como base o diario e o que discutimos anteriormente lembrando da necessidade do BOLSA FAMILIA
 * 
 * @done S2 - As vezes o botão de remover professor da turma não aparece.
 * 
 * @done S2 - Visualizar todas as escolas pelo admin
 * @done S2 - Criar tabelas de aulas e faltas
 * @todo S2 - inserir aba/botão de cadastrar aulas na tela de turma
 * @done S2 - inserir aba de frequência no fullmenu
 * @todo S2 - Criar tela de criar quadro de aulas
 * @todo S2 - Gerar calendário a partir do quadro de aulas
 * @todo S2 - Criar tela de exibir quadro de aulas
 * 
 * 
 * * * RESPONSIVE DESIGN * * *
 * 
 * @todo S2 - Bugs em campos (todas as telas)
 * @todo S2 - Menu lateral (ocuta mas continua ocupando espaço em telas pequenas)
 * @todo S2 - Posição do logotipo (tela de login e todo a app)
 * @todo S2 - Botões de Next e Preview ficam desalinhados ao diminuir e aumentar tela
 * @todo S2 - Retirar CSS desnecessaŕio
 * @todo S2 - Retirar JS desnecessário
 * 
 * * * USER EXPERIENCE * * *
 * 
 * @todo S2 - Frases de ajuda baseada nas ações do campo e não no tipo de dado a ser preenchido.
 * @todo S2 - Validação dos campos com explicação do tipo de erro e a forma correta de preenchimento.
 * @todo S2 - Erros de preenchimento podem ser validados antes de enviar o form via javascript (o tema possui ferramenta pronta)
 * @done S2 - Traduzir frases em inglês do Select2
 * 
*/?>