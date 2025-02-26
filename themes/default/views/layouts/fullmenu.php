<?php

$baseUrl = Yii::app()->theme->baseUrl;

if (Yii::app()->user->isGuest) {
    $this->redirect(yii::app()->createUrl('site/login'));
}

$school_logo = $baseUrl . "/img/emblema-escola.svg";
$url_school_logo = '/?r=school/displayLogo&id=' . Yii::app()->user->school;
$schoolurl = yii::app()->createUrl('school');

$select_school = '';


if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)  || Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id)) {
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



$assetManager = Yii::app()->getAssetManager();
$assetUrl = Yii::app()->theme->baseUrl;

$cs = Yii::app()->getClientScript();

// Base Layout
$cs->registerCssFile($assetUrl . "/css/bootstrap.min.css");
$cs->registerCssFile($assetUrl . "/css/responsive.min.css");
$cs->registerCssFile($assetUrl . "/css/print.css", "print");

// 3rd party libraries
$cs->registerCssFile($assetUrl . "/css/select2.css");
$cs->registerCssFile($assetUrl . "/css/datatables.min.css");
$cs->registerCssFile($assetUrl . "/css/bootstrap-datepicker.min.css");
$cs->registerCssFile($assetUrl . '/js/jquery/fullcalendar/fullcalendar.css');
$cs->registerCssFile($assetUrl . '/js/jquery/fullcalendar/fullcalendar.print.css', 'print');
$cs->registerCssFile($assetUrl . '/css/jquery-ui-1.9.2.custom.min.css');

$cs->registerCssFile($assetUrl . "/css/glyphicons.min.css");
$cs->registerCssFile($assetUrl . '/css/font-awesome.min.css');

// Custom styles
$cs->registerCssFile($assetUrl . "/css/template.css?v=" . TAG_VERSION);
$cs->registerCssFile($assetUrl . '/css/template2.css?v=' . TAG_VERSION);
$cs->registerCssFile($assetUrl . "/css/admin.css?v=" . TAG_VERSION);
$cs->registerCssFile($assetUrl . "/css/home.css?v=" . TAG_VERSION);
$cs->registerCssFile(Yii::app()->baseUrl . "/css/form.css?v=" . TAG_VERSION);
$cs->registerCssFile(Yii::app()->baseUrl . "/sass/css/main.css?v=" . TAG_VERSION);

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


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-K8P42N6XZ5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-K8P42N6XZ5');
    </script>

    <!-- Hotjar Tracking Code for https://demo.tag.ong.br -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:3615212,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
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
                            <img class="tag-topbar__school_logo hidden" src="<?php echo $url_school_logo ?>" alt="emblema da escola" onload="$('#alt-logo').removeClass('show').addClass('hidden'); $(this).removeClass('hidden').addClass('show')" />
                        </div>
                        <div class="column">
                            <form class="school" id2="school" action="<?php echo yii::app()->createUrl('site/changeschool') ?>" method="Post">
                                <?php echo $select_school; ?>
                            </form>
                            <div class="tag-topbar__username"><?= Yii::app()->user->loginInfos->username ?></div>
                        </div>

                    </div>
                </li>

                    <?php
                    if(!Yii::app()->user->getState("rememberMe")):
                        // $this->beginWidget('zii.widgets.CPortlet', array(
                        //     'title' => '',
                        // ));
                        // echo $this->renderPartial('/layouts/_session_timer', ['sessionTime' => SessionTimer::getSessionTime()]);
                        // $this->endWidget();
                    endif;
                    ?>
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
                                    <span class="t-menu-item__text">Escola</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classroom") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classroom') ?>">
                                    <span class="t-icon-people t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Turmas</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=student") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('student') ?>">
                                    <span class="t-icon-pencil t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Alunos</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=instructor") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('instructor') ?>">
                                    <span class="t-icon-book t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Professores</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=calendar") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('calendar') ?> ">
                                    <span class="t-icon-calendar t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Calendário Escolar</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=curricularmatrix") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('curricularmatrix') ?> ">
                                    <span class="t-icon-line_graph t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Matriz Curricular</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=timesheet") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('timesheet') ?> ">
                                    <span class="t-icon-blackboard t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Quadro de Horário</span>
                                </a>
                            </li>
                        <?php endif ?>


                        <?php if($i = Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) :?>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=calendar") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('calendar') ?> ">
                                    <span class="t-icon-calendar t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Calendário Escolar</span>
                                </a>
                            </li>
                        <?php endif ?>

                        <?php if(!Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id) &&
                                !Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)):?>
                        <li id="menu-electronic-diary" class="t-menu-group <?=
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=grades/grades") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=enrollment/reportCard")
                                                                                ? 'active' : '' ?>">
                            <i class="submenu-icon fa fa-chevron-right"></i>
                            <i class="submenu-icon fa fa-chevron-down"></i>
                            <a id="menu-electronic-diary-trigger" data-toggle="collapse" class="t-menu-group__link" href="#submenu-electronic-diary">
                                <span class="t-icon-schedule t-menu-item__icon t-menu-group__icon"></span>
                                <span class="t-menu-group__text">Diário Eletrônico</span>
                            </a>
                            <ul class="collapse <?=
                                                    strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=grades/grades") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=enrollment/reportCard") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=enrollment/gradesRelease") ||
                                                    strpos($_SERVER['REQUEST_URI'], "?r=aeerecord") ? 'in' : '' ?>" id="submenu-electronic-diary">

                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('courseplan/courseplan') ?>">
                                        <span class="t-icon-diary t-menu-item__icon"></span>
                                        <span class="t-menu-item__text">Plano de Aula</span>
                                    </a>
                                </li>
                                <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)):?>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classes/classContents') ?>">
                                        <span class="t-icon-topics t-menu-item__icon"></span>
                                        <span class="t-menu-item__text">Aulas Ministradas</span>
                                    </a>
                                </li>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classes/frequency') ?>">
                                        <span class="t-icon-checklist t-menu-item__icon"></span>
                                        <span class="t-menu-item__text">Frequência</span>
                                    </a>
                                </li>
                                <?php endif ?>
                                <?php if(Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id) && (Yii::app()->features->isEnable("FEAT_FREQ_CLASSCONT"))):?>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/classContents") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classes/classContents') ?>">
                                        <span class="t-icon-topics t-menu-item__icon"></span>
                                        <span class="t-menu-item__text">Aulas Ministradas</span>
                                    </a>
                                </li>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/frequency") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classes/frequency') ?>">
                                        <span class="t-icon-checklist t-menu-item__icon"></span>
                                        <span class="t-menu-item__text">Frequência</span>
                                    </a>
                                </li>
                                <?php endif ?>
                                <?php if (Yii::app()->features->isEnable("FEAT_GRADES")): ?>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=grades/grades") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('grades/grades') ?> ">
                                            <span class="t-icon-edition t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Notas</span>
                                        </a>
                                    </li>
                                <?php endif ?>
                                <?php if (Yii::app()->features->isEnable("FEAT_REPORTCARD")): ?>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=enrollment/reportCard") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('enrollment/reportCard') ?> ">
                                            <span class="t-report_card t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Lançamento de Notas</span>
                                        </a>
                                    </li>
                                <?php endif ?>
                                <?php if (Yii::app()->features->isEnable("FEAT_GRADESRELEASE")): ?>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=enrollment/gradesRelease") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('enrollment/gradesRelease') ?> ">
                                            <span class="t-report_card t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Lançamento de Notas</span>
                                        </a>
                                    </li>
                                <?php endif ?>
                                <?php if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) : ?>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=aeerecord") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('aeerecord/default/') ?> ">
                                            <span class="t-icon-copy t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Ficha AEE</span>
                                        </a>
                                    </li>
                                <?php endif ?>
                                <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ||
                                Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)) : ?>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=aeerecord") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('aeerecord/default/admin') ?> ">
                                            <span class="t-icon-copy t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Ficha AEE</span>
                                        </a>
                                    </li>
                                <?php endif ?>
                            </ul>
                            <?php endif?>
                        </li>
                        <?php if (Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)) : ?>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=courseplan") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('courseplan/courseplan') ?>">
                                    <span class="t-icon-diary t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Plano de Aula</span>
                                </a>
                            </li>
                         <?php endif ?>
                        <?php if (Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)) : ?>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classes/validateClassContents") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classes/validateClassContents') ?>">
                                    <span class="t-icon-topics t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Aulas Ministradas</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=instructor/frequency") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('instructor/frequency') ?>">
                                    <span class="t-icon-checklist t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Frequência</span>
                                </a>
                            </li>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=grades/grades") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('grades/grades') ?>">
                                    <span class="t-icon-edition t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Notas</span>
                                </a>
                            </li>
                        <?php endif ?>
                         <?php if (Yii::app()->getAuthManager()->checkAccess('coordinator', Yii::app()->user->loginInfos->id)) : ?>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=aeerecord") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('aeerecord/default/admin') ?> ">
                                    <span class="t-icon-copy t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Ficha AEE</span>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if (Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) : ?>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=classdiary/default/") ? 'active' : '' ?>">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('classdiary/default/') ?> ">
                                    <span class="t-classdiary t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Diario de Classe</span>
                                </a>
                            </li>
                         <?php endif ?>
                         <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)) :?>
                        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=reports") ? 'active' : '' ?> hide-responsive">
                            <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('reports') ?>">
                                <span class="t-icon-column_graphi t-menu-item__icon"></span>
                                <span class="t-menu-item__text">Relatórios</span>
                            </a>
                        </li>
                        <?php endif;?>
                        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || Yii::app()->getAuthManager()->checkAccess('manager', Yii::app()->user->loginInfos->id)) : ?>
                            <li id="menu-quiz" class="t-menu-item  <?= strpos($_SERVER['REQUEST_URI'], "?r=quiz") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('quiz') ?>">
                                    <span class="t-icon-question-group t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Questionário</span>
                                </a>
                            </li>
                            <?php if(Yii::app()->features->isEnable("FEAT_FOOD")): ?>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=foods") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('foods') ?> ">
                                        <span class="t-icon-apple t-menu-item__icon"></span>
                                        <span class="t-menu-item__text">Merenda Escolar</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=lunch") ? 'active' : '' ?>">
                                    <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('lunch') ?> ">
                                        <span class="t-icon-apple t-menu-item__icon"></span>
                                        <span class="t-menu-item__text">Merenda Escolar</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li id="menu-integrations" class="t-menu-group <?=
                                                                            strpos($_SERVER['REQUEST_URI'], "?r=censo/validate") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=sagres") ||
                                                                                strpos($_SERVER['REQUEST_URI'], "?r=sedsp")
                                                                                ? 'active' : '' ?>"><i class="submenu-icon fa fa-chevron-right"></i><i class="submenu-icon fa fa-chevron-down"></i>
                                <a id="menu-integrations-trigger" data-toggle="collapse" class="t-menu-group__link" href="#submenu-integrations">
                                    <span class="t-icon-integration t-menu-item__icon t-menu-group__icon"></span>
                                    <span class="t-menu-group__text">Integrações</span>
                                </a>
                                <ul class="collapse <?=
                                                    strpos($_SERVER['REQUEST_URI'], "?r=censo/validate") ||
                                                        strpos($_SERVER['REQUEST_URI'], "?r=sagres") ||
                                                        strpos($_SERVER['REQUEST_URI'], "?r=sedsp") ? 'in' : '' ?>" id="submenu-integrations">

                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=censo/validate") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('censo/validate') ?> ">
                                            <span class="t-icon-educacenso t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Educacenso</span>
                                        </a>
                                    </li>
                                    <?php if (INSTANCE != "BUZIOS") { ?>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=sagres") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('sagres') ?> ">
                                            <span class="t-icon-sagres t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Sagres</span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if (Yii::app()->features->isEnable("FEAT_SEDSP")) { ?>
                                        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=sedsp") ? 'active' : '' ?>">
                                            <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('sedsp') ?>">
                                                <span class="t-icon-sp  t-menu-item__icon"></span>
                                                <span class="t-menu-item__text">SEDSP</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php endif ?>
                        <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=admin/editPassword") ? 'active' : '' ?> hide-responsive">
                            <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('admin/editPassword', array("id" => Yii::app()->user->loginInfos->id)) ?>">
                                <span class="t-icon-lock t-menu-item__icon"></span>
                                <span class="t-menu-item__text">Alterar senha</span>
                            </a>
                        </li>
                        <?php if(Yii::app()->getAuthManager()->checkAccess('nutritionist', Yii::app()->user->loginInfos->id)): ?>
                            <?php if(Yii::app()->features->isEnable("FEAT_FOOD")): ?>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=foods") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('foods') ?> ">
                                            <span class="t-icon-apple t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Merenda Escolar</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=lunch") ? 'active' : '' ?>">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('lunch') ?> ">
                                            <span class="t-icon-apple t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Merenda Escolar</span>
                                        </a>
                                    </li>
                                <?php endif;
                                endif;
                                ?>
                        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
                            <li class="t-menu-item <?= strpos($_SERVER['REQUEST_URI'], "?r=admin") ? 'active' : '' ?> hide-responsive">
                                <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('admin') ?>">
                                    <span class="t-icon-configuration-adm t-menu-item__icon"></span>
                                    <span class="t-menu-item__text">Administração</span>
                                </a>
                            </li>
                            <?php if(Yii::app()->features->isEnable("FEAT_DASHBOARD_POWER")): ?>
                                    <li class="t-menu-item hide-responsive">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('dashboard') ?>">
                                            <span class="t-icon-bar_graph t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Gestão de Resultados</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="t-menu-item hide-responsive">
                                        <a class="t-menu-item__link" href="<?php echo yii::app()->createUrl('resultsmanagement') ?>">
                                            <span class="t-icon-bar_graph t-menu-item__icon"></span>
                                            <span class="t-menu-item__text">Gestão de Resultados</span>
                                        </a>
                                    </li>
                                <?php endif;?>
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
                        <?php echo CHtml::label(yii::t('default', 'Year'), 'years', array('class' => 'control-label')); ?>
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
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js?v=<?= TAG_VERSION ?>"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js?v=<?= TAG_VERSION ?>"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2-locale-pt-BR.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.qrcode.min.js" type="text/javascript"></script>
    <script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.min.js?v=<?= TAG_VERSION ?>'></script>
    <script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/purify.min.js'></script>
    <script src='<?php echo Yii::app()->baseUrl; ?>/js/layout/functions.js?v=<?= TAG_VERSION ?>'></script>
    <script src='<?php echo Yii::app()->baseUrl; ?>/js/datatables/init.js?v=<?= TAG_VERSION ?>'></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datatables.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datatablesptbr.js"></script>
</body>

</html>
