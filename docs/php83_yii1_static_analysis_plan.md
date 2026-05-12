# Plano de Modernizacao para Analise Estatica e Manutenabilidade

## Objetivo

Melhorar a seguranca de refatoracao do projeto, reduzir erros de classes nao importadas e assinaturas incorretas, e criar um ambiente mais previsivel para manutencao humana e para servicos de IA.

O objetivo nao e reescrever o Yii 1, e sim criar uma borda moderna em volta do legado para que codigo novo e codigo critico passem a ser mais tipados, mais descobriveis e mais analisaveis.

Este documento complementa o ADR `docs/adrs/1_ANALISE_ESTATICA_DE_CODIGO.md` com um plano de execucao incremental voltado ao estado atual do projeto.

## Diagnostico atual

Levantamento feito em 2026-05-12 no repositorio:

- O runtime do projeto esta em PHP 8.3, mas o `composer.json` ainda declara `php >=5.1.0`.
- O repositorio possui 1023 arquivos PHP em `app/`, `themes/` e `tests/`.
- Existem 157 ocorrencias de `Yii::import(...)`, varias delas com curingas `.*`.
- Existem 62 controllers estendendo a classe base `Controller`.
- Existem 26 models estendendo `CActiveRecord`.
- Existem nomes globais duplicados, como `DefaultController` (22 ocorrencias), `ReportsController`, `LoginForm`, `GradesController` e outros.
- O `composer.json` nao possui `autoload` nem `autoload-dev` definidos para o codigo da aplicacao.
- O `PHPStan` e o `Rector` ja estao instalados no projeto, mas ainda nao existe uma configuracao de analise estatica consolidada.
- O uso de `namespace` esta concentrado em testes gerados e em codigo do modulo `sagres`, nao no nucleo principal da aplicacao Yii.
- Ha apenas 1 ocorrencia de `declare(strict_types=1)` no escopo analisado.

## Leitura arquitetural

O principal bloqueio para autoload moderno do projeto nao e apenas a ausencia de namespace. O maior problema e a combinacao de:

- classes globais com nomes repetidos entre modulos;
- carga dinamica com `Yii::import(...)`;
- uso intensivo de propriedades e relacoes magicas do Yii 1;
- controllers com regra de negocio, acesso a request, SQL e persistencia misturados no mesmo arquivo.

Isso significa que classmap geral no Composer para todo `app/` nao e seguro neste momento. Os nomes globais duplicados fariam o autoload apontar para a classe errada ou tornariam a migracao ambigua.

## Principios do plano

1. Nao migrar tudo de uma vez.
2. Nao tentar namespaciar controllers e ActiveRecords legados no primeiro movimento.
3. Todo codigo novo deve nascer namespaced, com `strict_types=1`, e fora da camada mais dinamica do Yii.
4. Controllers Yii devem virar cascas finas de orquestracao.
5. O Composer passa a ser a fonte de verdade do codigo novo.
6. O PHPStan passa a vigiar primeiro o codigo novo e os pontos de integracao, nao o legado inteiro de uma vez.
7. O legado ganha visibilidade via PHPDoc, stubs e adaptadores antes de ganhar tipagem nativa.
8. O fluxo deve ser amigavel a IA: simbolos previsiveis, nomes estaveis, contratos explicitos e menos comportamento magico espalhado.

## Estrategia alvo

Separar o projeto em tres aneis:

- Casca Yii legada:
  controllers, ActiveRecords, widgets, views e pontos que o framework instancia por convencao.
- Camada de integracao:
  adaptadores, mapeadores, DTOs e wrappers que traduzem entre Yii e codigo moderno.
- Nucleo moderno:
  services, use cases, value objects e regras de negocio em `namespace`, autoloadados por Composer e cobertos por analise estatica.

## Fase 1 - Fundacao tecnica

Objetivo: criar o trilho de analise estatica sem tentar resolver todo o legado.

### Entregas

- Ajustar o `composer.json` para refletir a versao de PHP realmente suportada pelo projeto.
- Adicionar `autoload` e `autoload-dev` no Composer apenas para codigo novo.
- Criar um diretorio dedicado a codigo moderno, por exemplo `app/src/`.
- Definir um namespace raiz para codigo novo, por exemplo `Tag\\`.
- Criar `phpstan.neon.dist`.
- Criar um bootstrap leve para o PHPStan com constantes e inicializacao minima necessaria.
- Criar baseline inicial do PHPStan.
- Adicionar scripts de Composer para analise estatica e dry-run de refatoracao.

### Regras importantes desta fase

- Nao usar `classmap` para todo `app/`.
- Nao rodar `Rector` sobre o projeto inteiro com regras de tipagem automaticamente.
- Nao introduzir `strict_types=1` em ActiveRecords e controllers legados sem isolamento previo.

### Configuracao inicial sugerida

O primeiro escopo de analise deve ser pequeno e previsivel:

- `app/src/`
- `app/domain/`
- `app/repository/`
- subconjuntos estaveis de `app/components/`
- `tests/` que exercitam esses pontos

Escopos que devem comecar fora da analise ou com regras reduzidas:

- `themes/`
- controllers Yii muito acoplados
- models com relacoes magicas sem PHPDoc
- codigo gerado do `sagres`

## Fase 2 - Borda moderna para o legado

Objetivo: parar de colocar regra de negocio diretamente dentro do Yii 1.

### Padrao de desenvolvimento novo

Para cada nova funcionalidade ou manutencao relevante:

- o controller continua global se o Yii depender disso;
- a regra de negocio vai para uma classe namespaced em `app/src/`;
- leitura de `$_POST`, `$_GET` e `Yii::app()->request` fica no controller ou em um request mapper;
- retorno de regra de negocio deve usar DTO, value object ou array shape documentado;
- acesso a banco fora de ActiveRecord deve ser encapsulado em repository ou service dedicado.

### Exemplo de movimento esperado

O arquivo `app/modules/courseplan/controllers/CourseplanController.php` e um bom candidato de piloto porque hoje mistura:

- leitura direta de `$_POST`;
- queries SQL;
- montagem manual de arrays JSON;
- criacao e atualizacao de varios modelos;
- regras de validacao e fluxo;
- logs e redirecionamentos.

O caminho recomendado nao e namespaciar esse controller primeiro. O caminho recomendado e extrair dele servicos namespaced, por exemplo:

- `Tag\\CoursePlan\\Application\\SaveCoursePlanService`
- `Tag\\CoursePlan\\Application\\ListCourseClassesService`
- `Tag\\CoursePlan\\Application\\ValidateCoursePlanService`
- `Tag\\CoursePlan\\Application\\CoursePlanRequestMapper`

## Fase 3 - Visibilidade estatica do Yii legado

Objetivo: fazer o analisador entender melhor o que o runtime ja sabe.

### Entregas

- Criar stubs para classes base e pontos magicos mais usados.
- Documentar melhor modelos `CActiveRecord` com `@property`, `@method` e relacoes.
- Documentar componentes customizados de `Yii::app()`.
- Catalogar aliases e imports dinamicos realmente necessarios.

### Tipos de stubs prioritarios

- `Yii::app()` e seus componentes customizados.
- classe de usuario autenticado e propriedades relevantes como `loginInfos`, `school` e `year`.
- metodos de factories comuns do Yii, quando necessario para o PHPStan.
- contratos de componentes que hoje sao acessados de forma implicita.

### Resultado esperado

Mesmo mantendo parte do legado sem namespace, o analisador passa a detectar:

- classe inexistente;
- metodo inexistente;
- assinatura incorreta;
- retorno inesperado;
- acesso a propriedade que nao deveria existir.

## Fase 4 - Cobertura incremental do PHPStan

Objetivo: transformar a analise estatica em trava de qualidade, nao em relatorio esquecido.

### Trilho de adocao

1. Criar configuracao minima e baseline.
2. Rodar em CI apenas nos diretorios modernos.
3. Bloquear erro novo em arquivo tocado.
4. Expandir escopo por modulo ou por pacote.
5. Elevar o nivel do PHPStan gradualmente.

### Meta sugerida por etapas

- Etapa A: nivel baixo com foco em simbolos, chamadas invalidas e assinaturas.
- Etapa B: nivel intermediario com arrays, nullability e retornos.
- Etapa C: nivel mais alto em codigo namespaced e testado.

### Politica de baseline

Baseline deve servir para absorver legado existente, nao para esconder erro novo. Sempre que possivel:

- baseline por arquivo ou por conjunto pequeno;
- revisao periodica do baseline;
- proibido adicionar baseline para codigo novo sem justificativa.

## Fase 5 - Controle de naming e duplicidade global

Objetivo: reduzir o que impede autoload previsivel.

### Acoes

- Inventariar todas as classes globais duplicadas.
- Priorizar duplicidades em classes muito tocadas.
- Evitar criar novas classes globais com nomes genericos como `DefaultController`, `Helper`, `Service`, `Form` e `Model`.
- Quando possivel, mover logica repetida para classes namespaced com nome unico.

### Regra pratica

Enquanto existirem muitos nomes globais duplicados, o Composer deve autoloadar somente o codigo namespaced novo. O legado continua sendo descoberto pelo mecanismo do Yii e por configuracao do PHPStan.

## Fase 6 - Uso seguro do Rector

Objetivo: usar automacao sem quebrar convencoes dinamicas do Yii 1.

### Ajuste de postura

O `rector.php` atual esta configurado para percorrer `app/`, `tests/` e `themes/` com conjuntos amplos de regras, incluindo `TYPE_DECLARATION`. Para o estado atual do projeto, isso e agressivo demais.

### Recomendacao

- Limitar o Rector a `app/src/`, `app/domain/`, `app/repository/` e outros escopos modernos.
- Usar dry-run por diretorio.
- Manter listas de `skip` explicitas para codigo gerado e legado altamente dinamico.
- Aplicar regras de tipagem nativa somente onde o contrato ja esta claro.

## Convencoes para IA e manutencao futura

Estas regras ajudam tanto pessoas quanto agentes automatizados:

- Toda classe nova deve ter namespace e imports explicitos.
- Toda regra de negocio nova deve sair de controller e de view.
- Evitar arrays anonimos para contratos complexos; preferir DTO ou ao menos array shape documentado.
- Nomes de classes devem refletir acao ou papel de negocio.
- Cada arquivo deve ter uma responsabilidade clara.
- Novas dependencias devem entrar por construtor ou factory explicita.
- Nao usar `Yii::import('...*')` em codigo novo.
- Nao acessar `$_POST` e `$_GET` fora da borda HTTP.
- Sempre documentar, em PHPDoc, propriedades magicas inevitaveis do legado.

## Backlog recomendado de curto prazo

### Sprint 1

- Corrigir `composer.json` para a faixa de PHP suportada.
- Adicionar `autoload` PSR-4 para `app/src/`.
- Criar `phpstan.neon.dist`.
- Criar `phpstan-baseline.neon`.
- Adicionar script `composer analyse`.
- Documentar bootstrap minimo da analise.

### Sprint 2

- Escolher um modulo piloto.
- Extrair de 2 a 4 servicos namespaced desse modulo.
- Cobrir esse nucleo com PHPStan e testes.
- Adicionar primeiros stubs de Yii e componentes customizados.

### Sprint 3

- Padronizar PHPDoc dos ActiveRecords mais usados pelo modulo piloto.
- Adicionar politica de CI para erro novo em codigo namespaced.
- Restringir o escopo do Rector para codigo moderno.

## Modulos candidatos a piloto

Prioridade sugerida:

1. `courseplan`
2. `inventory`
3. `notifications`
4. `domain/admin`
5. `repository`

Justificativa:

- possuem alto valor funcional;
- ja mostram mistura entre fluxo web, persistencia e regra;
- tendem a render bons ganhos com extracao de servicos;
- sao bons exemplos para criar um padrao replicavel.

## Indicadores de sucesso

- Todo arquivo novo fora de view nasce em `namespace`.
- Nenhuma funcionalidade nova relevante adiciona `Yii::import(...)`.
- PHPStan roda localmente e em CI no escopo moderno.
- O numero de servicos namespaced cresce sprint a sprint.
- Controllers ficam menores e com menos SQL inline.
- ActiveRecords criticos passam a ter PHPDoc util.
- IA consegue localizar classe, contrato e ponto de integracao sem depender de convencoes implicitas do Yii.

## Decisoes que nao devem ser tomadas agora

- Nao tentar migrar todos os ActiveRecords para Doctrine ou outra ORM.
- Nao tentar namespaciar todos os controllers legados de uma vez.
- Nao usar autoload Composer geral sobre classes globais duplicadas.
- Nao aplicar `strict_types=1` em massa.
- Nao elevar o PHPStan para nivel maximo antes de estabilizar simbolos e contratos.

## Referencias externas

- PHPStan: baseline, discovering symbols, stub files e bootstrap.
- Composer: PSR-4, classmap e otimizacao de autoload.
- Rector: configuracao de `paths` e `skip` por escopo.
