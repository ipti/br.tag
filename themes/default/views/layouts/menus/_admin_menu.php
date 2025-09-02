<?php

$menuItems = [
    [
        'label' => 'Matrícula Online',
        'url' => ['enrollmentonline/Enrollmentonlinestudentidentification/StudentStatus'],
        'icon' => 't-icon-backpack',
        'roles' => ['guardian'],
    ],
    [
        'label' => 'Página Inicial',
        'url' => ['/'],
        'icon' => 't-icon-home',
        'roles' => ['!guardian'], // quem não é guardian
    ],
    // Menu Escola, Turmas, Alunos, Professores
    [
        'label' => 'Escola',
        'url' => function() {
            return (count(Yii::app()->user->usersSchools) == 1) 
                ? ['school/update', ['id' => Yii::app()->user->school]]
                : ['school/index'];
        },
        'icon' => 't-icon-school',
        'roles' => ['admin', 'manager', 'reader'],
    ],
    [
        'label' => 'Turmas',
        'url' => ['classroom'],
        'icon' => 't-icon-people',
        'roles' => ['admin', 'manager', 'reader'],
    ],
    [
        'label' => 'Alunos',
        'url' => ['student'],
        'icon' => 't-icon-pencil',
        'roles' => ['admin', 'manager', 'reader'],
    ],
    [
        'label' => 'Professores',
        'url' => ['instructor'],
        'icon' => 't-icon-book',
        'roles' => ['admin', 'manager', 'reader'],
    ],
    [
        'label' => 'Calendário Escolar',
        'url' => ['calendar'],
        'icon' => 't-icon-calendar',
        'roles' => ['admin', 'manager', 'reader', 'instructor'],
    ],
    [
        'label' => 'Matriz Curricular',
        'url' => ['curricularmatrix'],
        'icon' => 't-icon-line_graph',
        'roles' => ['admin', 'manager', 'reader'],
    ],
    [
        'label' => 'Quadro de Horário',
        'url' => ['timesheet'],
        'icon' => 't-icon-blackboard',
        'roles' => ['admin', 'manager', 'reader'],
    ],
    // Diário Eletrônico
    [
        'label' => 'Diário Eletrônico',
        'icon' => 't-icon-schedule',
        'roles' => ['!guardian', '!nutritionist', '!coordinator'],
        'submenu' => [
            [
                'label' => 'Plano de Aula',
                'url' => ['courseplan/courseplan'],
                'icon' => 't-icon-diary',
            ],
            [
                'label' => 'Aulas Ministradas',
                'url' => ['classes/classContents'],
                'icon' => 't-icon-topics',
                'roles' => ['admin', 'reader', 'manager', 'instructor'],
                'feature' => 'FEAT_FREQ_CLASSCONT',
            ],
            [
                'label' => 'Frequência',
                'url' => ['classes/frequency'],
                'icon' => 't-icon-checklist',
                'roles' => ['admin', 'reader', 'manager', 'instructor'],
                'feature' => 'FEAT_FREQ_CLASSCONT',
            ],
            [
                'label' => 'Notas',
                'url' => ['grades/grades'],
                'icon' => 't-icon-edition',
                'feature' => 'FEAT_GRADES',
            ],
            [
                'label' => 'Lançamento de Notas',
                'url' => ['enrollment/reportCard'],
                'icon' => 't-report_card',
                'feature' => 'FEAT_REPORTCARD',
            ],
            [
                'label' => 'Lançamento de Notas',
                'url' => ['enrollment/gradesRelease'],
                'icon' => 't-report_card',
                'feature' => 'FEAT_GRADESRELEASE',
            ],
            [
                'label' => 'Ficha AEE',
                'url' => ['aeerecord/default/'],
                'icon' => 't-icon-copy',
                'roles' => ['instructor'],
            ],
            [
                'label' => 'Ficha AEE',
                'url' => ['aeerecord/default/admin'],
                'icon' => 't-icon-copy',
                'roles' => ['admin', 'reader', 'manager'],
            ],
        ],
    ],
    // Aulas e plano de aula para coordenador
    [
        'label' => 'Aulas Ministradas',
        'url' => ['classes/validateClassContents'],
        'icon' => 't-icon-topics',
        'roles' => ['coordinator'],
    ],
    [
        'label' => 'Plano de Aula',
        'url' => ['courseplan/courseplan'],
        'icon' => 't-icon-diary',
        'roles' => ['coordinator'],
    ],
    [
        'label' => 'Ficha AEE',
        'url' => ['aeerecord/default/admin'],
        'icon' => 't-icon-copy',
        'roles' => ['coordinator'],
    ],
    [
        'label' => 'Diário de Classe',
        'url' => ['classdiary/default/'],
        'icon' => 't-classdiary',
        'roles' => ['instructor'],
    ],
    [
        'label' => 'Relatórios',
        'url' => ['reports'],
        'icon' => 't-icon-column_graphi',
        'roles' => ['admin', 'manager', 'reader'],
    ],
    [
        'label' => 'Questionário',
        'url' => ['quiz'],
        'icon' => 't-icon-question-group',
        'roles' => ['admin', 'manager', 'reader'],
    ],
    // Merenda Escolar
    [
        'label' => 'Merenda Escolar',
        'url' => function() {
            return Yii::app()->features->isEnable("FEAT_FOOD") 
                ? ['foods'] : ['lunch'];
        },
        'icon' => 't-icon-apple',
        'roles' => ['!guardian', 'nutritionist', 'admin', 'manager', 'reader'],
    ],
    // Integrações
    [
        'menu_id' => 'menu-integrations-trigger',
        'submenu_id' => 'submenu-integrations',
        'label' => 'Integrações',
        'icon' => 't-icon-integration',
        'roles' => ['admin', 'manager', 'reader'],
        'submenu' => [
            ['label' => 'Educacenso', 'url' => ['censo/index'], 'icon' => 't-icon-educacenso'],
            ['label' => 'Sagres', 'url' => ['sagres'], 'icon' => 't-icon-sagres', 'visible' => (INSTANCE != "BUZIOS")],
            ['label' => 'SEDSP', 'url' => ['sedsp'], 'icon' => 't-icon-sp', 'feature' => 'FEAT_SEDSP'],
            ['label' => 'Gestão Presente', 'url' => ['gestaopresente'], 'icon' => 't-icon-educacenso'],
        ],
    ],
    // Alterar senha
    [
        'label' => 'Alterar senha',
        'url' => function() {
            return ['admin/editPassword', ['id' => Yii::app()->user->loginInfos->id]];
        },
        'icon' => 't-icon-lock',
    ],
    // Administração
    [
        'label' => 'Administração',
        'url' => ['admin'],
        'icon' => 't-icon-configuration-adm',
        'roles' => ['admin', 'reader'],
    ],
    [
        'label' => 'Gestão de Resultados',
        'url' => function() {
            return Yii::app()->features->isEnable("FEAT_DASHBOARD_POWER") 
                ? ['dashboard'] : ['resultsmanagement'];
        },
        'icon' => 't-icon-bar_graph',
        'roles' => ['admin', 'reader'],
    ],
];



TMenu::renderMenu($menuItems);