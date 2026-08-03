# Frontend do assistente no formulário MACETE

Status: parcialmente implementado. Há um painel lateral básico no formulário de planos já salvos, com conversa síncrona, CSRF, mensagens escapadas, fontes e estados de carregamento/erro. Propostas ainda não podem ser aplicadas ao rascunho e o painel não envia campos ainda não salvos.

## Ponto de entrada

O assistente aparece na edição de planos MACETE já salvos, no painel lateral do formulário. Sem a configuração do serviço, informa que está indisponível; em planos novos, informa que é preciso salvar antes de iniciar a conversa. O design reutiliza classes e componentes do TAG, sem Bootstrap novo.

O acesso ao painel segue a mesma feature já exigida para o plano de aula. A invisibilidade do botão nunca substitui a autorização do endpoint.

## Fluxo do professor

1. O professor preenche ou seleciona o mínimo necessário: etapa, componente curricular e tema; habilidades são recomendadas para sugestões alinhadas.
2. Clica em “Assistente pedagógico”.
3. O TAG cria/retoma uma conversa usando o contexto seguro da versão já salva do plano.
4. O professor envia uma pergunta ou escolhe uma ação rápida, por exemplo “Gerar metodologia”, “Adaptar para multisseriada” ou “Sugerir avaliação”.
5. A interface aguarda a resposta completa e mostra a mensagem, fontes e propostas separadas.
6. Nesta etapa, o professor revisa a proposta e pode continuar a conversa; a aplicação ao formulário será uma etapa posterior.
7. O botão normal “Salvar” persiste somente o que estiver no formulário e passar pelas validações existentes.

## Estados da interface

| Estado | Comportamento |
| --- | --- |
| Pré-requisito ausente | Explica quais informações faltam; não chama o serviço. |
| Carregando | Desabilita somente o envio duplicado e anuncia “Gerando sugestão…”. |
| Resposta | Exibe mensagem, fontes e ações de aplicar/descartar. |
| Erro temporário | Mantém a mensagem digitada e o formulário intacto; permite nova tentativa. |
| Sem fonte suficiente | Mostra o alerta retornado; não apresenta alinhamento curricular como fato. |

O formulário permanece utilizável enquanto o assistente aguarda resposta, exceto pelo botão de envio da própria conversa.

## Propostas aplicáveis

Uma proposta é renderizada como cartão com:

- nome do campo alvo em linguagem pedagógica;
- prévia da alteração;
- justificativa;
- fontes correspondentes;
- botões “Aplicar” e “Descartar”.

Aplicar uma proposta altera somente o input alvo no DOM. Para campos de texto rico, o JavaScript deve atualizar o `textarea` original e sincronizar o Quill usando as funções do módulo. A proposta não deve submeter o formulário nem alterar campos de propriedade, status, etapas, componentes ou habilidades.

Ao aplicar, o cartão passa a indicar “Aplicada ao rascunho”; o professor ainda pode editar manualmente e salvar pelo fluxo existente.

## Campos aptos a receber sugestões na primeira entrega

| Grupo | Destinos |
| --- | --- |
| Contexto | `territory_context`, `knowledge_object`, contextualização por etapa. |
| Metodologia | `METHODOLOGY_INVOLVE`, `METHODOLOGY_INVESTIGATE`, `METHODOLOGY_ACT`. |
| Objetivo | `LEARNING_OBJECTIVE`. |
| Complementar | recursos, `evaluation`, `references_text` e seções de adaptação. |

Não permitir proposta para `school_inep_fk`, `users_fk`, `school_year`, `status`, IDs, turma, etapas/componentes ou relações de habilidade. Essas informações compõem contexto e são controladas pelo formulário e serviços atuais.

## Integração JavaScript

Criar um recurso próprio, por exemplo `app/modules/macete/resources/lesson-plan-assistant.js`, registrado apenas no formulário de plano. Ele deve:

- iniciar conversa e manter `conversation_id` somente na página atual;
  - usar o contexto saneado da versão persistida do plano; o envio do rascunho ainda não salvo será uma evolução posterior;
- enviar POST com token CSRF às rotas do próprio TAG;
- escapar toda mensagem e fonte exibida, inserindo texto com APIs seguras;
- aplicar propostas por uma lista explícita de mapeamentos campo → seletor;
- não receber URL, token ou credencial do serviço de IA.

Rotas em JavaScript devem ser geradas com `MaceteRoutes::url(...)` e expostas em configuração JSON segura na view, em vez de usar URL externa ou rota crua nova.

## Acessibilidade

- Painel identificado por título e região `aria-live="polite"` para progresso/erros.
- Teclado alcança mensagens, fontes e ações em ordem lógica.
- Cada botão “Aplicar” informa o campo que será alterado.
- Erros não dependem exclusivamente de cor ou ícone.
- Links das fontes abrem com rótulo compreensível e `rel="noopener"` quando externos.

## E2E futuro

Como é uma mudança de formulário e AJAX, a implementação exigirá validação E2E. Antes dela, obter URL, credenciais, dados de teste e aprovação do plano de teste, conforme as regras do repositório.
