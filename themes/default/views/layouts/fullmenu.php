<?php

$baseUrl = Yii::app()->theme->baseUrl;

if (Yii::app()->user->isGuest) {
    $this->redirect(yii::app()->createUrl('site/login'));
}

$schoolLogo = $baseUrl . "/img/emblema-escola.svg";
$urlSchoolLogo = '/?r=school/displayLogo&id=' . Yii::app()->user->school;
$schoolurl = yii::app()->createUrl('school');


$selectSchool = '';


if (TagUtils::checkAccess(['admin', 'nutritionist', 'reader'])) {
    $selectSchool = CHtml::activeDropDownList(
        SchoolIdentification::model(),
        'inep_id',
        Chtml::listData(Yii::app()->user->usersSchools, 'inep_id', 'name'),
        array('empty' => 'Selecione a escola', 'class' => 'select-school', 'id2' => 'school', 'options' => array(Yii::app()->user->school => array('selected' => true)))
    );
} else {
    if (TagUtils::checkAccess(TRole::GUARDIAN)) {
        $selectSchool = CHtml::activeDropDownList(
            UsersSchool::model(),
            'school_fk',
            [],
            [
                'empty' => 'TAG',
                'class' => 'select-school',
                'disabled' => 'disabled',
                'id2' => 'school',
                'options' => [Yii::app()->user->school => array('selected' => true)]
            ]
        );
    } else {
        $selectSchool = CHtml::activeDropDownList(
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

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-K8P42N6XZ5');
    </script>

    <!-- Hotjar Tracking Code for https://demo.tag.ong.br -->
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 3615212,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>
</head>

<body>
    <!-- Main Container Fluid -->
    <div class="container-fluid fluid menu-left">
        <!-- Top navbar -->
        <div class="tag-topbar hidden-print ">

            <!-- Top Menu Right -->
            <ul class="tag-topbar__content">
                <li class="tag-topbar__item">
                    <a onclick="history.go(-1);" class="tag-topbar__voltar">
                        <img alt="icone de voltar" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/voltar_icon.png" />
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
                            <img id="alt-logo" alt="logo da escola selecionada" src="<?php echo $schoolLogo ?>" class="tag-topbar__school_logo show" />
                            <img class="tag-topbar__school_logo hidden" src="<?php echo $urlSchoolLogo ?>"
                                alt="emblema da escola"
                                onload="$('#alt-logo').removeClass('show').addClass('hidden'); $(this).removeClass('hidden').addClass('show')" />
                        </div>
                        <div class="column">
                            <form class="school" id2="school"
                                action="<?php echo yii::app()->createUrl('site/changeschool') ?>" method="Post">
                                <?php echo $selectSchool; ?>
                            </form>
                            <div class="tag-topbar__username"><?= Yii::app()->user->loginInfos->username ?></div>
                        </div>
                    </div>
                </li>

                <li id="menu-logout" class="hide-responsive" style="margin-left: auto">
                    <a class="t-button-tertiary" href="<?php echo yii::app()->createUrl('site/logout') ?>">
                        <img alt="iconde de sair em branco" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/sair_branco.svg" />
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
                    <img alt="logo da escola no menu lateral " class="tag-logo" style="width:85px;"
                        src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_navbar.svg" />
                    <a href="#" class="t-badge pull-left" data-toggle="modal" data-target="#change-year"
                        target="_blank">
                        <span class="t-badge__label" id="schoolyear"><?php echo Yii::app()->user->year; ?></span>
                        <i class="t-badge__icon fa fa-chevron-down"></i>
                    </a>
                </div>
                <div class="slim-scroll" data-scroll-height="800px">
                    <ul class="t-menu">
                       <?php $this->renderPartial("//layouts/menus/_admin_menu"); ?>
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
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                    style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title" id="myModalLabel">Selecione o ano</h4>
        </div>
        <form class="form-vertical" id="createCalendar" action="<?php echo yii::app()->createUrl('site/changeYear') ?>"
            method="post">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        style="background: #EFF2F5; color:#252A31;">Voltar</button>
                    <button class="btn btn-primary" url="<?php echo Yii::app()->createUrl('admin/changeYear'); ?>"
                        type="submit" value="Alterar" style="background: #3F45EA; color: #FFFFFF;"> Selecionar Ano
                    </button>
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
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.qrcode.min.js"
        type="text/javascript"></script>
    <script
        src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.min.js?v=<?= TAG_VERSION ?>'></script>
    <script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/purify.min.js'></script>
    <script src='<?php echo Yii::app()->baseUrl; ?>/js/layout/functions.js?v=<?= TAG_VERSION ?>'></script>
    <script src='<?php echo Yii::app()->baseUrl; ?>/js/datatables/init.js?v=<?= TAG_VERSION ?>'></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datatables.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/datatablesptbr.js"></script>
</body>

</html>
