
# Módulo 7: Adicionar Aluno(rápido)

**Descrição**
Este caso de uso tem como objetivo possibilitar a inserção de um novo aluno no sistema, utilizando a funcionalidade de adicionar aluno rápido, permitindo que um usuário autorizado inclua e registre as informações essenciais do aluno.

**Testes realizados**
Adicionar aluno (rápido) com campos obrigatórios válidos e preenchidos;
Adicionar aluno (rápido) com campos obrigatórios em branco;
Adicionar aluno (rápido) com alguns campos inválidos.

**URL**
https://demo.tag.ong.br/?r=student/create&simple=1

### Adicionar aluno (rápido) com campos obrigatórios válidos e preenchidos
| Cenário                             | Casos de teste                                               | Passos de execução                                           | Cenário (BDD)                                                | Critérios de aceitação                                       | Resultados esperados                                         |
| ----------------------------------- | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ |
| Cenário 010: adicionar aluno rápido | CT010-001: Teste para adicionar aluno (rápido) com campos obrigatórios válidos e preenchidos | Acesse a funcionalidade adicionar aluno (rápido); preencha os campos obrigatórios; clique no botão de "Criar" | **Dado** que o usuário está na tela de Adicionar Aluno(rápido),  **Quando** o usuário preenche todos os campos registrados como obrigatórios: Nome de apresentação, Data de nascimento, sexo, cor/raça, nacionalidade, país de origem, estado, cidade, filiação,  localização/ zona de residência, e clica em Criar,                          **Então** o sistema deverá adicionar o novo aluno ao sistema e apresentar a mensagem “O cadastro de (nome do aluno) foi criado com sucesso!” | O sistema deve salvar as informações conforme foram preenchidas. | O sistema deverá registrar o novo aluno e  apresentar a mensagem “O cadastro de (nome do aluno) foi criado com sucesso!” |
| Cenário 010: adicionar aluno rápido | CT010-002: Teste para adicionar aluno (rápido) com campos obrigatórios válidos e preenchidos e opção Não declarado/Ignorado selecionada em Filiação | Acesse a funcionalidade adicionar aluno (rápido); preencha os campos obrigatórios; selecione Não declarado/Ignorado em Filiação; clique no botão de "Criar". | **Dado** que o usuário está na tela de Adicionar Aluno(rápido),   **Quando** o usuário preenche todos os campos registrados como obrigatórios: Nome de apresentação, Data de nascimento, sexo, cor/raça, nacionalidade, país de origem, estado, cidade,  localização/ zona de residência,  seleciona a opção Não declarado/Ignorado em Filiação,  e clica em "Criar"                      **Então** o sistema deverá salvar as informações e mostrar a mensagem “O cadastro de (nome do aluno) foi criado com sucesso!” | O sistema deve salvar as informações conforme foram preenchidas. | O sistema deverá registrar o novo aluno, matricular na turma selecionada e apresentar a mensagem “O cadastro de (nome do aluno)foi criado com sucesso!” |
| Cenário 010: adicionar aluno rápido | CT010-003: Teste para adicionar aluno (rápido) com campos obrigatórios válidos e preenchidos e opção Pai e/ou Mãe selecionada em Filiação | Acesse a funcionalidade adicionar aluno (rápido); preencha os campos obrigatórios; selecione o campo Pai e/ou Mãe em Filiação; clique no botão de "Criar". | **Dado** que o usuário está na tela de Adicionar Aluno(rápido),       **Quando** o usuário preenche todos os campos registrados como obrigatórios: Nome de apresentação, Data de nascimento, sexo, cor/raça, nacionalidade, país de origem, estado, cidade,  localização/ zona de residência,seleciona a opção Pai e/ou Mãe em Filiação, e clica em "Criar"                      **Então** o sistema deverá sinalizar a obrigatoriedade de preencher também o campo Nome Completo da Filiação ou Nome completo do Pai, e ao clicar em "Criar"  deverá mostrar a mensagem “O cadastro de (nome do aluno) foi criado com sucesso!” | O sistema deve salvar as informações conforme foram preenchidas. | O sistema deverá registrar o novo aluno, sinalizar a obrigatoriedade de preencher o campo Nome Completo da Filiação ou Nome completo do Pai e apresentar a mensagem “O cadastro de (nome do aluno)foi criado com sucesso!” quando clicar em "Criar". |

### Adicionar aluno (rápido) com campos obrigatórios em branco
| Cenário                                                      | Casos de teste                                               | Passos de execução                                           | Cenário (BDD)                                                | Critérios de aceitação                                       | Resultados esperados                                         |
| ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ |
| Cenário 011: adicionar aluno (rápido) - com  campos obrigatórios em branco | CT011-001: Teste para adicionar aluno (rápido) com todos os campos obrigatórios em branco | Todos os campos obrigatórios não preenchidos:  Nome de apresentação; data de nascimento; sexo; cor/raça; nacionalidade; país de origem; estado; cidade;filiação; localização/zona de residência; | **Dado** que o usuário está na tela de Adicionar aluno(rápido), **Quando** o usuário **não** preenche os campos obrigatórios, **Então** o sistema deverá apresentar uma mensagem informando todos os campos que são de preenchimento obrigatório, seguindo a ordem de seleção no sistema. | O sistema deverá reconhecer que os campos obrigatórios não foram preenchidos e apresentar uma mensagem indicando os campos não preenchidos. | O sistema apresentará uma mensagem indicando os campos que não foram preenchidos:     "Campo **Nome** é obrigatório. Campo **Data de nascimento** é obrigatório. Campo **Sexo** é obrigatório. Campo **Cor/Raça** é obrigatório. Campo **Filiação** é obrigatório. Campo **Nacionalidade** é obrigatório. Campo **País de origem** é obrigatório. Campo **Localização/ Zona de residência** é obrigatório." |

### Adicionar aluno (rápido) com alguns campos inválidos
| Cenário                                                      | Casos de teste                              | Passos de execução                                           | Cenário (BDD)                                                | Critérios de aceitação                                       | Resultados esperados                                         |
| ------------------------------------------------------------ | ------------------------------------------- | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ |
| Cenário 012: adicionar aluno (rápido) com alguns campos inválidos | CT012-001: Teste com nome de aluno inválido | Campo Nome de apresentação inválido; Outros campos obrigatórios válidos; Clique em "Criar". | **Dado** que o usuário está na tela de Adicionar Aluno,  **Quando** o usuário preenche o campo Nome de apresentação inválido ,**Então** o sistema deverá apresentar uma mensagem indicando os caracteres que o campo aceita: "O campo aceita somente caracteres maiúsculos de A a Z,sem cedilhas e/ou acentos. Tamanho mínimo: 1." | O sistema deve apresentar a mensagem indicando os caracteres que o campo  Nome aceita e não permitir que o usuário crie a escola contendo caractere diferente do permitido. | O sistema deve reconhecer que o nome está inválido, apresentar a mensagem de erro e não permitir que o usuário crie/salve a escola enquanto estiver com o erro. |
| Cenário 010: adicionar aluno rápido | CT010-001: Teste para adicionar aluno (rápido) com campos obrigatórios válidos e peenchidos | Acesse a funcionalidade adicionar aluno (rápido); preencha os campos obrigatórios; clique no botão de "Criar" | **Dado** que o usuário está na tela de Adicionar Aluno(rápido),  **Quando** o usuário preenche todos os campos registrados como obrigatórios: Nome de apresentação, Data de nascimento, sexo, cor/raça, nacionalidade, país de origem, estado, cidade, filiação, **pós-censo,** localização/ zona de residência, **deficiência, tipos de deficiência,** e clica em Criar, *verificar a necessidade de obrigatoriedade dos campos em negrito.  **Então** o sistema deverá adicionar o novo aluno ao sistema e apresentar a mensagem “O cadastro de (nome do aluno) foi criado com sucesso!” | O sistema deve salvar as informações conforme foram preenchidas. | O sistema deverá registrar o novo aluno e  apresentar a mensagem “O cadastro de (nome do aluno)foi criado com sucesso!” |