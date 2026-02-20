# DataTableGridView - Guia de Uso

## Visão Geral

`DataTableGridView` é um componente wrapper do `CGridView` do Yii que integra automaticamente o DataTables, fornecendo funcionalidades avançadas de tabela com configuração mínima.

## Uso Básico

```php
<?php $this->widget('application.components.widgets.DataTableGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        'id',
        'name',
        'created_at',
    ),
)); ?>
```

## Com Filtros (CGridView)

```php
<?php $this->widget('application.components.widgets.DataTableGridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,  // Habilita filtros por coluna
    'columns' => array(
        array('name' => 'id', 'filter' => false),
        array('name' => 'name'),
        array('name' => 'status', 'filter' => CHtml::activeDropDownList($model, 'status', 
            array('active' => 'Ativo', 'inactive' => 'Inativo'),
            array('empty' => 'Todos')
        )),
    ),
)); ?>
```

## Opções Disponíveis

### Opções do Componente

| Opção | Tipo | Padrão | Descrição |
|-------|------|--------|-----------|
| `enableDataTable` | bool | `true` | Habilita/desabilita DataTables |
| `searchPlaceholder` | string | `'Pesquisar registros'` | Texto do placeholder da busca |
| `hasFilters` | bool | auto-detectado | Se a grid tem filtros CGridView |
| `datatableOptions` | array | `[]` | Opções customizadas do DataTables |

### Opções do DataTables

Você pode passar qualquer opção do DataTables via `datatableOptions`:

```php
'datatableOptions' => array(
    'order' => [[0, 'desc']],           // Ordenação inicial
    'pageLength' => 25,                  // Registros por página
    'searching' => true,                 // Habilitar busca
    'paging' => true,                    // Habilitar paginação
    'info' => true,                      // Mostrar informações
    'lengthChange' => true,              // Permitir mudar qtd por página
    'columnDefs' => array(
        array('orderable' => false, 'targets' => [4, 5])  // Colunas não ordenáveis
    ),
),
```

## Exemplos Práticos

### Exemplo 1: Tabela Simples

```php
$this->widget('application.components.widgets.DataTableGridView', array(
    'dataProvider' => $dataProvider,
    'searchPlaceholder' => 'Buscar produtos',
    'columns' => array('id', 'name', 'price', 'stock'),
));
```

### Exemplo 2: Com Filtros e Ordenação Customizada

```php
$this->widget('application.components.widgets.DataTableGridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'searchPlaceholder' => 'Pesquisar movimentações',
    'datatableOptions' => array(
        'order' => [[0, 'desc']],  // Ordenar pela primeira coluna (data) decrescente
        'pageLength' => 50,
    ),
    'columns' => array(
        array('name' => 'date', 'header' => 'Data'),
        array('name' => 'type', 'header' => 'Tipo', 'filter' => CHtml::activeDropDownList(...)),
        array('name' => 'quantity', 'header' => 'Quantidade'),
    ),
));
```

### Exemplo 3: Desabilitar DataTables

```php
$this->widget('application.components.widgets.DataTableGridView', array(
    'dataProvider' => $dataProvider,
    'enableDataTable' => false,  // Usa apenas CGridView padrão
    'columns' => array('id', 'name'),
));
```

## Vantagens

✅ **Configuração automática** - DataTables é inicializado automaticamente  
✅ **Suporte a filtros** - Detecta e configura corretamente grids com filtros CGridView  
✅ **Responsivo** - Configuração responsiva por padrão  
✅ **Customizável** - Aceita todas as opções do DataTables  
✅ **Consistente** - Mesmo visual e comportamento em toda aplicação  
✅ **Manutenível** - Mudanças centralizadas no componente  

## Migração de CGridView

**Antes:**
```php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'my-grid',
    'dataProvider' => $dataProvider,
    'itemsCssClass' => 'js-tag-table table ...',
    'afterAjaxUpdate' => 'js:function(){ initDatatable(); }',
    'columns' => array(...),
));
```

**Depois:**
```php
$this->widget('application.components.widgets.DataTableGridView', array(
    'id' => 'my-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(...),
));
```

## Notas

- O componente herda todas as funcionalidades do `CGridView`
- Compatível com AJAX updates do Yii
- Funciona com `CActiveDataProvider`, `CArrayDataProvider`, etc.
- Requer que o DataTables JS/CSS estejam carregados no layout
