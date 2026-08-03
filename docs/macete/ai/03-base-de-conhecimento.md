# Base de conhecimento e RAG

Status: especificação de dados e curadoria — não implementado.

## Objetivo

Dar ao agente referências curriculares verificáveis sem incorporar documentos inteiros no prompt ou “treinar” o modelo a cada atualização. A recuperação deve selecionar trechos relevantes, filtrados pelo contexto pedagógico, e apresentá-los junto à resposta.

## Fontes e prioridade

| Nível | Tipo | Exemplo | Regra de uso |
| --- | --- | --- | --- |
| 1 | Normativa nacional | BNCC, diretrizes e legislação | Fonte prioritária para alegações curriculares. |
| 2 | Currículo de rede | Documento municipal/estadual aprovado | Complementa e contextualiza, sem contrariar o nível 1. |
| 3 | Material institucional | Guia MACETE, formação da rede | Orienta metodologia e recursos locais. |
| 4 | Complementar aprovada | Referência pedagógica licenciada | Apoia sugestões; não fundamenta obrigação normativa. |

Fontes externas livres e busca na internet não integram a primeira entrega.

## Modelo editorial

Uma referência só pode ser recuperada quando estiver com situação `ACTIVE`. O ciclo é:

```mermaid
flowchart LR
  U[Upload ou cadastro de fonte] --> V[Validação técnica]
  V --> R[Revisão pedagógica]
  R --> A[Ativação e indexação]
  A --> S[Uso pelo agente]
  S --> X[Substituição ou expiração]
```

Campos obrigatórios para cada versão:

- título, órgão/autoria, URL/origem e licença/uso autorizado;
- versão, data de publicação, vigência e situação;
- nível de autoridade e escopo territorial;
- etapa, ano/série, componente e habilidades, quando identificáveis;
- hash do arquivo original e responsável pela aprovação;
- páginas/seções de origem preservadas.

Documentos substituídos não são apagados do histórico: tornam-se `INACTIVE` e deixam de ser recuperados para novas respostas.

## Pipeline de ingestão

1. Armazenar o arquivo original e registrar a versão editorial.
2. Extrair texto preservando página, título, subtítulo e, quando possível, tabelas.
3. Aplicar OCR somente quando necessário e enviar o resultado para conferência humana.
4. Dividir o conteúdo por unidade semântica, preferindo seção/subseção; nunca quebrar uma habilidade ou tabela de forma arbitrária.
5. Acrescentar metadados curriculares e de permissão a cada trecho.
6. Gerar embedding com modelo multilíngue aprovado e salvar texto, metadados e vetor.
7. Executar amostra de recuperação e revisão pedagógica antes de ativar a versão.

Uma falha de extração não deve permitir que um PDF seja ativado automaticamente.

## Operação administrativa

O serviço de IA possui um painel editorial server-rendered em `/admin`, protegido por token administrativo próprio, cookie de sessão assinado e CSRF. Ele aceita Markdown UTF-8 por texto ou arquivo, cria a versão como `DRAFT` e permite registrar submissão, aprovação/ativação ou rejeição. O token do painel é diferente da credencial de serviço usada pelo TAG; enquanto a configuração administrativa não existe, a rota retorna `404`.

## Recuperação em tempo de conversa

1. Transformar a pergunta e o snapshot do plano em consulta.
2. Aplicar filtros obrigatórios: `tenant/scope`, documento ativo, idioma, etapa e componente quando disponíveis.
3. Executar busca híbrida: termos exatos relevantes (como código de habilidade) e similaridade semântica.
4. Reordenar e deduplicar os melhores trechos.
5. Entregar ao agente trecho, fonte, versão, seção, página e URL; limitar o volume para caber no contexto do modelo.
6. Registrar os IDs dos trechos efetivamente usados na resposta.

Os códigos e descrições das habilidades já selecionadas no TAG são contexto estruturado direto. Eles não devem depender de recuperação vetorial.

## Dados mínimos do índice

```text
knowledge_documents
  id, title, authority_level, source_url, version, status, effective_from, effective_to

knowledge_chunks
  id, document_id, content, page, section, chunk_order, embedding,
  stage_ids, discipline_ids, ability_codes, territory_scope, language
```

Índices devem atender aos filtros de metadados usados com frequência e à busca por embeddings. A consulta sempre deve filtrar autorização/escopo antes de devolver trechos ao agente.

## Fontes na resposta

Cada resposta que usar RAG deve trazer as fontes com título, versão, seção e página. Caso nenhuma fonte seja adequada, o agente deve explicitar a limitação e evitar alegação de conformidade curricular.

## Qualidade da recuperação

Antes de ativar a base, montar um conjunto de avaliação com perguntas reais e anonimizadas de professores. Para cada pergunta, registrar a fonte esperada e verificar se ela aparece nos primeiros resultados. A avaliação da recuperação é independente da qualidade de redação do modelo.
