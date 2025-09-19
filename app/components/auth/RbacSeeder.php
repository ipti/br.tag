<?php

Yii::import('application.components.auth.TRole');
Yii::import('application.components.auth.TTask');
Yii::import('application.components.auth.TFeature');

class RbacSeeder
{
    public static function seed(): void
    {
        $db = Yii::app()->db;
        $txn = $db->beginTransaction();

        try {
            $auth = Yii::app()->authManager;

            // ----------------------------- ROLES --------------------------------
            $roles = [
                TRole::ADMIN->value => 'Administrador',
                TRole::MANAGER->value => 'Gestor',
                TRole::READER->value => 'Leitor',
                TRole::COORDINATOR->value => 'Coordenador',
                TRole::INSTRUCTOR->value => 'Instrutor',
                TRole::GUARDIAN->value => 'Responsável',
                TRole::NUTRITIONIST->value => 'Nutricionista',
            ];
            foreach ($roles as $name => $desc) {
                self::createOrUpdate($auth, $name, CAuthItem::TYPE_ROLE, $desc);
            }

            // ----------------------- OPERATIONS (FEAT_*) ------------------------
            // Somente operações atômicas (sem *_MODULE)
            $ops = [
                // SITE
                [TFeature::FEAT_SITE_HOME, 'Acesso à Página Inicial'],

                // ALUNOS
                [TFeature::FEAT_STUDENT_CREATE, 'Criar Aluno'],
                [TFeature::FEAT_STUDENT_DELETE, 'Remover Aluno'],
                [TFeature::FEAT_STUDENT_UPDATE, 'Editar Aluno'],
                [TFeature::FEAT_STUDENT_INDV_RECORD, 'Ficha individual'],

                // MATRÍCULA
                [TFeature::FEAT_ENROLLMENT_CREATE,   'Criar Matrícula'],
                [TFeature::FEAT_ENROLLMENT_DELETE,   'Remover Matrícula'],
                [TFeature::FEAT_ENROLLMENT_UPDATE,   'Editar Matrícula'],
                [TFeature::FEAT_ENROLLMENT_TRANSFER, 'Transferir Matrícula'],
                [TFeature::FEAT_ENROLLMENT_ONLINE,   'Matrícula Online'],

                // TURMAS
                [TFeature::FEAT_CLASSROOM_LIST,                         'Listar Turmas'],
                [TFeature::FEAT_CLASSROOM_CREATE,                       'Criar Turma'],
                [TFeature::FEAT_CLASSROOM_UPDATE,                       'Editar Turma'],
                [TFeature::FEAT_CLASSROOM_DELETE,                       'Remover Turma'],
                [TFeature::FEAT_CLASSROOM_UPDATE_PEDAGOGIC,             'Configuração Pedagógica'],
                [TFeature::FEAT_CLASSROOM_UPDATE_LINK_INSTRUCTOR,       'Vincular Professor'],
                [TFeature::FEAT_CLASSROOM_UPDATE_REMOVE_ENROLLMENT_BATCH, 'Remover Matrículas em Lote'],
                [TFeature::FEAT_CLASSROOM_UPDATE_MOVE_ENROLLMENT_BATCH,   'Mover Matrículas em Lote'],

                // ESCOLAS
                [TFeature::FEAT_SCHOOL_LIST,              'Listar Escolas'],
                [TFeature::FEAT_SCHOOL_UPDATE,            'Editar Escola'],
                [TFeature::FEAT_SCHOOL_UPDATE_DEFINE_STAGES, 'Definir Etapas da Escola'],
                [TFeature::FEAT_SCHOOL_DELETE,            'Remover Escola'],

                // PROFESSORES
                [TFeature::FEAT_INSTRUCTOR_LIST,      'Listar Professores'],
                [TFeature::FEAT_INSTRUCTOR_UPDATE,    'Editar Professor'],
                [TFeature::FEAT_INSTRUCTOR_FREQUENCY, 'Frequência do Professor'],
                [TFeature::FEAT_INSTRUCTOR_DELETE,    'Remover Professor'],

                // DIÁRIO ELETRÔNICO
                [TFeature::FEAT_DIARY_LESSON_PLAN,     'Plano de Aula'],
                [TFeature::FEAT_DIARY_CLASSES,         'Aulas Ministradas'],
                [TFeature::FEAT_DIARY_ATTENDANCE,      'Frequência'],
                [TFeature::FEAT_DIARY_CALCULATE_GRADES, 'Calcular Notas'],
                [TFeature::FEAT_DIARY_GRADES,          'Notas'],
                [TFeature::FEAT_DIARY_GRADES_BUZIOS,   'Lançamento de Notas (Búzios)'],
                [TFeature::FEAT_DIARY_GRADES_FINAL,    'Lançamento de Notas Finais'],
                [TFeature::FEAT_DIARY_FREQ_CLASSCONT,  'Frequência por Conteúdo'],
                [TFeature::FEAT_DIARY_AEE_SHEET,       'Ficha AEE'],
                [TFeature::FEAT_DIARY_CLASSBOOK,       'Diário de Classe'],

                // NOVA MERENDA
                [TFeature::FEAT_FOODS_MENU_CREATE,   'Criar Cardápio de Merenda'],
                [TFeature::FEAT_FOODS_MENU_STOCK,    'Estoque de Merenda'],
                [TFeature::FEAT_FOODS_MENU_REQUESTS, 'Solicitações de Merenda'],
                [TFeature::FEAT_FOODS_MENU_FARMERS,  'Agricultores/PAA'],
                [TFeature::FEAT_FOODS_MENU_BIDS,     'Editais de Merenda'],

                // CURRÍCULO & CALENDÁRIO
                [TFeature::FEAT_CURRICULUM_CALENDAR, 'Calendário Escolar'],
                [TFeature::FEAT_CURRICULUM_MATRIX,   'Matriz Curricular'],
                [TFeature::FEAT_CURRICULUM_SCHEDULE, 'Quadro de Horário'],

                // ADMINISTRAÇÃO
                [TFeature::FEAT_ADMIN_GENERAL,        'Administração Geral'],
                [TFeature::FEAT_USER_CHANGE_PASSWORD, 'Alterar Senha do Usuário'],

                // GESTÃO POR RESULTADOS
                [TFeature::FEAT_MANAGEMENT_PERFORMANCE_POWERBI, 'Gestão de Resultados (PowerBI)'],
                [TFeature::FEAT_MANAGEMENT_PERFORMANCE,         'Gestão de Resultados (Dashboard Interno)'],

                // RELATÓRIOS
                [TFeature::FEAT_REPORTS_ACCESS, 'Acesso a Relatórios'],

                // QUIZ
                [TFeature::FEAT_QUIZ_ACCESS, 'Acesso a Questionários'],

                // INTEGRAÇÕES
                [TFeature::FEAT_INTEGRATIONS_ACCESS,             'Acesso às Integrações'],
                [TFeature::FEAT_INTEGRATIONS_CENSO,              'Integração com Educacenso'],
                [TFeature::FEAT_INTEGRATIONS_SAGRES,             'Integração com Sagres'],
                [TFeature::FEAT_INTEGRATIONS_SAGRES_STATUS_ENROLL, 'Sagres: Status da Matrícula'],
                [TFeature::FEAT_INTEGRATIONS_GESTAOPRESENTE,     'Integração Gestão Presente'],
                [TFeature::FEAT_INTEGRATIONS_SEDSP,              'Integração SEDSP'],
            ];

            foreach ($ops as [$feat, $desc]) {
                self::createOrUpdate($auth, $feat->value, CAuthItem::TYPE_OPERATION, $desc);
            }

            // ----------------------------- TASKS --------------------------------
            // Tasks agrupam FEAT_* (sem *_MODULE)
            $taskDescriptions = [
                TTask::TASK_HOME->value => 'Página inicial e acesso ao sistema',
                TTask::TASK_STUDENT_MANAGE->value => 'Gestão de alunos (criação, edição, exclusão)',
                TTask::TASK_ONLINE_ENROLLMENT->value => 'Matrícula online para responsáveis',
                TTask::TASK_ENROLLMENT_MANAGE->value => 'Gestão de matrículas presenciais (criar, editar, excluir, transferir)',
                TTask::TASK_CLASSROOM_MANAGE->value => 'Gestão de turmas (criação, configuração pedagógica, movimentação de alunos)',
                TTask::TASK_SCHOOL_MANAGE->value => 'Gestão de escolas (editar dados, definir etapas, remover)',
                TTask::TASK_INSTRUCTOR_MANAGE->value => 'Gestão de professores (listar, editar, remover)',
                TTask::TASK_DIARY_RECORD->value => 'Diário eletrônico: planos de aula, aulas ministradas, frequência',
                TTask::TASK_DIARY_GRADING->value => 'Notas e lançamento de avaliações no diário eletrônico',
                TTask::TASK_FOODS_MENU_MANAGE->value => 'Gestão da merenda: cardápio, estoque, solicitações, agricultores e editais',
                TTask::TASK_LUNCH_MENU_MANAGE->value => 'Gestão da merenda tradicional (almoxarifado/lanche)',
                TTask::TASK_CURRICULUM_ACCESS->value => 'Acesso ao currículo e calendário (calendário, matriz, quadro de horário)',
                TTask::TASK_REPORTS_ACCESS->value => 'Acesso a relatórios do sistema',
                TTask::TASK_INTEGRATIONS_ACCESS->value => 'Integrações externas (Educacenso, Sagres, SEDSP, Gestão Presente)',
                TTask::TASK_ADMIN_GENERAL->value => 'Administração geral do sistema',
                TTask::TASK_MANAGEMENT_PERFORMANCE->value => 'Gestão de resultados (módulo interno)',
                TTask::TASK_MANAGEMENT_PERFORMANCE_BI->value => 'Gestão de resultados via BI/PowerBI',
                TTask::TASK_QUIZ_ACCESS->value => 'Módulo de questionários e pesquisas',
            ];

            $tasks = [
                // Alunos
                TTask::TASK_STUDENT_MANAGE->value => [
                    TFeature::FEAT_STUDENT_CREATE->value,
                    TFeature::FEAT_STUDENT_UPDATE->value,
                    TFeature::FEAT_STUDENT_DELETE->value,
                    TFeature::FEAT_STUDENT_INDV_RECORD->value,
                ],
                // Matrícula
                TTask::TASK_ENROLLMENT_MANAGE->value => [
                    TFeature::FEAT_ENROLLMENT_CREATE->value,
                    TFeature::FEAT_ENROLLMENT_UPDATE->value,
                    TFeature::FEAT_ENROLLMENT_DELETE->value,
                    TFeature::FEAT_ENROLLMENT_TRANSFER->value,
                    TFeature::FEAT_ENROLLMENT_ONLINE->value,
                ],
                // Turmas
                TTask::TASK_CLASSROOM_MANAGE->value => [
                    TFeature::FEAT_CLASSROOM_LIST->value,
                    TFeature::FEAT_CLASSROOM_CREATE->value,
                    TFeature::FEAT_CLASSROOM_UPDATE->value,
                    TFeature::FEAT_CLASSROOM_DELETE->value,
                    TFeature::FEAT_CLASSROOM_UPDATE_PEDAGOGIC->value,
                    TFeature::FEAT_CLASSROOM_UPDATE_LINK_INSTRUCTOR->value,
                    TFeature::FEAT_CLASSROOM_UPDATE_REMOVE_ENROLLMENT_BATCH->value,
                    TFeature::FEAT_CLASSROOM_UPDATE_MOVE_ENROLLMENT_BATCH->value,
                ],
                // Escolas
                TTask::TASK_SCHOOL_MANAGE->value => [
                    TFeature::FEAT_SCHOOL_LIST->value,
                    TFeature::FEAT_SCHOOL_UPDATE->value,
                    TFeature::FEAT_SCHOOL_UPDATE_DEFINE_STAGES->value,
                    TFeature::FEAT_SCHOOL_DELETE->value,
                ],
                // Professores
                TTask::TASK_INSTRUCTOR_MANAGE->value => [
                    TFeature::FEAT_INSTRUCTOR_LIST->value,
                    TFeature::FEAT_INSTRUCTOR_UPDATE->value,
                    TFeature::FEAT_INSTRUCTOR_FREQUENCY->value,
                    TFeature::FEAT_INSTRUCTOR_DELETE->value,
                ],
                // Diário (registro pedagógico)
                TTask::TASK_DIARY_RECORD->value => [
                    TFeature::FEAT_DIARY_LESSON_PLAN->value,
                    TFeature::FEAT_DIARY_CLASSES->value,
                    TFeature::FEAT_DIARY_ATTENDANCE->value,
                    TFeature::FEAT_DIARY_AEE_SHEET->value,
                    TFeature::FEAT_DIARY_CLASSBOOK->value,
                    TFeature::FEAT_DIARY_FREQ_CLASSCONT->value,
                ],
                // Diário (avaliação/nota)
                TTask::TASK_DIARY_GRADING->value => [
                    TFeature::FEAT_DIARY_CALCULATE_GRADES->value,
                    TFeature::FEAT_DIARY_GRADES->value,
                    TFeature::FEAT_DIARY_GRADES_BUZIOS->value,
                    TFeature::FEAT_DIARY_GRADES_FINAL->value,
                ],
                // Nova merenda
                TTask::TASK_FOODS_MENU_MANAGE->value => [
                    TFeature::FEAT_FOODS_MENU_CREATE->value,
                    TFeature::FEAT_FOODS_MENU_STOCK->value,
                    TFeature::FEAT_FOODS_MENU_REQUESTS->value,
                    TFeature::FEAT_FOODS_MENU_FARMERS->value,
                    TFeature::FEAT_FOODS_MENU_BIDS->value,
                ],
                // Currículo & calendário
                TTask::TASK_CURRICULUM_ACCESS->value => [
                    TFeature::FEAT_CURRICULUM_CALENDAR->value,
                    TFeature::FEAT_CURRICULUM_MATRIX->value,
                    TFeature::FEAT_CURRICULUM_SCHEDULE->value,
                ],
                // Relatórios
                TTask::TASK_REPORTS_ACCESS->value => [
                    TFeature::FEAT_REPORTS_ACCESS->value,
                ],
                // Integrações
                TTask::TASK_INTEGRATIONS_ACCESS->value => [
                    TFeature::FEAT_INTEGRATIONS_ACCESS->value,
                    TFeature::FEAT_INTEGRATIONS_CENSO->value,
                    TFeature::FEAT_INTEGRATIONS_SAGRES->value,
                    TFeature::FEAT_INTEGRATIONS_SAGRES_STATUS_ENROLL->value,
                    TFeature::FEAT_INTEGRATIONS_GESTAOPRESENTE->value,
                    TFeature::FEAT_INTEGRATIONS_SEDSP->value,
                ],
                // Administração
                TTask::TASK_ADMIN_GENERAL->value => [
                    TFeature::FEAT_ADMIN_GENERAL->value,
                    TFeature::FEAT_USER_CHANGE_PASSWORD->value,
                ],
                // Gestão por resultados
                TTask::TASK_MANAGEMENT_PERFORMANCE->value => [
                    TFeature::FEAT_MANAGEMENT_PERFORMANCE_POWERBI->value,
                    TFeature::FEAT_MANAGEMENT_PERFORMANCE->value,
                ],
                // Quiz
                TTask::TASK_QUIZ_ACCESS->value => [
                    TFeature::FEAT_QUIZ_ACCESS->value,
                ],
            ];

            $tasks = array_merge($tasks, array_reduce(TTask::cases(), function ($carry, $task) {
                $carry[$task->value] = [];
                return $carry;
            }));

            foreach ($tasks as $taskName => $opList) {
                self::createOrUpdate($auth, $taskName, CAuthItem::TYPE_TASK, $taskDescriptions[$taskName]);
                if (empty($opList)) {
                    continue;
                }
                foreach ($opList as $opName) {
                    self::ensureChild($auth, $taskName, $opName);
                }
            }

            // ------------------------- ROLES → TASKS -----------------------------
            $grants = [
                TRole::ADMIN->value => array_keys($tasks),

                TRole::MANAGER->value => [
                    TTask::TASK_STUDENT_MANAGE->value,
                    TTask::TASK_ENROLLMENT_MANAGE->value,
                    TTask::TASK_CLASSROOM_MANAGE->value,
                    TTask::TASK_SCHOOL_MANAGE->value,
                    TTask::TASK_INSTRUCTOR_MANAGE->value,
                    TTask::TASK_CURRICULUM_ACCESS->value,
                    TTask::TASK_REPORTS_ACCESS->value,
                    TTask::TASK_MANAGEMENT_PERFORMANCE->value,
                    TTask::TASK_INTEGRATIONS_ACCESS->value,
                ],

                TRole::READER->value => [
                    TTask::TASK_CURRICULUM_ACCESS->value,
                    TTask::TASK_REPORTS_ACCESS->value,
                    TTask::TASK_MANAGEMENT_PERFORMANCE->value,
                ],

                TRole::COORDINATOR->value => [
                    TTask::TASK_DIARY_RECORD->value,
                    TTask::TASK_DIARY_GRADING->value,
                    TTask::TASK_CURRICULUM_ACCESS->value,
                    TTask::TASK_REPORTS_ACCESS->value,
                    TTask::TASK_CLASSROOM_MANAGE->value,
                ],

                TRole::INSTRUCTOR->value => [
                    TTask::TASK_DIARY_RECORD->value,
                    TTask::TASK_DIARY_GRADING->value,
                    TTask::TASK_CURRICULUM_ACCESS->value,
                ],

                TRole::GUARDIAN->value => [
                    TTask::TASK_REPORTS_ACCESS->value,
                    TTask::TASK_CURRICULUM_ACCESS->value,
                ],

                TRole::NUTRITIONIST->value => [
                    TTask::TASK_FOODS_MENU_MANAGE->value,
                    TTask::TASK_REPORTS_ACCESS->value,
                ],
            ];

            foreach ($grants as $role => $children) {
                foreach ($children as $child) {
                    self::ensureChild($auth, $role, $child);
                }
            }

            // ---------------------- FEATURE FLAGS (FEAT_*) -----------------------
            self::ensureFeatureFlagsTable();

            $featAndTasks = array_merge($ops, array_map(fn ($task) => [$task, $task->value], TTask::cases()));
            self::upsertFeatureFlags(array_map(fn ($p) => $p[0]->value, $featAndTasks));

            $txn->commit();
        } catch (Exception $e) {
            if ($txn->active) {
                $txn->rollBack();
            }
            Yii::log('RBAC seed failed: ' . $e->getMessage(), CLogger::LEVEL_ERROR);
            throw $e;
        }
    }

    // ============================== HELPERS ===================================

    private static function createOrUpdate(CAuthManager $auth, string $name, int $type, string $description = ''): void
    {
        $item = $auth->getAuthItem($name);

        if (!$item) {
            $auth->createAuthItem($name, $type, $description);
            Yii::log("RBAC: criado {$name} (type {$type})", CLogger::LEVEL_INFO);
            CVarDumper::dump("RBAC: criado {$name} (type {$type})", 10, true);
        } elseif ($description && $item->getDescription() !== $description) {
            $item->setDescription($description);

            if ($auth instanceof CDbAuthManager) {
                $auth->saveAuthItem($item);   // <-- salva no banco
            } elseif ($auth instanceof CPhpAuthManager) {
                $auth->save();                // <-- salva no arquivo de config
            }

            Yii::log("RBAC: atualizado {$name}", CLogger::LEVEL_INFO);
            CVarDumper::dump("RBAC: criado {$name}", 10, true);
        }
    }

    private static function ensureChild(CAuthManager $auth, string $parent, string $child): void
    {
        if (!$auth->getAuthItem($parent)) {
            // fallback: assume parent como ROLE (quando não encontrado)
            $auth->createAuthItem($parent, CAuthItem::TYPE_ROLE, 'auto-created parent');
        }
        if (!$auth->getAuthItem($child)) {
            // fallback: assume child como OPERATION (quando não encontrado)
            $auth->createAuthItem($child, CAuthItem::TYPE_OPERATION, 'auto-created child');
        }
        if (!$auth->hasItemChild($parent, $child)) {
            $auth->addItemChild($parent, $child);
            Yii::log("RBAC: vinculado {$parent} → {$child}", CLogger::LEVEL_INFO);
        }
    }

    // ------------------------ feature_flags helpers ---------------------------

    private static function ensureFeatureFlagsTable(): void
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS feature_flags (
            feature_name VARCHAR(100) NOT NULL PRIMARY KEY,
            active       TINYINT(1) NOT NULL DEFAULT 1,
            updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_feature_flags_auth_item
                FOREIGN KEY (feature_name)
                REFERENCES auth_item(name)
                ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        SQL;
        Yii::app()->db->createCommand($sql)->execute();
    }

    /** Ativa (ou atualiza) todas as features passadas no feature_flags. */
    private static function upsertFeatureFlags(array $featureNames): void
    {
        if (empty($featureNames)) {
            return;
        }

        $values = [];
        $params = [];
        foreach ($featureNames as $i => $name) {
            $values[] = "( :f{$i}, 1 )";
            $params[":f{$i}"] = $name;
        }

        $sql = 'INSERT INTO feature_flags (feature_name, active) VALUES '
             . implode(",\n", $values)
             . ' ON DUPLICATE KEY UPDATE active = VALUES(active)';

        Yii::app()->db->createCommand($sql)->execute($params);
        CVarDumper::dump($featureNames, 10, true);
    }
}
