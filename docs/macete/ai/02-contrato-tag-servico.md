# Contrato de integração: TAG e serviço de IA

Status: parcialmente implementado. Gateway, resolvedor de contexto e endpoints AJAX internos existem; o frontend do TAG ainda não foi implementado nem o recurso foi ativado para usuários.

## Princípios

- O navegador chama apenas rotas do TAG; a chave do provedor e a URL interna do serviço nunca chegam ao JavaScript.
- `LessonsplanController` permanece fino. Um serviço do módulo, por exemplo `MaceteAiAssistantGateway`, monta o contexto, chama a API interna e normaliza erros.
- O TAG aplica suas regras atuais de feature, escola, ano letivo, autoria e visibilidade antes de chamar o serviço.
- O serviço recebe contexto mínimo e devolve sugestões; não recebe acesso ao MySQL do TAG.

## Configuração do TAG

O ambiente do TAG deve fornecer estas variáveis, sem versionar valores reais:

```dotenv
MACETE_AI_SERVICE_URL=https://macete-ai.internal
MACETE_AI_SERVICE_TOKEN=<token-interno>
MACETE_AI_SERVICE_TIMEOUT_SECONDS=60
MACETE_AI_ACTOR_PSEUDONYM_KEY=<segredo-longo-e-rotacionável>
```

Sem URL, token ou chave de pseudonimização, o recurso deve permanecer oculto/desabilitado no formulário. `MaceteAiPlanContextResolver` gera o identificador do ator por HMAC; portanto, o ID interno do usuário não sai do TAG. O gateway normaliza erros de rede e respostas HTTP sem incluir o token ou o corpo de erro do serviço na mensagem exibida ao usuário.

## Rotas novas no TAG

As rotas abaixo são internas ao módulo e exigem usuário autenticado com `FEAT_DIARY_LESSON_PLAN`.

| Rota | Método | Finalidade |
| --- | --- | --- |
| `macete/lessonsplan/startAssistantConversation` | POST | Cria conversa associada ao plano autorizado. |
| `macete/lessonsplan/sendAssistantMessage` | POST | Encaminha uma mensagem ao serviço e retorna a resposta completa. |

Essas ações estão em `LessonsplanController`, exigem usuário autenticado, feature, POST e token CSRF do Yii. `MaceteRoutes.php` foi regenerado com `composer run routes:generate -- macete`. O cliente ainda precisa ser implementado para enviar `lesson_plan_id`, `conversation_id` e `message` sem expor configuração interna.

## Contrato TAG → serviço

### Criar/retomar conversa

`POST /v1/conversations`

```json
{
  "tenant_id": "school:12345678",
  "actor": { "id": "user:42", "role": "instructor" },
  "plan": {
    "external_id": 91,
    "school_year": 2026,
    "name": "Medidas no meu território",
    "theme": "Medidas no entorno da escola",
    "unit": "II Unidade",
    "stage_components": [
      { "stage_id": 5, "stage_name": "5º ano", "discipline_id": 3, "discipline_name": "Matemática" }
    ],
    "abilities": [
      { "id": 800, "code": "EF05MA19", "description": "Descrição cadastrada no TAG" }
    ],
    "draft": {
      "territory_context": "...",
      "knowledge_object": "...",
      "sections": {},
      "resources": {},
      "evaluation": "",
      "references_text": ""
    }
  }
}
```

O `actor.id` deve ser um identificador interno/pseudonimizado. Nome, e-mail, CPF, dados de estudante e dados não necessários não devem integrar este payload.

Resposta: `201 Created`, com `conversation_id`, `expires_at` e `assistant_version`.

### Enviar mensagem

`POST /v1/conversations/{conversation_id}/messages`

```json
{
  "tenant_id": "school:12345678",
  "message": "Crie uma atividade investigativa sem uso de internet.",
  "plan_snapshot": {
    "stage_components": [],
    "abilities": [],
    "draft": {}
  }
}
```

O snapshot em toda mensagem evita que o agente trabalhe com uma versão desatualizada quando o professor edita o formulário durante a conversa.

Resposta: `200 OK` com o schema `AssistantReply` descrito em [01-servico-agente-rag.md](01-servico-agente-rag.md).

## Autenticação entre serviços

Na primeira entrega, usar autenticação de serviço para serviço por `Authorization: Bearer <token interno>` e TLS. O token:

- fica apenas em variável de ambiente do TAG;
- é rotacionável;
- não é exposto em logs nem ao navegador;
- concede somente as rotas do assistente MACETE.

Em ambiente com infraestrutura compatível, preferir mTLS ou token assinado de curta duração. A decisão deve constar no runbook de implantação.

## Comportamento de erro

| Situação | Resposta do serviço | Resposta do TAG ao navegador |
| --- | --- | --- |
| Contexto inválido | `422` com código estável | Mensagem de correção; não chama o modelo. |
| Conversa inexistente ou fora do tenant | `404` | Inicia nova conversa, sem revelar dados. |
| Provedor indisponível/timeout | `503`/`504` | “Não foi possível gerar a sugestão agora. Tente novamente.” |
| Limite de uso | `429` | Informa indisponibilidade temporária sem expor limite interno. |
| Saída inválida | `502` | Mantém o formulário e informa falha na sugestão. |

O JavaScript nunca substitui campos quando a resposta não for `200` e validada pelo TAG.

## Aplicação de sugestões

Não existe endpoint “aplicar” no serviço de IA. O navegador aplica a proposta escolhida aos inputs existentes; o salvamento posterior continua usando o POST normal de `LessonsplanController::actionCreate` ou `actionUpdate` e `MaceteLessonPlanService::save`.

Essa separação garante que as validações atuais — etapas, componentes, habilidades, turma, status, sanitização e autoria — continuem sendo executadas.
