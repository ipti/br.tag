# Plano de Trabalho — Correção: Justificativa de Falta do Professor

**Data:** 2026-05-10  
**Autor:** Gabriel Chagas  
**Projeto:** br.tag  

---

## 1. Descrição do Problema

Na tela de **Frequência de Professor**, ao clicar no ícone de justificativa e salvar, os campos `instructorId`, `day` e `month` chegam vazios ao servidor. O UPDATE executado pelo backend usa `instructor = 0`, não afetando nenhum registro.

Evidência: screenshot mostrando payload `instructorId: ""`, `day: ""`, `month: ""` na requisição POST para `/?r=instructor/saveJustification`.

---

## 2. Diagnóstico / Causa Raiz

**Arquivo:** `js/instructor/frequency.js`, linha 57

```javascript
$("#frequency-container").html(DOMPurify.sanitize(html)).show();
```

O `DOMPurify.sanitize()` **remove atributos HTML customizados** (`instructorId`, `day`, `month`) dos elementos `<input>` antes de inseri-los no DOM, pois esses atributos não constam na lista de atributos permitidos pelo DOMPurify por padrão.

**Consequência em cascata:**
- `checkbox.attr("instructorid")` → retorna `undefined` / `""`
- `checkbox.attr("day")` → retorna `undefined` / `""`
- `checkbox.attr("month")` → retorna `undefined` / `""`

Isso afeta **dois** handlers:
1. `saveFrequency` (marcar/desmarcar falta) — envia valores vazios; o controller faz `if (empty($_POST['instructorId'])) return;` e silenciosamente não salva nada.
2. `saveJustification` (salvar justificativa) — envia valores vazios; o UPDATE não afeta nenhum registro.

---

## 3. Contexto Técnico Relevante

A lógica de frequência foi refatorada para trabalhar **por dia** (não por horário individual). O `actionSaveFrequency` e o `actionSaveJustification` no `InstructorController.php` já estão corretos e propagam a falta/justificativa para **todos os horários do professor naquele dia** via JOIN entre `instructor_faults`, `schedule` e `classroom`. Nenhuma alteração é necessária no backend.

---

## 4. Solução

Substituir os atributos customizados (`instructorId`, `day`, `month`) por atributos `data-*` (`data-instructor-id`, `data-day`, `data-month`), que são **preservados pelo DOMPurify** por padrão.

---

## 5. Tarefas de Implementação

### Arquivo: `js/instructor/frequency.js`

---

#### Tarefa 1 — Trocar atributos na geração do HTML (linhas 50–52)

**Antes:**
```javascript
" instructorId='" + instructor.instructorId + "'" +
" day='" + schedule.day + "'" +
" month='" + $("#month").val() + "'>" +
```

**Depois:**
```javascript
" data-instructor-id='" + instructor.instructorId + "'" +
" data-day='" + schedule.day + "'" +
" data-month='" + $("#month").val() + "'>" +
```

---

#### Tarefa 2 — Atualizar handler `saveFrequency` (linhas 89–91)

**Antes:**
```javascript
instructorId: $(this).attr("instructorid"),
day: $(this).attr("day"),
month: $(this).attr("month"),
```

**Depois:**
```javascript
instructorId: $(this).data("instructor-id"),
day: $(this).data("day"),
month: $(this).data("month"),
```

---

#### Tarefa 3 — Atualizar handler do clique na justificativa (linhas 116–118)

**Antes:**
```javascript
$("#justification-instructorid").val(checkbox.attr("instructorid"));
$("#justification-day").val(checkbox.attr("day"));
$("#justification-month").val(checkbox.attr("month"));
```

**Depois:**
```javascript
$("#justification-instructorid").val(checkbox.data("instructor-id"));
$("#justification-day").val(checkbox.data("day"));
$("#justification-month").val(checkbox.data("month"));
```

---

#### Tarefa 4 — Atualizar seletor jQuery no `success` do `saveJustification` (linha 144)

**Antes:**
```javascript
let justification = $(".table-frequency tbody .frequency-checkbox[instructorid=" + $("#justification-instructorid").val() + "][day=" + $("#justification-day").val() + "][month=" + $("#justification-month").val() + "]").parent().find(".frequency-justification-icon");
```

**Depois:**
```javascript
let justification = $(".table-frequency tbody .frequency-checkbox[data-instructor-id=" + $("#justification-instructorid").val() + "][data-day=" + $("#justification-day").val() + "][data-month=" + $("#justification-month").val() + "]").parent().find(".frequency-justification-icon");
```

---

### Arquivo: `app/controllers/InstructorController.php`

**Nenhuma alteração necessária.** O backend já está correto:
- `actionSaveFrequency` (linhas 778–823): busca todos os schedules do professor no dia e cria/deleta faults para cada um.
- `actionSaveJustification` (linhas 825–849): executa UPDATE em todos os `instructor_faults` do professor no dia/mês/ano via JOIN.

---

## 6. Resumo das Alterações

| Arquivo | Tipo | Descrição |
|---|---|---|
| `js/instructor/frequency.js` | Bug fix | Trocar `instructorId`/`day`/`month` → `data-instructor-id`/`data-day`/`data-month` em 4 locais |
| `app/controllers/InstructorController.php` | Sem alteração | Lógica já correta |

---

## 7. Critérios de Aceite

- [ ] Ao marcar uma falta, o POST para `saveFrequency` envia `instructorId`, `day` e `month` com valores corretos.
- [ ] A falta é salva para **todos os horários** do professor naquele dia.
- [ ] Ao clicar no ícone de justificativa, o modal abre com os dados corretos.
- [ ] Ao salvar a justificativa, o POST para `saveJustification` envia `instructorId`, `day` e `month` com valores corretos.
- [ ] A justificativa é aplicada a **todos os registros** `instructor_faults` do professor naquele dia.
- [ ] O ícone de justificativa é atualizado visualmente após salvar.
