<?php
Yii::import('application.components.auth.TTask');

$menuItems = [
    [
        'label' => 'Página Inicial',
        'url' => ['/'],
        'icon' => 't-icon-home',
        'roles' => [TRole::ADMIN, TRole::COORDINATOR,TRole::MANAGER,TRole::INSTRUCTOR,TRole::NUTRITIONIST,TRole::READER],
        'feature' => TTask::TASK_HOME,
    ],
    [
        'label' => 'Controle de Módulos',
        'url' => ['systemadmin/default/managemodules'],
        'icon' => 't-icon-home',
        'roles' => [TRole::SUPERUSER],
        'feature' => TTask::TASK_HOME,
    ],
    [
        'label' => 'Matrícula Online',
        'url' => ['enrollmentonline/Enrollmentonlinestudentidentification/StudentStatus'],
        'icon' => 't-icon-backpack',
        'roles' => [TRole::GUARDIAN],
        'feature' => TTask::TASK_ONLINE_ENROLLMENT,
    ],

    // Escola, Turmas, Alunos, Professores
    [
        'label' => 'Escola',
        'url' => function() {
            return (count(Yii::app()->user->usersSchools) == 1)
                ? ['school/update', ['id' => Yii::app()->user->school]]
                : ['school/index'];
        },
        'icon' => 't-icon-school',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_SCHOOL_MANAGE,
    ],
    [
        'label' => 'Turmas',
        'url' => ['classroom'],
        'icon' => 't-icon-people',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_CLASSROOM_MANAGE,
    ],
    [
        'label' => 'Alunos',
        'url' => ['student'],
        'icon' => 't-icon-pencil',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_STUDENT_MANAGE,
    ],
    [
        'label' => 'Professores',
        'url' => ['instructor'],
        'icon' => 't-icon-book',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_INSTRUCTOR_MANAGE,
    ],
    [
        'label' => 'Calendário Escolar',
        'url' => ['calendar'],
        'icon' => 't-icon-calendar',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER, 'instructor'],
        'feature' => TTask::TASK_CURRICULUM_ACCESS,
    ],
    [
        'label' => 'Matriz Curricular',
        'url' => ['curricularmatrix'],
        'icon' => 't-icon-line_graph',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_CURRICULUM_ACCESS,
    ],
    [
        'label' => 'Quadro de Horário',
        'url' => ['timesheet'],
        'icon' => 't-icon-blackboard',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_CURRICULUM_ACCESS,
    ],

    // Diário Eletrônico
    [
        'menu_id' => 'menu-electronic-diary',
        'submenu_id' => 'submenu-electronic-diary',
        'label' => 'Diário Eletrônico',
        'icon' => 't-icon-schedule',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::INSTRUCTOR, TRole::READER],
        'feature' => TTask::TASK_DIARY_RECORD,
        'submenu' => [
            [
                'label' => 'Plano de Aula',
                'url' => ['courseplan/courseplan/index'],
                'icon' => 't-icon-diary',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::INSTRUCTOR, TRole::READER],
                'feature' => TFeature::FEAT_DIARY_LESSON_PLAN,
            ],
            [
                'label' => 'Aulas Ministradas',
                'url' => ['classes/classContents'],
                'icon' => 't-icon-topics',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::INSTRUCTOR, TRole::READER],
                'feature' => TFeature::FEAT_DIARY_CLASSES,
            ],
            [
                'label' => 'Frequência',
                'url' => ['classes/frequency'],
                'icon' => 't-icon-checklist',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::INSTRUCTOR, TRole::READER],
                'feature' => TFeature::FEAT_DIARY_ATTENDANCE,
            ],
            [
                'label' => 'Notas',
                'url' => ['grades/grades'],
                'icon' => 't-icon-edition',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::INSTRUCTOR, TRole::READER],
                'feature' => TFeature::FEAT_DIARY_GRADES,
            ],
            [
                'label' => 'Lançamento de Notas',
                'url' => ['enrollment/reportCard'],
                'icon' => 't-report_card',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::INSTRUCTOR, TRole::READER],
                'feature' => TFeature::FEAT_DIARY_GRADES_BUZIOS,
            ],
            [
                'label' => 'Lançamento de Notas',
                'url' => ['enrollment/gradesRelease'],
                'icon' => 't-report_card',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::INSTRUCTOR, TRole::READER],
                'feature' => TFeature::FEAT_DIARY_GRADES_FINAL,
            ],
            [
                'label' => 'Ficha AEE',
                'url' => ['aeerecord/default/'],
                'icon' => 't-icon-copy',
                'roles' => [TRole::INSTRUCTOR],
                'feature' => TFeature::FEAT_DIARY_AEE_SHEET,
            ],
            [
                'label' => 'Ficha AEE',
                'url' => ['aeerecord/default/admin'],
                'icon' => 't-icon-copy',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
                'feature' => TFeature::FEAT_DIARY_AEE_SHEET,
            ],
        ],
    ],

    // Coordenador
    [
        'label' => 'Aulas Ministradas',
        'url' => ['classes/validateClassContents'],
        'icon' => 't-icon-topics',
        'roles' => [TRole::COORDINATOR],
        'feature' => TTask::TASK_DIARY_RECORD,
    ],
    [
        'label' => 'Plano de Aula',
        'url' => ['courseplan/courseplan'],
        'icon' => 't-icon-diary',
        'roles' => [TRole::COORDINATOR],
        'feature' => TTask::TASK_DIARY_RECORD,
    ],
    [
        'label' => 'Ficha AEE',
        'url' => ['aeerecord/default/admin'],
        'icon' => 't-icon-copy',
        'roles' => [TRole::COORDINATOR],
        'feature' => TTask::TASK_DIARY_RECORD,
    ],
    [
        'label' => 'Diário de Classe',
        'url' => ['classdiary/default/'],
        'icon' => 't-classdiary',
        'roles' => [TRole::INSTRUCTOR],
        'feature' => TTask::TASK_DIARY_RECORD,
    ],

    [
        'label' => 'Relatórios',
        'url' => ['reports'],
        'icon' => 't-icon-column_graphi',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_REPORTS_ACCESS,
    ],
    [
        'label' => 'Questionário',
        'url' => ['quiz'],
        'icon' => 't-icon-question-group',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_QUIZ_ACCESS,
    ],

    // Merenda Escolar
    [
        'label' => 'Merenda Escolar',
        'url' => ['foods'],
        'icon' => 't-icon-apple',
        'roles' => [TRole::NUTRITIONIST, TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_FOODS_MENU_MANAGE,
    ],

     [
        'label' => 'Merenda Escolar',
        'url' => ['lunch'],
        'icon' => 't-icon-apple',
        'roles' => [TRole::NUTRITIONIST, TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_LUNCH_MENU_MANAGE,
    ],

    // Integrações
    [
        'menu_id' => 'menu-integrations',
        'submenu_id' => 'submenu-integrations',
        'label' => 'Integrações',
        'icon' => 't-icon-integration',
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
        'feature' => TTask::TASK_INTEGRATIONS_ACCESS,
        'submenu' => [
            [
                'label' => 'Educacenso',
                'url' => ['censo/index'],
                'icon' => 't-icon-educacenso',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
                'feature' => TFeature::FEAT_INTEGRATIONS_CENSO,
            ],
            [
                'label' => 'Sagres',
                'url' => ['sagres'],
                'icon' => 't-icon-sagres',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
                'feature' => TFeature::FEAT_INTEGRATIONS_SAGRES,
            ],
            [
                'label' => 'SEDSP',
                'url' => ['sedsp'],
                'icon' => 't-icon-sp',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
                'feature' => TFeature::FEAT_INTEGRATIONS_SEDSP,
            ],
            [
                'label' => 'Gestão Presente',
                'url' => ['gestaopresente'],
                'icon' => 't-icon-educacenso',
                'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER],
                'feature' => TFeature::FEAT_INTEGRATIONS_GESTAOPRESENTE,
            ],
        ],
    ],

    [
        'label' => 'Alterar senha',
        'url' => function() {
            return ['admin/editPassword', ['id' => Yii::app()->user->loginInfos->id]];
        },
        'roles' => [TRole::ADMIN, TRole::MANAGER, TRole::READER, TRole::INSTRUCTOR, TRole::NUTRITIONIST, ],
        'icon' => 't-icon-lock',
        'feature' => TFeature::FEAT_ADMIN_GENERAL,
    ],

    // Administração
    [
        'label' => 'Administração',
        'url' => ['admin'],
        'icon' => 't-icon-configuration-adm',
        'roles' => [TRole::ADMIN, TRole::READER],
        'feature' => TTask::TASK_ADMIN_GENERAL,
    ],
    [
        'label' => 'Gestão de Resultados',
        'icon' => 't-icon-bar_graph',
        'url' =>  ['dashboard'],
        'roles' => [TRole::ADMIN, TRole::READER],
        'feature' => TTask::TASK_MANAGEMENT_PERFORMANCE_BI,
    ],
    [
        'label' => 'Gestão de Resultados',
        'url' =>  ['resultsmanagement'],
        'icon' => 't-icon-bar_graph',
        'roles' => [TRole::ADMIN, TRole::READER],
        'feature' => TTask::TASK_MANAGEMENT_PERFORMANCE,
    ],
];



TMenu::renderMenu($menuItems);
