---
trigger: always_on
---

# TAG — System Overview

This is the TAG (Tecnologia de Apoio à Gestão), an open-source school management system built with **Yii 1.1** (PHP 8.3) and **MySQL 8.0**, running in Docker.

## What TAG Does
TAG manages the full lifecycle of a municipal education network:
- **Students**: enrollment, transfers, documents, health records (IMC), grades, attendance
- **Teachers (Instructors)**: identification, teaching assignments, schedules, absences, substitute management
- **Schools**: structure, rooms, managers, calendars
- **Academics**: grade structures (numeric and concept-based), curricula, lesson plans, class diaries, teaching records
- **Reports**: enrollment forms, grade sheets, attendance reports, electronic diaries, school certificates
- **Integrations**: Educacenso (national census), Sagres (audit), SEDSP (São Paulo state)
- **Support**: school lunch/food management, inventory, notifications, online enrollment, feature flags

## Architecture

### Stack
- **Backend**: PHP 8.3, Yii 1.1 (MVC), MySQL 8.0
- **Frontend**: Server-rendered PHP views, jQuery, SASS design system (TAG)
- **Infrastructure**: Docker (`tag-app`, `mysql`, `adminer`, `sass`, `bytebase`)

### Directory Structure
```
app/
├── commands/         # Console commands (SqlMigration, Admin, etc.)
├── components/       # Yii components (shared services)
├── config/           # App configuration
├── controllers/      # Legacy controllers (19 files — avoid adding new ones)
├── domain/           # Domain logic (admin, grades)
├── models/           # Root CActiveRecord models (89 files)
├── modules/          # Feature modules (28 — all new features go here)
│   ├── abilities/         # Regional skills management
│   ├── aeerecord/         # Special education (AEE) records
│   ├── calendar/          # School calendars
│   ├── classdiary/        # Class diary / teaching records
│   ├── courseplan/        # Lesson plans
│   ├── curricularcomponents/ # Curricular components
│   ├── curricularmatrix/  # Curricular matrices
│   ├── dashboard/         # Dashboard
│   ├── enrollmentonline/  # Online enrollment
│   ├── foods/             # Lunch ingredients/nutrition
│   ├── gradeconcept/      # Concept-based grading
│   ├── inventory/         # School supply inventory
│   ├── lunch/             # Lunch menus and management
│   ├── notifications/     # User notifications
│   ├── professional/      # Professional staff management
│   ├── quiz/              # Assessments
│   ├── resultsmanagement/ # Grade results
│   ├── sagres/            # Sagres audit integration
│   ├── schoolreport/      # Report cards
│   ├── sedsp/             # São Paulo state integration
│   ├── stages/            # Education stages
│   ├── studentimc/        # Student health (BMI)
│   ├── systemadmin/       # System administration
│   ├── tag/               # Core TAG utilities
│   ├── timesheet/         # Teacher timesheets
│   ├── tools/             # Admin tools
│   └── wizard/            # Setup wizard
├── migrations/       # SQL migration files
├── services/         # Service classes
├── widgets/          # Reusable UI widgets
└── export/           # File exports
```

### Key Models (Root)
| Domain | Models |
|--------|--------|
| Student | `StudentIdentification`, `StudentEnrollment`, `StudentDocumentsAndAddress`, `StudentDisorder`, `StudentVaccine` |
| Teacher | `InstructorIdentification`, `InstructorTeachingData`, `InstructorDocumentsAndAddress`, `InstructorVariableData`, `InstructorFaults` |
| School | `SchoolIdentification`, `SchoolStructure`, `SchoolRoom`, `SchoolStages` |
| Class | `Classroom`, `ClassBoard`, `ClassContents`, `ClassDiaries`, `ClassFaults`, `Schedule` |
| Grades | `Grade`, `GradeRules`, `GradeUnity`, `GradeResults`, `GradeCalculation`, `GradeConcept` |
| Course | `CoursePlan`, `CourseClass`, `CourseClassAbilities` |
| System | `Users`, `UsersSchool`, `AuthAssignment`, `AuthItem`, `FeatureFlags`, `Log` |

### Design System (SASS)
Located in `sass/scss/`. Uses design tokens (`_tokens.scss`), a custom grid, and components prefixed with `t-` (buttons), `tag-` (tables), etc. See `design-system-usage.md` rule for full list.

### Multi-Tenant
Each municipality has its own database (`*.tag.ong.br`). Migrations run across all databases via `SqlMigrationCommand`.

## Documentation Locations
- `docs/` — ADRs, usage guides, test cases
- `docs/modules/` — Per-module documentation (domain + technical)
- `CHANGELOG.md` — Release history (user-facing language)
- `.github/` — PR template, code review template
- `docs/adrs/` — Architectural Decision Records

## Documentation Guidelines

Documentation must be split into two perspectives, adapting language accordingly:

### Documentação de Domínio (Funcional)
- **Público**: gestores escolares, secretários de educação, coordenadores pedagógicos
- **Linguagem**: termos do dia a dia escolar — "aluno", "matrícula", "turma", "boletim", "diário de classe", "merenda"
- **Foco**: o que o sistema faz, fluxos de uso, regras de negócio
- **Evitar**: nomes de classes, tabelas, colunas, código, jargão técnico
- **Exemplo**: "O gestor pode enviar notificações para todos os professores de uma escola"

### Documentação Técnica
- **Público**: desenvolvedores, QA, devops
- **Linguagem**: nomes de classes, models, controllers, tabelas, colunas, rotas, comandos
- **Foco**: como o sistema funciona, arquitetura, API, exemplos de código, diagramas ER
- **Exemplo**: "`NotificationService::broadcast()` envia para users filtrados por `auth_assignment.itemname`"

### Regra Geral
Ao documentar um módulo, inclua **ambas as perspectivas** no mesmo documento:
1. Primeiro a visão de domínio (funcionalidades, fluxos, regras)
2. Depois a visão técnica (arquitetura, API, código, diagramas)

### Precisão Documental (CRÍTICO)
- **Documentar apenas o que o sistema FAZ, nunca o que PODERIA fazer.**
- Se um componente existe no código mas **não é utilizado por nenhum outro arquivo**, ele deve ser documentado como "infraestrutura disponível" ou "planejado", **nunca como funcionalidade ativa**.
- Antes de afirmar que uma funcionalidade está ativa, **verificar se ela é realmente chamada/utilizada** (buscar por referências com `grep` nos models, controllers e config).
- Exemplos:
  - ❌ "O sistema envia notificações quando uma matrícula é criada" (se nenhum model usa o behavior)
  - ✅ "O sistema possui infraestrutura para notificações automáticas, mas ainda não está ativada nos módulos"
