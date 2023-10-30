# Módulo 3: Alterar escola

**Descrição**
O objetivo deste teste é garantir a funcionalidade correta do processo de alteração de escola em um campo de seleção, verificando a capacidade do sistema em permitir que o usuário selecione e alter a escola.

**Testes realizados**
Teste para alterar escola no campo de seleção.


**URL**
https://demo.tag.ong.br/

### Alterar escola no campo de seleção
| Cenário                      | Caso de teste                                 | Passos de execução | Cenário(BDD)                                                 | Critérios de aceitação                                       | Resultados esperados                                         |
| ---------------------------- | --------------------------------------------- | ------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------------------ |
| Cenário 003 : Alterar Escola | CT003-001: Alterar escola no campo de seleção | Selecionar escola. | **Dado** que o usuário está na tela principal, **Quando** o usuário clica na escola, **Então** o sistema deverá alterar as informações conforme a escola selecionada. | O sistema deve filtrar e mostrar as informações da escola selecionada. | O sistema mostrará as informações da escola selecionada e apresentará as atividades recentes realizadas no sistema. |
