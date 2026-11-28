---
id: R3-FRONT-02
title: "frontend/validators: portar CPF, data, CEP, NIS, CNS e regras do Educacenso com testes de caracterização"
release: R3
module: plataforma
type: frontend
skill: tag-js-page-migration
agents: [tag-qa, tag-refactorer, tag-plan-guardian]
status: pronto
depends_on: [R3-FRONT-01]
size: P
risk: medio
human_review: recomendada
jira: ""
plan_ref: "docs/arquitetura/plano-modernizacao.md, seção 7 (F3: validators) e seção 8.5 (bug encontrado vira issue)"
write_scope:
  - frontend/validators/**
read_only:
  - themes/default/js/util.js
  - frontend/core/**
  - tests/providers/CustomProvider.php
forbidden:
  - composer.json
  - app/config/main.php
  - themes/default/views/layouts/**
  - themes/default/js/**
  - sass/scss/main.scss
  - .github/workflows/**
acceptance:
  - "npm run lint && npm run typecheck && npm test -- validators"
  - "npm run build"
  - "git diff --stat main...HEAD -- themes/   (vazio: o util.js não muda neste card)"
---

# R3-FRONT-02: frontend/validators: portar CPF, data, CEP, NIS, CNS e regras do Educacenso com testes de caracterização

## Contexto
As validações do formulário de aluno, professor, escola e turma vivem em `themes/default/js/util.js` (546 linhas), carregado pelos layouts `fullmenu` e `reports`. O R3-FRONT-04 vai trocar esse arquivo pela ponte legada, e a ponte precisa chamar validators novos e testados. Este card cria os validators; ele **não** muda o `util.js`.

O que existe hoje (verificado) e define o contrato a preservar:
- `rule(string, exp)` (≈L6) faz `string.match(exp)`: com valor que não é string lança `TypeError`. Os validators novos são tipados para `string`; entradas não string ficam fora do contrato.
- Data: `dateRules.date` (≈L15) aceita dia 01–31, mês 01–12 e ano `[12][0-9]{3}` ou 2 dígitos. **Aceita 31/02 e 31/04** e anos de 2 dígitos. `validateDate` (≈L74) é só essa regex; `validateYear` (≈L45) usa `1900` e o ano corrente como padrão.
- `validateBirthdayPerson` (≈L170) faz `new Date("dd/mm/aaaa")`, que o navegador interpreta como mês/dia ou rejeita, e limita o ano a 1918–1999.
- CPF: `validateCpf` (≈L294–L343) devolve `{valid, message}`, recusa as 10 sequências repetidas, confere os dois dígitos e exige 11 dígitos (`numberRules.cpf`, ≈L35). Tem blocos redundantes no fim.
- CEP: `validateCEP` (≈L114) = `numbersNotEqual` (não pode ser tudo igual) + `^[0-9]{8}$`.
- NIS e CNS (≈L386–L392) só conferem o tamanho (11 e 15 dígitos), sem dígito verificador.
- Regras do Educacenso em `stringRules` (≈L17–L31: nome de escola e de turma em maiúsculas com `°ºª-` e espaço, 4–100 e 4–80 caracteres; endereços, RG, e-mail) e `numberRules` (≈L33–L42: DDD, telefone de 8 ou 9 dígitos, `count`, `num`), mais `validateCount`, `validateLogin`, `validatePassword` e `compareSimilarName` (≈L180, que vaza as globais `name_test_1` e `name_test_2`).
- `stringRules.personName` (≈L20) **não tem `$`**, então aceita sufixo inválido; a mensagem de `validateNamePerson` (≈L267) fala em "maiúsculos de A a Z", mas a regex aceita minúsculas e acentos. A checagem de letra repetida usa `until4 > 4` (≈L252) com mensagem de "4 vezes" (≈L254).
- As quatro funções com AJAX (`validateNamePerson`, `existsStudentWithCPF`, `validateCivilCertificationTermNumber`, `validateCivilRegisterEnrollmentNumber`, ≈L202–L384) montam `?r=student/...` à mão com `origin`/`pathname` globais e usam `.success`. Ficam fora deste card: migram com a página do aluno, sobre o `core/http` e as rotas geradas.

## Objetivo
Validators puros em `frontend/validators/`, com o mesmo comportamento do `util.js` provado por testes lado a lado, e a lista dos defeitos encontrados registrada para decisão de produto.

## Passos
1. Crie, como módulos ES com JSDoc (`checkJs` estrito): `date.js` (`isValidDate`, `isValidYear`, `stringToDate`, `isValidBirthday`), `cpf.js` (`validateCpf` com o mesmo retorno `{valid, message}`), `cep.js`, `nis.js`, `cns.js`, `educacenso.js` (as regras de `stringRules`/`numberRules` como constantes exportadas e as funções `validate*` correspondentes), `similar-name.js` (`compareSimilarName` sem globais) e `index.js`.
2. **Porte fiel.** Mantenha as mesmas expressões regulares e a mesma ordem das checagens. Não corrija 31/02, o ano de 2 dígitos, o `$` ausente, o `until4 > 4` nem os limites de 1918–1999: cada um vira issue.
3. `tag-qa`: testes de caracterização que carregam o `themes/default/js/util.js` real no jsdom (leitura do arquivo e `window.eval`) e rodam o código antigo e o novo com as mesmas entradas:
   - tabelas à mão com os casos-limite (CPF válido e inválido, 11 dígitos repetidos, 10 e 12 dígitos, data com 31/02, 29/02 em ano bissexto e não bissexto, ano de 2 dígitos, CEP `00000000`, NIS e CNS com 10, 11, 14, 15 e 16 dígitos, nomes com acento e minúsculas);
   - entradas pseudoaleatórias com gerador **semeado** (por exemplo 2.000 por validator), para a execução ser determinística;
   - CPFs, NIS e CNS fictícios, gerados como o `tests/providers/CustomProvider.php` faz (nunca dados reais).
4. Um teste explícito por defeito, no estilo do `tag-qa`: o de caracterização afirma o comportamento atual; o esperado vai com `it.todo('Bug: <issue>')`.
5. Exporte os validators pelo `index.js` com nomes estáveis; o R3-FRONT-04 os ligará aos nomes globais antigos.

## Fora de escopo
- Alterar `util.js` ou qualquer layout (R3-FRONT-04).
- Corrigir os defeitos, adicionar dígito verificador de NIS e CNS ou mudar mensagens.
- As quatro funções com AJAX e as de apresentação de erro (`addError`, `errorMessage`…; o `ui/form-errors.js` do R3-FRONT-01 as substitui).

## Critérios de aceite
- [ ] Para cada função exportada, os testes lado a lado com o `util.js` passam com as tabelas e com as entradas semeadas.
- [ ] Nenhum teste de caracterização foi enfraquecido para passar.
- [ ] Os defeitos (31/02, ano de 2 dígitos, `validateBirthdayPerson`, `$` ausente em `personName`, `until4`, mensagem de maiúsculas, globais de `compareSimilarName`) estão listados no Handoff como issues sugeridas, com o comportamento atual e o esperado.
- [ ] `frontend/validators` não usa jQuery, `window`, nem globais.
- [ ] `npm run lint`, `typecheck`, `test` e `build` passam; `themes/` ficou intacto.

## Como verificar
```bash
npm run lint && npm run typecheck
npm test -- validators
npm run build
git diff --stat main...HEAD -- themes/    # vazio
```

## Handoff
_Preenchido por quem executar._
