---
id: RX-AREA-01
title: "Título curto, com verbo no imperativo"
release: RX
module: plataforma            # módulo Yii (app/modules/<nome>), "raiz" (app/controllers) ou "plataforma"
type: refactor                # seguranca | fundacao | refactor | testes | api | frontend | css | docs | epico | refinamento
skill: tag-extract-service    # skill em .agents/skills/ ou playbook em .agents/playbooks/<arquivo>.md
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: rascunho              # rascunho | pronto | em-andamento | em-revisao | concluido | bloqueado
depends_on: []
size: P                       # P | M | G
risk: medio                   # baixo | medio | alto
human_review: recomendada     # obrigatoria | recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção X"
write_scope:
  - caminho/que/pode/ser/alterado/**
read_only:
  - caminho/só/para/consulta/**
forbidden:
  - composer.json
  - app/config/main.php
acceptance:
  - "comando que precisa passar"
---

# RX-AREA-01: Título

## Contexto
Explique por que este card existe e aponte para o plano, os arquivos e as linhas relevantes. Escreva para quem não acompanhou a conversa que originou o card. Use só fatos que dá para conferir no código.

## Objetivo
Uma ou duas frases sobre o resultado esperado.

## Passos
1. …

## Fora de escopo
- …

## Critérios de aceite
- [ ] …

## Como verificar
Comandos e passos manuais, cada um com o resultado esperado. Testes de navegador seguem `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação.

## Handoff
_Preenchido por quem executar o card:_
- _o que foi feito;_
- _decisões tomadas;_
- _gates rodados (comando → resultado);_
- _pendências;_
- _riscos._
