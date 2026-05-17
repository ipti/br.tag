# Plano de Trabalho: Correção da Exportação Educacenso 2026

**Branch:** `fix/censo-export-2026`
**Data:** 2026-05-17

---

## Contexto

O commit `c8a5e657f` implementou as mudanças do layout 2026 do Educacenso, mas utilizou contadores de campos incorretos para os registros 10 e 30. O layout oficial (Matrícula Inicial 2026 v3) especifica:

| Registro | Oficial 2026 | Implementação atual | Diferença |
|---|---|---|---|
| 00 | 53 | 53 | ✅ OK |
| 10 | **187** | **182** | ❌ −5 campos |
| 20 | 66 | 66 | ✅ OK |
| 30 | **110** | **108** | ❌ −2 campos |
| 40 | 7 | 7 | ✅ OK |
| 50 | 38 | 38 | ✅ OK |
| 60 | 33 | 33 | ✅ OK |

**Causa raiz:** os limites errados estão em dois lugares simultâneos:
1. `EducacensoRegisterFormatter.php` — constante que corta o output no export
2. `censo_2026_export_aliases.sql` — DELETE final que prune aliases além do limite (está descartando aliases válidos)

---

## Pré-requisito (bloqueador)

Consultar as abas **"Registro 10"** e **"Registro 30"** do Excel de layout 2026 para identificar:

- Os **5 campos novos** do R10 (corders 183–187): nome, `attr` no banco, tipo, obrigatoriedade, valores permitidos
- Os **2 campos novos** do R30 (corders 109–110): idem

Sem esses dados a Tarefa 3 não pode ser concluída.

---

## Tarefa 1 — Corrigir constantes no formatter

**Arquivo:** `app/libraries/Educacenso/EducacensoRegisterFormatter.php` (linhas 8–9)

Alterar:

```php
// Antes
10 => 182,
30 => 108,

// Depois
10 => 187,
30 => 110,
```

Nenhuma outra mudança é necessária neste arquivo.

---

## Tarefa 2 — Corrigir o DELETE de pruning na migration

**Arquivo:** `app/migrations/3.13.13/censo_2026_export_aliases.sql` (linhas 103–104)

Alterar os limites no bloco DELETE final:

```sql
-- Antes
OR (register = 10 AND corder > 182)
OR (register IN (301, 302) AND corder > 108)

-- Depois
OR (register = 10 AND corder > 187)
OR (register IN (301, 302) AND corder > 110)
```

---

## Tarefa 3 — Adicionar aliases dos campos ausentes na migration

Ainda no arquivo de migration, **antes do `COMMIT`**, inserir os registros que descrevem os novos campos.

> Preencher `<attr>`, `<cdesc>` e `<default>` com base nas abas do Excel de layout 2026.

**Registro 10 — 5 campos novos (corders 183–187):**

```sql
INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES
  (10, 183, '<attr_183>', '<descricao>', '<default>', 1, 2026),
  (10, 184, '<attr_184>', '<descricao>', '<default>', 1, 2026),
  (10, 185, '<attr_185>', '<descricao>', '<default>', 1, 2026),
  (10, 186, '<attr_186>', '<descricao>', '<default>', 1, 2026),
  (10, 187, '<attr_187>', '<descricao>', '<default>', 1, 2026);
```

**Registro 30 — 2 campos novos (corders 109–110):**

Inserir para `register = 301` (aluno) e `register = 302` (professor) se ambos compartilham os novos campos:

```sql
INSERT INTO edcenso_alias (register, corder, attr, cdesc, `default`, stable, `year`)
VALUES
  (301, 109, '<attr_109>', '<descricao>', '<default>', 1, 2026),
  (301, 110, '<attr_110>', '<descricao>', '<default>', 1, 2026),
  (302, 109, '<attr_109>', '<descricao>', '<default>', 1, 2026),
  (302, 110, '<attr_110>', '<descricao>', '<default>', 1, 2026);
```

---

## Tarefa 4 — Revisar lógica de export nos Register files

### Register10.php

**Arquivo:** `app/libraries/Educacenso/Register10.php`

Verificar se algum dos 5 novos campos (corders 183–187) precisa de derivação especial (como os campos 115 e 117 que usam `deriveInternetDeviceAccess` e `deriveLocalNetworkAccess`).

Se sim, adicionar um `elseif` no loop de aliases (linha ~297) e/ou no bloco `if ((int) $year === 2026)` (linha ~326). Se os novos campos mapeiam diretamente para atributos do banco via `edcenso_alias.attr`, nenhuma mudança é necessária.

### Register30.php

**Arquivo:** `app/libraries/Educacenso/Register30.php`

Verificar se os campos 109 e 110 se aplicam a aluno (`register = 301`), professor (`register = 302`), ou ambos, e se precisam de tratamento condicional.

A linha do gestor (linha ~604) é normalizada automaticamente pelo `EducacensoRegisterFormatter::formatLine`, então campos vazios no final serão preenchidos com `||` sem necessidade de mudança manual naquele bloco.

---

## Tarefa 5 — Validar e aplicar a migration

```bash
# Dry-run primeiro
composer run migrate:dry app/migrations/3.13.13/censo_2026_export_aliases.sql

# Revisar o output, então aplicar
composer run migrate app/migrations/3.13.13/censo_2026_export_aliases.sql
```

> **Atenção:** se a migration já foi aplicada em banco de desenvolvimento, criar um script corretivo separado `app/migrations/3.13.13/censo_2026_export_aliases_fix.sql` ao invés de alterar o original.

---

## Tarefa 6 — Verificar formatação e análise estática

```bash
composer run lint
composer run analyse
```

Corrigir qualquer aviso antes de abrir o PR.

---

## Checklist de encerramento

- [ ] `EducacensoRegisterFormatter::EXPECTED_FIELD_COUNTS_BY_YEAR` usa 187 para R10 e 110 para R30
- [ ] Migration SQL usa os limites corretos no DELETE final
- [ ] Aliases dos campos 183–187 (R10) e 109–110 (R30) estão na migration
- [ ] `Register10.php` e `Register30.php` exportam os novos campos corretamente
- [ ] Migration aplicada com sucesso (sem erros no dry-run)
- [ ] `composer run lint` e `composer run analyse` passam sem erros
- [ ] PR segue o playbook `.agents/playbooks/release.md`
