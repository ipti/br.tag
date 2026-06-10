# Migração: GradesStructureController → `app/modules/gradeconcept/`

## Objetivo

Mover o gerenciamento de estruturas de avaliação (regras de nota, unidades, recuperação) do controller legado `GradesStructureController` para o módulo `gradeconcept`.

---

## Origem

**Arquivo:** `app/controllers/GradesStructureController.php`
**Tamanho:** ~20 KB
**Layout:** `fullmenu`

---

## Destino

**Módulo:** `app/modules/gradeconcept/`
**Já existente:** Sim
**Layout do módulo:** `webroot.themes.default.views.layouts.fullmenu`
**Controller atual:** `app/modules/gradeconcept/controllers/DefaultController.php`

---

## Análise das Ações

| Ação | HTTP | Parâmetros | Propósito |
|---|---|---|---|
| `actionIndex()` | GET | — | Lista estruturas de avaliação filtradas por ano |
| `actionCreate()` | GET/POST | form data | Cria/edita estrutura de avaliação (unidades, modalidades) |
| `actionDelete($id)` | GET | `$id` | Exclui estrutura com validações de segurança |
| `actionGetunities()` | POST (AJAX) | `grade_rules_id` | Retorna unidades de uma estrutura em JSON |
| `actionSaveunities()` | POST | dados complexos de formulário | Salva estrutura completa via usecase |
| `actionCopy($id, $year)` | GET | `$id`, `year` | Copia estrutura para outro ano letivo |

---

## Lógica de Negócio Crítica

### Validações de exclusão (`actionDelete`)

Bloqueia exclusão se:
- Existem notas lançadas nas unidades da estrutura (`Grade` table com JOIN em `GradeUnityModality`)
- Existem notas lançadas em recuperações parciais
- A estrutura está vinculada a turmas (`ClassroomVsGradeRules`)
- `created_at < 2025` — estruturas antigas não podem ser excluídas

### Tipos de unidade (constantes `GradeUnity`)

```
TYPE_UNITY (U)                    — unidade normal
TYPE_UNITY_BY_CONCEPT (UC)        — avaliação por conceito
TYPE_UNITY_WITH_RECOVERY (UR)     — unidade com recuperação
TYPE_FINAL_RECOVERY (RF)          — recuperação final
TYPE_FINAL_CONCEPT                — conceito final
```

### Salvar estrutura (`actionSaveunities`)

Usa `UpdateGradeStructUsecase`:
- Define `school_year` em novos registros via `Yii::app()->user->year`
- Cria recuperação final via `handleFinalRecovery()`
- Cria conceito final via `handleFinalConcept()` quando aplicável

### Copiar estrutura (`actionCopy`)

Usa `CopyGradeStructUsecase` com parâmetro de ano.

---

## Usecases Utilizados

```php
UpdateGradeStructUsecase
CopyGradeStructUsecase
```

Verificar onde estão definidos — podem estar em `app/usecases/` ou já dentro de algum módulo.

---

## Modelos Usados

- `GradeRules::model()` (entidade principal)
- `GradeUnity::model()`
- `GradeUnityModality::model()`
- `Grade::model()` (verificação de notas existentes)
- `GradePartialRecovery::model()`
- `GradePartialRecoveryWeights::model()`
- `GradeCalculation::model()`
- `EdcensoStageVsModality::model()`
- `CurricularMatrix::model()`
- `ClassroomVsGradeRules::model()`
- `GradeRulesVsEdcensoStageVsModality::model()`

Todos modelos compartilhados em `app/models/` — **não mover**.

---

## Views a Mover

### Atenção: Views usam path compartilhado `//admin/`

```php
$this->render('//admin/indexGradesStructure', [...]);
$this->render('//admin/gradesStructure', [...]);
```

As views estão em `themes/default/views/admin/` (não em `app/views/gradesstructure/`). Isso significa:
- As views usam o path de layouts global, não path relativo ao controller
- Não precisam ser movidas, mas a referência `//admin/` deve continuar funcionando no contexto do módulo

**Verificar se `$this->render('//admin/...')` funciona dentro de um módulo Yii 1.1 sem ajustes.**

---

## Situação do Módulo `gradeconcept` Atual

Verificar o conteúdo do `DefaultController.php` do módulo para identificar sobreposição. Com base no nome do módulo (`gradeconcept`), é provável que gerencie avaliação por conceitos — domínio relacionado mas não idêntico ao `GradesStructureController`.

---

## Referências de Rota a Atualizar

Buscar no repositório:
```
?r=gradesStructure/
?r=gradesstructure/
createUrl('gradesStructure/
createUrl('gradesstructure/
```

Verificar especialmente:
- Links de criação/edição de estrutura de avaliação nas views de turma (`ClassroomController`)
- Links do menu de configuração escolar

---

## Riscos e Observações

- **Risco médio** — estrutura de avaliação é configuração crítica; erros afetam lançamento de notas
- As views usam path `//admin/` compartilhado — comportamento dentro de módulo precisa ser verificado
- Os usecases `UpdateGradeStructUsecase` e `CopyGradeStructUsecase` precisam ser localizados e garantidos no contexto do módulo
- O filtro por ano (`created_at >= 2025`) para novas regras precisa ser preservado
- Verificar se `DefaultController` do `gradeconcept` já cobre alguma dessas ações antes de migrar

---

## Passos de Migração

1. Ler `DefaultController.php` do módulo `gradeconcept` para avaliar sobreposição
2. Localizar `UpdateGradeStructUsecase` e `CopyGradeStructUsecase`
3. Testar se `$this->render('//admin/...')` funciona dentro de um module controller Yii 1.1
4. Criar `GradesStructureController.php` em `app/modules/gradeconcept/controllers/`
5. Garantir imports dos usecases no módulo
6. Buscar e atualizar referências `?r=gradesStructure/` em PHP, JS e layouts
7. Testar: criação de estrutura, adição de unidades, cópia para novo ano, exclusão com validações
8. Remover `app/controllers/GradesStructureController.php` após validação

---

## Checklist de Encerramento

- [ ] `DefaultController` do módulo analisado (sem conflito de ações)
- [ ] `GradesStructureController` criado em `modules/gradeconcept/controllers/`
- [ ] Usecases acessíveis no contexto do módulo
- [ ] Views `//admin/indexGradesStructure` e `//admin/gradesStructure` funcionando no módulo
- [ ] Rotas `?r=gradesStructure/` atualizadas
- [ ] Validações de exclusão (notas existentes, turmas vinculadas, `created_at`) funcionando
- [ ] Cópia de estrutura para novo ano testada
- [ ] Controller legado removido
