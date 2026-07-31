# Serviço de IA: agente pedagógico e RAG

Status: especificação de arquitetura — não implementado.

## Responsabilidade

O serviço transforma uma mensagem do professor e o contexto autorizado do plano em:

- uma resposta conversacional em português;
- alterações sugeridas para campos do plano MACETE;
- referências recuperadas que justificam a sugestão;
- alertas quando não houver evidência suficiente ou o pedido extrapolar o escopo.

Ele não autentica usuários do TAG diretamente, não consulta o banco do TAG e não persiste planos.

## Stack recomendada

| Camada | Tecnologia | Motivo |
| --- | --- | --- |
| API | FastAPI | API HTTP tipada, assíncrona e simples de operar. |
| Agente e validação | PydanticAI e Pydantic | Contratos tipados, validação de saída e adaptação a provedores compatíveis. |
| Dados próprios | PostgreSQL + pgvector | Conversas, catálogo de documentos, auditoria e busca vetorial no mesmo banco. |
| Modelo | Qwen/DashScope ou Kimi/Moonshot | Configurável por ambiente; o domínio não depende de um fornecedor. |
| Empacotamento | Docker | Implantação isolada do legado Yii 1.1. |

`pgvector` é a escolha inicial para reduzir componentes. Um gateway como LiteLLM é opcional e só deve ser introduzido se forem necessários fallback automático, rate limit compartilhado ou múltiplos consumidores.

## Estrutura proposta

```text
macete-ai-service/
  app/
    api/
      routes/conversations.py
      routes/health.py
    agents/
      lesson_plan_agent.py
      prompts.py
    schemas/
      requests.py
      replies.py
      knowledge.py
    services/
      conversation_service.py
      retrieval_service.py
      suggestion_service.py
    providers/
      base.py
      qwen.py
      kimi.py
    repositories/
      conversations.py
      knowledge.py
      audit.py
    settings.py
  migrations/
  tests/
  Dockerfile
```

## Fluxo de uma mensagem

1. O TAG autoriza o usuário e monta o contexto permitido do plano.
2. O serviço valida o contrato e carrega a conversa pelo `conversation_id` e `tenant_id`.
3. O recuperador pesquisa apenas documentos ativos e compatíveis com os filtros curriculares recebidos.
4. O agente recebe instruções fixas, contexto do plano, histórico limitado da conversa e os trechos recuperados.
5. O provedor responde integralmente; não há SSE nem streaming na primeira entrega.
6. A resposta é validada contra o schema `AssistantReply`. Saídas inválidas podem passar por uma tentativa controlada de reparo; se continuarem inválidas, a API retorna erro sem sugestão.
7. A mensagem, os trechos utilizados, o modelo e o consumo são registrados para auditoria.

## Instruções obrigatórias do agente

O prompt de sistema deve impor pelo menos estas regras:

- responder em português do Brasil e apoiar, não substituir, a decisão pedagógica do professor;
- usar somente as referências entregues como fundamento curricular;
- não afirmar alinhamento com BNCC ou documento local sem fonte recuperada;
- indicar incerteza e pedir contexto quando a evidência for insuficiente;
- não solicitar nem inferir nomes, dados sensíveis ou desempenho individual de estudantes;
- produzir sugestões adequadas à etapa, ao componente e às habilidades recebidas;
- não inventar links, códigos de habilidades, páginas ou citações;
- devolver alterações somente nos campos previstos pelo contrato.

## Saída estruturada

O agente trabalha com dois tipos de conteúdo: a resposta conversacional e uma lista de propostas aplicáveis. Cada proposta mantém a alteração isolada para permitir confirmação granular pelo professor.

```json
{
  "message": "Sugeri uma investigação baseada em medidas observadas no entorno da escola.",
  "proposals": [
    {
      "id": "prop_01",
      "field": "sections.METHODOLOGY_INVESTIGATE.stage_5",
      "operation": "replace",
      "value": "Em grupos, os estudantes registram e comparam medidas...",
      "rationale": "A proposta retoma a habilidade selecionada e o contexto territorial."
    }
  ],
  "sources": [
    {
      "document_id": "bncc-ei-ef-2018",
      "title": "Base Nacional Comum Curricular",
      "version": "2018",
      "section": "Matemática — Ensino Fundamental",
      "page": 302,
      "excerpt": "...",
      "source_url": "https://..."
    }
  ],
  "warnings": []
}
```

Campos aceitos, operações (`replace` e `append`) e limites de tamanho são definidos no contrato. O serviço deve rejeitar qualquer tentativa de retornar campos internos, permissões, status ou identificadores de propriedade do plano.

## Persistência própria

Tabelas mínimas:

| Tabela | Conteúdo |
| --- | --- |
| `conversations` | Identificador, tenant, pseudônimo do usuário, plano externo, criação, expiração. |
| `messages` | Papel, conteúdo, data, modelo, versão do prompt e uso de tokens. |
| `knowledge_documents` | Fonte, versão, nível de autoridade, vigência e situação editorial. |
| `knowledge_chunks` | Trecho, página/seção, metadados e embedding. |
| `retrieval_events` | Trechos recuperados para cada resposta. |
| `model_requests` | Latência, provedor, modelo, resultado e erro sanitizado. |

`external_plan_id` serve somente para vincular a conversa ao plano no TAG; não é chave estrangeira entre bancos.

## Limites técnicos iniciais

- Timeout do provedor: configurável; sugestão inicial de 60 segundos.
- Uma mensagem em processamento por conversa.
- Histórico enviado ao modelo: janela limitada e resumida quando necessário.
- Busca: quantidade limitada de trechos, com deduplicação por documento/seção.
- Sem chamadas a ferramentas externas pelo modelo.
- Sem retenção de cadeia de raciocínio; registrar apenas entradas, respostas, fontes e métricas necessárias.
