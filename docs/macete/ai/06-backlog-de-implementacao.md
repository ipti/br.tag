# Backlog de implementação — assistente pedagógico MACETE

Status: execução iniciada em 31/07/2026. Os checkboxes continuam representando o aceite completo de cada atividade; o quadro abaixo registra o estágio real de implementação.

## Convenções

- **TAG**: aplicação Yii 1.1 deste repositório.
- **Serviço IA**: nova aplicação Python independente.
- **Compartilhada**: atividade de contrato, homologação ou decisão que exige os dois times/sistemas.
- **Bloqueia**: tarefa que precisa estar concluída antes do início desta.
- As tarefas de desenvolvimento não autorizam ativar o recurso em produção sem as aprovações de segurança, privacidade e homologação previstas.

## Visão de responsabilidade

| Grupo | IDs | Onde o código ou artefato principal fica |
| --- | --- | --- |
| Compartilhadas | AI-001 a AI-004, AI-063 e AI-064 | Documentação, infraestrutura e homologação. |
| Serviço de IA | AI-010 a AI-034, AI-060 e AI-061 | Repositório independente `macete-ai-service`. |
| TAG | AI-040 a AI-053 e AI-062 | Este repositório, em `app/modules/macete/`. |

## Atualização de execução

Implementação técnica realizada no repositório independente `C:\Users\CoordenacaoTI\Projetos\macete-ai-service`. Nenhuma integração foi ativada no TAG, nenhum plano é salvo pelo serviço e nenhuma fonte BNCC foi ativada sem revisão humana.

| ID | Situação | Evidência / pendência para aceite |
| --- | --- | --- |
| AI-004 | Em andamento | Repositório criado, Docker Compose, imagem, CI, lint e testes configurados. Falta definir convenção de branches/PR e confirmar o pipeline remoto. |
| AI-010 | Em andamento | FastAPI, configuração tipada e `GET /health` implementados. Falta correlação de requisição e padronização abrangente de erros. |
| AI-011 | Em andamento | PostgreSQL isolado, pgvector e migrations `0001` a `0004` criadas para conversas, mensagens, auditoria, documentos, chunks e revisões. Falta documentar estratégia formal de rollback. |
| AI-012 | Em andamento | Conversas persistidas com tenant, ator pseudonimizado, expiração e consulta restrita ao tenant. Falta ampliar os testes de contrato para todos os cenários de isolamento. |
| AI-013 | Em andamento | Token Bearer entre serviços e segredos por variáveis de ambiente implementados. TLS, rede interna e secret manager dependem da infraestrutura de implantação. |
| AI-020 | Em andamento | Catálogo editorial, estados de documento, decisões de revisão e painel administrativo server-rendered implementados. O painel cria rascunhos e registra submissão, aprovação/ativação ou rejeição com CSRF e token próprio. Faltam gestão de usuários/papéis e homologação do processo pedagógico. |
| AI-021 | Em andamento | pgvector, chunks, índices curriculares e filtros de recuperação implementados e testados. |
| AI-022 | Em andamento | Ingestão de Markdown UTF-8 curado por CLI e painel, com hash, versão, metadados editoriais e embeddings implementada. Extração de PDF, OCR e conferência humana permanecem operacionais. |
| AI-023 | Em andamento | Busca híbrida implementada: documento ativo/vigente, tenant, idioma, etapa, componente, habilidade exata, similaridade e deduplicação. Falta validar contra corpus curado e conjunto de avaliação. |
| AI-024 | Em andamento | Metadado da fonte oficial BNCC e roteiro de curadoria cadastrados em `references/bncc/`. Falta extrair, revisar, indexar e aprovar o corpus; por isso não há fonte BNCC ativa. |
| AI-030 | Em andamento | Schemas do contexto, resposta, propostas e citações implementados; propostas restringem campos e operações permitidos. Falta validar o mapeamento final contra os campos do formulário Yii. |
| AI-031 | Em andamento | Adaptadores Qwen de geração e embedding implementados em modo não-streaming; conexão real validada. Kimi, tentativas controladas e comparação formal de provedores permanecem pendentes. |
| AI-032 | Em andamento | Prompt versionado com guardrails, resposta JSON e bloqueio de fontes/campos inventados implementado e testado. |
| AI-033 | Em andamento | Orquestração RAG → Qwen → resposta validada, com auditoria de mensagens, modelo e recuperação. Histórico limitado de conversa e tratamento completo de falhas ainda precisam ser implementados. |
| AI-034 | Em andamento | Rotas de conversa, autenticação, tenant e resposta completa implementadas. Faltam limites de uso e cobertura de contrato para todos os códigos previstos. |
| AI-040 | Em andamento | Configuração por variáveis de ambiente e desativação segura sem configuração implementadas. Faltam teste automatizado e configuração da instância de homologação. |
| AI-041 | Em andamento | `MaceteAiAssistantGateway` implementado com POST JSON, Bearer, timeout e normalização de falhas sem expor segredo. Faltam testes com cliente falso. |
| AI-042 | Em andamento | `MaceteAiPlanContextResolver` usa serviços MACETE e pseudonimiza o ator por HMAC; habilidades vêm do `CourseClassAbilities`. Faltam testes de escopo e o snapshot de alterações ainda não salvas. |
| AI-043 | Em andamento | Endpoints POST/CSRF e rotas geradas implementados; não salvam o plano. Faltam testes de integração e homologação com a instância configurada. |
| AI-050 | Em andamento | Painel lateral básico, pré-requisito, carregamento, resposta, fontes e erro implementados com componentes TAG. Faltam ações rápidas e comportamento recolhível/expansível previsto na experiência final. |
| AI-051 | Em andamento | `lesson-plan-assistant.js` chama somente rotas TAG, mantém a conversa na página e renderiza texto de forma segura. Falta enviar snapshot do rascunho salvo localmente. |
| AI-052 | Pendente | Propostas são exibidas apenas para revisão; nenhuma alteração automática do formulário foi implementada. |
| AI-053 | Em andamento | Labels, foco para validação, mensagens textuais e regiões `aria-live` implementados. Falta validação por teclado com a futura aplicação de propostas. |
| AI-060 | Em andamento | Lint, testes unitários, migrations e testes de fumaça Docker executados. Faltam testes de contrato completos e provedor falso para todos os erros. |
| AI-061 | Pendente | Depende de corpus BNCC curado/ativo e avaliação pedagógica com perguntas anonimizadas. |
| AI-062 a AI-064 | Pendente | Dependem da implementação no TAG, homologação autorizada e decisões de operação/piloto. |

### Decisão de dados confirmada

O TAG já mantém as habilidades BNCC em `CourseClassAbilities`, acessadas pelo `MaceteAbilityService`. Essa tabela permanece a fonte estruturada dos campos `id`, `code` e `description` enviados no contexto autorizado do plano. O serviço IA não replica as habilidades, não acessa o MySQL do TAG e usa o corpus RAG apenas para fundamentação normativa, páginas, seções e citações.

## Tarefas compartilhadas — decisões e preparação

- [ ] **AI-001 — Aprovar escopo funcional do piloto**

  - Definir público piloto, redes/escolas participantes e funções permitidas.
  - Confirmar que a primeira entrega é conversa síncrona, sem streaming, e que a IA não salva nem publica planos.
  - Bloqueia: AI-010, AI-040 e AI-060.
  - Aceite: decisão registrada e validada por responsáveis pedagógico, produto e tecnologia.

- [ ] **AI-002 — Definir política de dados e retenção**

  - Classificar dados enviados ao serviço e aos provedores.
  - Definir prazo de retenção de conversas, logs, documentos e exclusão.
  - Validar LGPD, transferência internacional e contrato/termos do provedor escolhido.
  - Bloqueia: AI-013, AI-022 e AI-063.
  - Aceite: política aprovada; payload permitido e dados proibidos documentados.

- [ ] **AI-003 — Selecionar e avaliar Qwen e Kimi para o piloto**

  - Criar conjunto de prompts anonimizados de planos MACETE.
  - Comparar qualidade pedagógica, fidelidade às fontes, latência, disponibilidade e custo.
  - Definir modelo padrão e eventual fallback manual.
  - Bloqueia: AI-031.
  - Aceite: resultado comparativo, modelo escolhido e credenciais de homologação disponíveis.

- [ ] **AI-004 — Criar repositório e pipeline do serviço IA**

  - Criar repositório independente `macete-ai-service` e padrão de branches/PR.
  - Configurar ambiente local, CI, lint, testes e build da imagem Docker.
  - Bloqueia: AI-010.
  - Aceite: serviço mínimo sobe localmente e CI executa checks sem segredos reais.

## Tarefas do serviço de IA — fundação Python

- [ ] **AI-010 — Criar a API FastAPI e healthcheck**

  - Criar aplicação, configuração tipada por ambiente e rota `GET /health`.
  - Incluir correlação de requisição e tratamento uniforme de erros.
  - Bloqueia: AI-011, AI-012, AI-013 e AI-020.
  - Aceite: container inicia, healthcheck não expõe segredos e testes básicos passam.

- [ ] **AI-011 — Provisionar PostgreSQL e migrations próprias**

  - Criar banco isolado do TAG e migrations do serviço.
  - Criar tabelas `conversations`, `messages`, `model_requests` e `retrieval_events`.
  - Não criar conexão, foreign key ou credencial para o MySQL do TAG.
  - Bloqueia: AI-012 e AI-021.
  - Aceite: migration sobe em banco vazio, é versionada e possui rollback/estratégia documentada.

- [ ] **AI-012 — Implementar isolamento por tenant e conversa**

  - Implementar criação, retomada e expiração de conversa.
  - Exigir `tenant_id` em toda operação e impedir leitura cruzada.
  - Persistir somente identificador pseudonimizado do ator.
  - Bloqueia: AI-020 e AI-052.
  - Aceite: testes provam que uma conversa de outro tenant retorna 404 sem vazamento.

- [ ] **AI-013 — Configurar segredos, autenticação e rede interna**

  - Armazenar token de serviço e chaves de provedor em secret manager/variáveis de ambiente.
  - Exigir TLS e `Authorization: Bearer` nas rotas internas.
  - Configurar mascaramento de segredos em logs e CI.
  - Bloqueia: AI-020 e AI-041.
  - Aceite: chamadas sem token são recusadas; nenhum segredo aparece em log, resposta ou imagem.

## Tarefas do serviço de IA — base de conhecimento e RAG

- [ ] **AI-020 — Modelar catálogo editorial de referências**

  - Criar `knowledge_documents` e campos de fonte, versão, vigência, autoridade, escopo e status.
  - Definir estados `DRAFT`, `UNDER_REVIEW`, `ACTIVE`, `INACTIVE` e `EXPIRED`.
  - Bloqueia: AI-021 e AI-022.
  - Aceite: somente documentos `ACTIVE` podem ser elegíveis a recuperação.

- [ ] **AI-021 — Habilitar pgvector e modelar trechos indexáveis**

  - Habilitar extensão `vector` no banco do serviço.
  - Criar `knowledge_chunks` com texto, página, seção, metadados e embedding.
  - Criar índices para filtros curriculares e busca vetorial.
  - Bloqueia: AI-023.
  - Aceite: consulta de similaridade filtrada por etapa e componente retorna somente trechos permitidos.

- [ ] **AI-022 — Implementar ingestão versionada de documentos**

  - Receber arquivo/origem, calcular hash e registrar a versão.
  - Extrair texto preservando página/seção; enviar OCR para revisão quando necessário.
  - Fragmentar por seção sem quebrar habilidades/tabelas e gerar embeddings.
  - Bloqueia: AI-023 e AI-024.
  - Aceite: uma fonte só fica `ACTIVE` após revisão; o histórico preserva versões inativas.

- [ ] **AI-023 — Implementar recuperação híbrida com filtros**

  - Filtrar antes da busca por tenant/escopo, status, etapa e componente.
  - Combinar código de habilidade/termos exatos com similaridade semântica.
  - Deduplicar, ordenar e limitar trechos antes de enviá-los ao modelo.
  - Bloqueia: AI-030.
  - Aceite: testes cobrem filtros, documento inativo, ausência de fonte e recuperação da fonte esperada.

- [ ] **AI-024 — Cadastrar e validar corpus inicial da BNCC**

  - Registrar a fonte oficial, versão, licença/uso e responsável editorial.
  - Executar ingestão, revisão por amostra e avaliação com perguntas anonimizadas.
  - Bloqueia: AI-030 e AI-061.
  - Aceite: corpus aprovado, com páginas/seções preservadas e conjunto de avaliação documentado.

## Tarefas do serviço de IA — agente e provedores

- [ ] **AI-030 — Definir schemas Pydantic de contexto e resposta**

  - Implementar `LessonPlanContext`, `ConversationMessage`, `AssistantReply`, `Proposal` e `SourceCitation`.
  - Permitir somente campos aplicáveis existentes no MACETE e operações `replace`/`append`.
  - Bloqueia: AI-031, AI-032 e AI-042.
  - Aceite: schema rejeita campos de propriedade, status, IDs, turma, etapas e habilidades.

- [ ] **AI-031 — Implementar adaptadores de Qwen e Kimi**

  - Criar contrato interno de provedor e adaptadores configuráveis por ambiente.
  - Configurar timeout, limite de tokens, tentativas controladas e mapeamento de erros.
  - Não habilitar streaming.
  - Bloqueia: AI-033.
  - Aceite: testes com cliente falso cobrem sucesso, timeout, rate limit e erro de provedor.

- [ ] **AI-032 — Implementar prompt e guardrails pedagógicos**

  - Incluir instruções de português do Brasil, papel de apoio, uso de fontes e recusa de dados de estudante.
  - Exigir declaração de incerteza quando não houver fonte suficiente.
  - Versionar o prompt e associar a versão à resposta/auditoria.
  - Bloqueia: AI-033.
  - Aceite: testes de prompt verificam que a saída não inventa fontes e não propõe alteração fora do contrato.

- [ ] **AI-033 — Implementar orquestração de conversa e resposta completa**

  - Montar contexto com snapshot, histórico limitado e trechos recuperados.
  - Chamar o provedor de modo síncrono e validar a resposta estruturada.
  - Persistir mensagem, fontes, modelo, latência e erro sanitizado.
  - Bloqueia: AI-034 e AI-043.
  - Aceite: resposta válida contém mensagem, propostas isoladas e fontes; falha não persiste alteração de plano.

- [ ] **AI-034 — Expor API de conversas do serviço**

  - Implementar `POST /v1/conversations` e `POST /v1/conversations/{id}/messages`.
  - Aplicar autenticação, validação, tenant e códigos de erro definidos no contrato.
  - Bloqueia: AI-043.
  - Aceite: OpenAPI publicado e testes de contrato cobrem `201`, `200`, `401`, `404`, `422`, `429`, `503` e `504`.

## Tarefas do TAG — integração com o serviço de IA

- [ ] **AI-040 — Adicionar configuração do serviço IA ao TAG**

  - Definir URL interna, timeout e token por variável de ambiente/configuração de instância.
  - Não armazenar chaves de Qwen/Kimi no TAG.
  - Bloqueia: AI-041 e AI-042.
  - Aceite: configuração ausente desativa o recurso com mensagem segura, sem quebrar o formulário.

- [ ] **AI-041 — Criar gateway PHP para o serviço IA**

  - Criar serviço em `app/modules/macete/services/`, por exemplo `MaceteAiAssistantGateway`.
  - Usar cliente HTTP com timeout, headers de serviço e normalização de erro.
  - Manter controllers finos e não fazer chamadas HTTP nas views/JavaScript.
  - Bloqueia: AI-043.
  - Aceite: testes com cliente falso comprovam serialização, timeout e ausência de segredo em exceções.

- [ ] **AI-042 — Criar resolvedor de contexto permitido do plano**

  - Reutilizar `MaceteAccessService`, `MaceteLessonPlanService` e `MaceteAbilityService` para montar contexto autorizado.
  - Incluir apenas etapa, componente, habilidade, rascunho e identificador pseudonimizado necessários.
  - Bloqueia: AI-043.
  - Aceite: usuário sem escopo/feature não gera contexto nem chamada externa; dados de aluno não aparecem no payload.

- [ ] **AI-043 — Criar endpoints AJAX do MACETE**

  - Adicionar ações de iniciar conversa e enviar mensagem a `LessonsplanController`.
  - Exigir POST, usuário autenticado, feature e proteção CSRF.
  - Regenerar `MaceteRoutes.php` pelo script do projeto.
  - Bloqueia: AI-050.
  - Aceite: endpoints retornam JSON estável, respeitam escopo e não salvam o plano.

## Tarefas do TAG — frontend do MACETE

- [ ] **AI-050 — Criar painel de conversa e estados visuais**

  - Criar painel lateral/recolhível e comportamento responsivo no formulário de plano.
  - Usar componentes e classes existentes do design system TAG.
  - Implementar estados de pré-requisito, carregamento, resposta, ausência de fonte e erro.
  - Bloqueia: AI-051 e AI-052.
  - Aceite: formulário continua utilizável durante a geração; não há componentes Bootstrap novos.

- [ ] **AI-051 — Implementar cliente AJAX e CSRF do painel**

  - Criar `lesson-plan-assistant.js`, usando somente as rotas internas do TAG.
  - Serializar snapshot saneado; manter `conversation_id` apenas na página em edição.
  - Escapar conteúdo retornado antes de renderizar.
  - Bloqueia: AI-052.
  - Aceite: token, URL interna do serviço e credenciais de provedor não chegam ao navegador.

- [ ] **AI-052 — Implementar aplicação granular de propostas**

  - Mapear explicitamente campos permitidos a seletores do formulário.
  - Aplicar uma proposta por vez, sincronizando `textarea` e Quill quando aplicável.
  - Bloquear propostas para status, relações, propriedade e IDs.
  - Bloqueia: AI-060.
  - Aceite: aplicar não envia formulário; salvar posterior usa o fluxo normal e validações atuais.

- [ ] **AI-053 — Garantir acessibilidade do assistente**

  - Implementar foco, labels, `aria-live`, ordem de teclado e mensagens não dependentes de cor.
  - Bloqueia: AI-060.
  - Aceite: fluxo de conversa, fontes e aplicação de proposta funcionam por teclado.

## Tarefas do serviço de IA — qualidade e avaliação

- [ ] **AI-060 — Executar verificações e testes do serviço de IA**

  - Executar testes unitários, de integração e de contrato com provedor falso.
  - Validar autenticação de serviço, isolamento por tenant, timeout e resposta estruturada.
  - Bloqueia: AI-063.
  - Aceite: evidências de checks anexadas ao PR do serviço; limitações documentadas.

- [ ] **AI-061 — Avaliar qualidade pedagógica e recuperação**

  - Executar conjunto de perguntas com fontes esperadas.
  - Revisar respostas e propostas com equipe pedagógica.
  - Ajustar corpus, metadados, prompt e modelo sem misturar resultados de produção.
  - Bloqueia: AI-063.
  - Aceite: critérios mínimos de fidelidade de fonte e qualidade pedagógica aprovados pelo piloto.

## Tarefas do TAG — qualidade da integração e frontend

- [ ] **AI-062 — Executar verificações e testes do TAG**

  - Testar o gateway PHP com cliente falso, contexto permitido e normalização de erro.
  - Testar o JavaScript do painel, a aplicação de propostas e a sincronização com Quill.
  - Executar `composer run lint`, `composer run analyse` e `composer run mess` quando aplicáveis.
  - Bloqueia: AI-063.
  - Aceite: evidências de checks anexadas ao PR do TAG; limitações documentadas.

## Tarefas compartilhadas — homologação e liberação

- [ ] **AI-063 — Realizar homologação E2E autorizada**

  - Antes do teste, obter URL, credenciais, dados de pré-condição e aprovação do plano de testes.
  - Cobrir criação de conversa, erro do serviço, aplicação de proposta, salvamento e bloqueio por permissão.
  - Bloqueia: AI-064.
  - Aceite: cenários obrigatórios aprovados, com evidência por cenário.

- [ ] **AI-064 — Preparar operação e ativar piloto com feature flag**

  - Criar runbook de deploy, rollback, rotação de segredo, retenção e suporte.
  - Configurar métricas, alertas e limites de consumo.
  - Ativar para grupo piloto por configuração de instância/feature flag.
  - Aceite: plano de suporte e rollback aprovados; ativação não altera o acesso de usuários fora do piloto.

## Ordem recomendada

```text
AI-001..004
  → AI-010..013
  → AI-020..024
  → AI-030..034
  → AI-040..043
  → AI-050..053
  → AI-060..064
```

As tarefas AI-020..024 e AI-040..042 podem avançar em paralelo após a fundação do serviço, desde que os contratos de `AI-030` sejam estabilizados antes de integrar endpoints e frontend.
