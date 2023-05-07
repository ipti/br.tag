<?php

$baseUrl = Yii::app()->theme->baseUrl;

$school_logo = $baseUrl . "/img/emblema-escola.svg";
$url_school_logo = '/?r=school/displayLogo&id=' . Yii::app()->user->school;
$schoolurl = yii::app()->createUrl('school');

$select_school = '';

if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
    $select_school = CHtml::activeDropDownList(
        SchoolIdentification::model(),
        'inep_id',
        Chtml::listData(Yii::app()->user->usersSchools, 'inep_id', 'name'),
        array('empty' => 'Selecione a escola', 'class' => 'select-school', 'id2' => 'school', 'options' => array(Yii::app()->user->school => array('selected' => true)))
    );
} else {
    $select_school = CHtml::activeDropDownList(
        UsersSchool::model(),
        'school_fk',
        Chtml::listData(Yii::app()->user->usersSchools, 'school_fk', 'schoolFk.name'),
        [
            'empty' => 'Selecione a escola',
            'class' => 'select-school',
            'id2' => 'school',
            'options' => [Yii::app()->user->school => array('selected' => true)]
        ]
    );
}

if (Yii::app()->user->isGuest) {
    $this->redirect(yii::app()->createUrl('site/login'));
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]>
<html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!-->
<html><!-- <![endif]-->

<head>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ba-bbq.js"></script>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="referrer" content="unsafe-url" />
    <meta name="referrer" content="origin" />
    <meta name="referrer" content="no-referrer-when-downgrade" />
    <meta name="referrer" content="origin-when-cross-origin" />
    <meta name="referrer" content="no-referrer" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/responsive.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/template.css?v=1.2" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/template2.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->baseUrl; ?>/sass/css/main.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/glyphicons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/select2.css" rel="stylesheet" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" rel="stylesheet" type="text/css" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/admin.css" rel="stylesheet" type="text/css" />
    <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.print.css' media='print' />
    <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui-1.9.2.custom.min.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.min.css' />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/home.css?v=1.0" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/datatables.min.css" />
</head>

<body>
    <!-- Main Container Fluid -->
    <div class="container-fluid fluid menu-left">
        <!-- Top navbar -->
        <div class="tag-topbar hidden-print ">

            <!-- Brand -->
            <!-- <a href="<?php echo Yii::app()->homeUrl; ?>" class="appbrand pull-left"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_logo.png" style="float:left;padding: 8px 0 0 0;height: 27px;" /><span id="schoolyear"><?php echo Yii::app()->user->year; ?></span></a> -->

            <!-- Top Menu Right -->
            <ul class="tag-topbar__content">
                <li class="tag-topbar__item">
                    <a onclick="history.go(-1);" class="tag-topbar__voltar">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/voltar_icon.png" />
                        Voltar
                    </a>
                </li>
                <li class="tag-topbar__item ">
                    <div class="tag-topbar__toggle js-toggle-drawer">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </li>

                <li class="tag-topbar__item">
                    <div class="mobile-row justify-content--start">
                        <div>
                            <img id="alt-logo" src="<?php echo $school_logo ?>" class="tag-topbar__school_logo show" />
                            <img class="tag-topbar__school_logo hidden" src="<?php echo $url_school_logo ?>" alt="emblema da escola" onload="document.getElementById('alt-logo').classList.replace('show', 'hidden'); this.classList.replace('hidden', 'show')" />
                        </div>
                        <div class="column">
                            <form class="school" id2="school" action="<?php echo yii::app()->createUrl('site/changeschool') ?>" method="Post">
                                <?php echo $select_school; ?>
                            </form>
                            <div class="tag-topbar__username"><?= Yii::app()->user->loginInfos->username ?></div>
                        </div>

                    </div>
                </li>
                <li id="menu-logout" class="hide-responsive" style="margin-left: auto">
                    <a class="t-button-tertiary" href="<?php echo yii::app()->createUrl('site/logout') ?>">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sair_branco.svg" />
                        Sair
                    </a>
                </li>
            </ul>
        </div>
        <!-- Top navbar END -->

        <!-- Sidebar menu & content wrapper -->
        <div id="wrapper">
            <!-- Sidebar menu -->
            <div class="t-drawer js-drawer hidden-print t-drawer--mobile-hidden">
                <div class="colorful-bar">
                    <span id="span-color-blue"></span>
                    <span id="span-color-red"></span>
                    <span id="span-color-green"></span>
                    <span id="span-color-yellow"></span>
                </div>
                <div class="t-drawer-header column align-items--end logo-container">
                    <img class="tag-logo" style="width:85px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_navbar.svg" />
                    <a href="#" class="t-badge pull-left" data-toggle="modal" data-target="#change-year" target="_blank">
                        <span class="t-badge__label" id="schoolyear"><?php echo Yii::app()->user->year; ?></span>
                        <i class="t-badge__icon fa fa-chevron-down"></i>
                    </a>
                </div>
                <div class="slim-scroll" data-scroll-height="800px">
                    <ul class="t-menu">
                        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=site") || $_SERVER['REQUEST_URI'] == "/" ? 'active' : '' ?> hide-responsive">
                            <a href="/" class="t-menu-item__link">
                                <span class="t-icon-home t-menu-item__icon"></span>
                                <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/home.svg" /> -->
                                <span class="t-menu-item__text">Página Inicial</span>
                            </a>
                        </li>
                        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)) : ?>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=school") ? 'active' : '' ?>">
                                <?php
                                if (count(Yii::app()->user->usersSchools) == 1) {
                                    $schoolurl = yii::app()->createUrl('school/update', array('id' => yii::app()->user->school));
                                }
                                ?>
                                <a class="t-menu-item__link" href="<?php echo $schoolurl ?>">
                                    <span class="t-icon-school t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/escola.svg" /> -->
                                    <span class="t-menu-item__text">Escola</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classroom") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classroom') ?>">
                                    <span class="t-icon-people t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/turmas.svg" /> -->
                                    <span class="t-menu-item__text">Turmas</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=student") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('student') ?>">
                                    <span class="t-icon-pencil t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/alunos.svg" /> -->
                                    <span class="t-menu-item__text">Alunos</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=instructor") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('instructor') ?>">
                                    <span class="t-icon-book t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/professores.svg" /> -->
                                    <span class="t-menu-item__text">Professores</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=calendar") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('calendar') ?> ">
                                    <span class="t-icon-calendar t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/calendario.svg" /> -->
                                    <span class="t-menu-item__text">Calendário Escolar</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=curricularmatrix") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('curricularmatrix') ?> ">
                                    <span class="t-icon-line_graph t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/matriz_curricular.svg" /> -->
                                    <span class="t-menu-item__text">Matriz Curricular</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=timesheet") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('timesheet') ?> ">
                                    <span class="t-icon-blackboard t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/quadro_de_horarios.svg" /> -->
                                    <span class="t-menu-item__text">Quadro de Horário</span>
                                </a>
                            </li>
                        <?php endif ?>
                        <li id="menu-electronic-diary" class="t-menu-group <?=
                                                                            strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=enrollment/grades")
                                                                                ? 'active' : '' ?>">
                                                                                <i class="submenu-icon fa fa-chevron-right"></i>
                                                                                <i class="submenu-icon fa fa-chevron-down"></i>
                            <a id="menu-electronic-diary-trigger" data-toggle="collapse" class="t-menu-group__link" href="#submenu-electronic-diary">
                                <span class="t-icon-schedule t-menu-item__icon t-menu-group__icon"></span>
                                <!-- <img class="t-menu-group__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/diario_eletronico.svg" /> -->
                                <span class="t-menu-group__text">Diário Eletrônico</span>
                            </a>
                            <ul class="collapse <?=
                                                strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=enrollment/grades") ? 'in' : '' ?>" id="submenu-electronic-diary">

                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('courseplan') ?>">
                                        <span class="t-icon-diary t-menu-item__icon"></span>
                                        <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/plano_de_aula.svg" /> -->
                                        <span class="t-menu-item__text">Plano de Aula</span>
                                    </a>
                                </li>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classes/classContents') ?>">
                                        <span class="t-icon-topics t-menu-item__icon"></span>
                                        <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/aulas_ministradas.svg" /> -->
                                        <span class="t-menu-item__text">Aulas Ministradas</span>
                                    </a>
                                </li>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classes/frequency') ?>">
                                        <span class="t-icon-checklist t-menu-item__icon"></span>
                                        <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/frequencia.svg" /> -->
                                        <span class="t-menu-item__text">Frequência</span>
                                    </a>
                                </li>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=enrollment/grades") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('enrollment/grades') ?> ">
                                        <span class="t-icon-edition t-menu-item__icon"></span>
                                        <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/notas.svg" /> -->
                                        <span class="t-menu-item__text">Notas</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=reports") ? 'active' : '' ?> hide-responsive">
                            <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('reports') ?>">
                                <span class="t-icon-column_graphi t-menu-item__icon"></span>
                                <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/relatorios.svg" /> -->
                                <span class="t-menu-item__text">Relatórios</span>
                            </a>
                        </li>
                        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)) : ?>
                            <li id="menu-quiz" class="t-menu-item  <?= strpos($_SERVER['REQUEST_URI'], "?r=quiz") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('quiz') ?>">
                                    <span class="t-icon-question-group t-menu-item__icon"></span>
                                    <!-- <img alt="Questionario" class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/quizzes.svg" /> -->
                                    <span class="t-menu-item__text">Questionário</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=lunch") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('lunch') ?> ">
                                    <span class="t-icon-apple t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/merenda.svg" /> -->
                                    <span class="t-menu-item__text">Merenda Escolar</span>
                                </a>
                            </li>
                            <li id="menu-integrations" class="t-menu-group <?=
                                                                            strpos($_SERVER['REQUEST_URI'], "?r=censo/validate") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=sagres") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=sedsp")
                                                                                ? 'active' : '' ?>"><i class="submenu-icon fa fa-chevron-right"></i><i class="submenu-icon fa fa-chevron-down"></i>
                                <a id="menu-integrations-trigger" data-toggle="collapse" class="t-menu-group__link" href="#submenu-integrations">
                                    <span class="t-icon-integration t-menu-item__icon t-menu-group__icon"></span>
                                    <!-- <img class="t-menu-group__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/integration.svg" alt="engrenagem de integração" /> -->
                                    <span class="t-menu-group__text">Integrações</span>
                                </a>
                                <ul class="collapse <?=
                                                    strpos($_SERVER['REQUEST_URI'], "?r=censo/validate") ||
                                                        strpos($_SERVER['REQUEST_URI'], "?r=sagres") ||
                                                        strpos($_SERVER['REQUEST_URI'], "?r=sedsp") ? 'in' : '' ?>" id="submenu-integrations">

                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=censo/validate") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('censo/validate') ?> ">
                                            <span class="t-icon-educacenso t-menu-item__icon"></span>
                                            <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/educacenso.svg" alt="censo logo" /> -->
                                            <span class="t-menu-item__text">Educacenso</span>
                                        </a>
                                    </li>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=sagres") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('sagres') ?> ">
                                            <span class="t-icon-sagres t-menu-item__icon"></span>
                                            <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/sagres.svg" alt="sagres logo" /> -->
                                            <span class="t-menu-item__text">Sagres</span>
                                        </a>
                                    </li>
                                    <?php if (INSTANCE == "UBATUBA" || INSTANCE == "TREINAMENTO" || INSTANCE == "LOCALHOST") { ?>
                                        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=sedsp") ? 'active' : '' ?>">
                                            <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('sedsp') ?>">
                                                <span class="t-icon-sp  t-menu-item__icon"></span>
                                                <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/sedsp.svg" alt="sedsp logo" /> -->
                                                <span class="t-menu-item__text">SEDSP</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php endif ?>
                        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=admin") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('admin') ?>">
                                    <span class="t-icon-configuration-adm t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/administracao.svg" /> -->
                                    <span class="t-menu-item__text">Administração</span>
                                </a>
                            </li>
                            <li class="t-menu-item hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('resultsmanagement') ?>">
                                    <span class="t-icon-bar_graph t-menu-item__icon"></span>
                                    <!-- <img class="t-menu-item__icon" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sidebarIcons/gestao-de-resultados.svg" /> -->
                                    <span class="t-menu-item__text">Gestão de Resultados</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

            </div>

            <!-- // Sidebar Menu END -->

            <!-- Content -->
            <div id="content">
                <?php echo $content; ?>
            </div>
            <!-- // Content END -->

        </div>
        <div class="clearfix"></div>
        <!-- // Sidebar menu & content wrapper END -->
    </div>

    <div class="menu-cover"></div>

    <div class="modal fade modal-content" id="change-year" tabindex="-1" role="dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Selecione o ano</h4>
        </div>
        <form class="form-vertical" id="createCalendar" action="<?php echo yii::app()->createUrl('site/changeYear') ?>" method="post">
            <div class="modal-body">
                <div class="row-fluid">
                    <div class=" span12">
                        <?php echo CHtml::label(yii::t('default', 'Year'), 'year', array('class' => 'control-label')); ?>
                        <select name="years" id="years" placeholder="Selecione o ano" style="width:100%">
                            <?php
                            $years = range(date('Y'), 2014);
                            echo "<option value='' selected>Selecione o ano</option>";
                            for ($i = 0; $i < count($years); $i++) {
                                echo "<option value=" . $years[$i] . ">" . $years[$i] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" url="<?php echo Yii::app()->createUrl('admin/changeYear'); ?>" type="submit" value="Alterar" style="background: #3F45EA; color: #FFFFFF;"> Selecionar Ano </button>
                </div>
        </form>
    </div>

    <!-- // Main Container Fluid END -->
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mask.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap-datepicker.pt-BR.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js?v=1.0"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/uniform.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2-locale-pt-BR.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.qrcode.min.js" type="text/javascript"></script>
    <script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.min.js'></script>
    <script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/purify.min.js'></script>
    <script src='<?php echo Yii::app()->baseUrl; ?>/js/layout/functions.js'></script>
    <script src='<?php echo Yii::app()->baseUrl; ?>/js/datatables/init.js'></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datatables.min.js"></script>
</body>

</html>