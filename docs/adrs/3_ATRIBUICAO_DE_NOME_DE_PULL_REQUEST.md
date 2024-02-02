---
status: Accepted
date: 01-02-2024
deciders: Igor, Gustavo,
---

# Atribuição de nome de pull request

## Contexto

Padronizar os nomes de pull requests e consequentemente os nomes de branchs é fundamental, pois a padronização facilita o entedimento, a clareza e a revisão dos pull requests, dessa forma, a equipe terá um entendimento claro e acertivo para com os nomes de pull request e também poderá acessá-los com maior facilidade.

## Motivadores de decisão

* Evitar ambiguidade e divergências no padrão de pull request

## Opções consideradas

1. Compor os nomes de pull request da seguinte forma: tipo/descrição.
2. Compor os nomes de pull request da seguinte forma: tipo-descrição-codigoTarefaJira

## Resultado da decisão

Compor os nomes de pull request da seguinte forma: tipo/descrição. O tipo sendo qual a categoria que o pull request se encaixa, sendo eles:
* docs: Mudanças na documentação
* feat: Novas funcionalidades
* fix: Correção de bugs
* test: Adicionar ou corrigir testes
* refactor: Refatorações no código que não adicionem uma nova funcionalidade e que também não corrijam bugs
A descrição do pull request deve ser sucinta e as palavras devem ser separadas por underscores "_".

### Consequências

* Com a implementação escolhida, a comunicação da equipe se tornou mais acertiva, evitando falhas e confusões relacionadas a atribuição de nome das branchs e dos pull requests. Ademais, a busca e listagem de pull request se tornou mais legível e precisa.

## More Information

*
*
