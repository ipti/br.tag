<?php
$cs = Yii::app()->clientScript;
$cs->scriptMap = array(
    'jquery.min.js' => false,
    'jquery.ba-bbq.min.js' => false
);
$baseUrl = Yii::app()->theme->baseUrl;

$currentPage = Yii::app()->controller->id;
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/responsive.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/template.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/glyphicons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/select2.css" rel="stylesheet" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/admin.css" rel="stylesheet" type="text/css" />
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.css' />
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.print.css' media='print' />
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui-1.9.2.custom.min.css'/>
    </head>
    <body>
        <!-- Main Container Fluid -->
        <div class="container-fluid fluid menu-left">

            <!-- Top navbar -->
            <div class="navbar main hidden-print">

                <!-- Brand -->
                <a href="<?php echo Yii::app()->homeUrl; ?>" class="appbrand pull-left"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_logo.png" style="float:left;padding: 8px 0 0 0;height: 27px;" /><span><span>Ano Atual: <?php echo Yii::app()->user->year; ?></span></span></a>

                <!-- Menu Toggle Button -->
                <button id="button-menu" type="button" class="btn btn-navbar hidden-desktop">
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>

                <!-- Top Menu Right -->
                <ul class="topnav pull-right">
                    <li>
                        <div id="change-school" >
                            <form class="school" action="<?php echo yii::app()->createUrl('site/changeschool') ?>" method="Post">
                                <?php
                                if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
                                    echo CHtml::activeDropDownList(
                                            SchoolIdentification::model(), 'inep_id', Chtml::listData(Yii::app()->user->usersSchools, 'inep_id', 'name'), array('empty' => 'Selecione a escola', 'class' => 'span5 select-school', 'options' => array(Yii::app()->user->school => array('selected' => true))));
                                } else {
                                    echo CHtml::activeDropDownList(
                                            UsersSchool::model(), 'school_fk', Chtml::listData(Yii::app()->user->usersSchools, 'school_fk', 'schoolFk.name'), array('empty' => 'Selecione a escola', 'class' => 'span5 select-school', 'options' => array(Yii::app()->user->school => array('selected' => true))));
                                }
                                ?>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- Top navbar END -->

            <!-- Sidebar menu & content wrapper -->
            <div id="wrapper">
                <!-- Sidebar menu -->
                <div id="menu" class="hidden-print">
                    <div class="slim-scroll" data-scroll-height="800px">
                        <ul>
                            <li id="menu-school" class="<?= $currentPage == "school" ? 'active' : ''?>">
                                <?php
                                $schoolurl = yii::app()->createUrl('school');
                                if (count(Yii::app()->user->usersSchools) == 1) {
                                    $schoolurl = yii::app()->createUrl('school/update', array('id' => yii::app()->user->school));
                                }
                                ?>
                                <a class="glyphicons building" href="<?php echo $schoolurl ?>"><i></i><span>Escola</span></a>
                            </li>
                            <li id="menu-classroom" class="<?= $currentPage == "classroom" ? 'active' : ''?>">
                                <a class="glyphicons adress_book" href="<?php echo yii::app()->createUrl('classroom') ?>"><i></i><span>Turmas</span></a>
                            </li>
                            <!--<li id="menu-student" class="hasSubmenu">
                                <a data-toggle="collapse" class="glyphicons parents" href="#menu_alunos"><i></i><span>Alunos</span></a>
                                <ul class="collapse" id="menu_alunos">
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=student/create"><span>Aluno Novo</span></a></li>
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=student"><span>Lista de Alunos</span></a></li>
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=student"><span>Alunos PNE</span></a></li>
                                </ul>
                                <?php //<span class="count">2</span>  ?>
                            </li>-->
                            <li id="menu-student" class="<?= $currentPage == "student" ? 'active' : ''?>">
                                <a  class="glyphicons parents" href="<?php echo yii::app()->createUrl('student') ?>"><i></i><span>Alunos</span></a>
                            </li>
                            <li id="menu-instructor" class="<?= $currentPage == "instructor" ? 'active' : ''?>">
                                <a class="glyphicons nameplate" href="<?php echo yii::app()->createUrl('instructor') ?>"><i></i><span>Professores</span></a>
                            </li>
                            <li id="menu-classes" class="<?= $currentPage == "frequency" ? 'active' : ''?>">
                                <a class="glyphicons check" href="<?php echo yii::app()->createUrl('classes/frequency') ?>"><i></i><span>Frequência</span></a>
                            </li>
                            <li id="menu-classes" class="<?= $currentPage == "classObjectives" ? 'active' : ''?>">
                                <a class="glyphicons book_open" href="<?php echo yii::app()->createUrl('classes/classObjectives') ?>"><i></i><span>Plano de aula</span></a>
                            </li>
                            <li id="menu-grade">
                                <a class="glyphicons blog" style="opacity:0.5" href="#"><i></i><span>Censo Escolar</span></a>
                                <!-- <?php echo yii::app()->createUrl('grade') ?> -->
                            </li>
                             <li id="menu-grade" class="<?= $currentPage == "grades" ? 'active' : ''?>">
                                <a class="glyphicons blog" href="<?php echo yii::app()->createUrl('enrollment/grades') ?> "><i></i><span>Notas</span></a>
                                
                            </li>
                            <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
                                <li id="menu-admin" class="<?= $currentPage == "admin" ? 'active' : ''?>">
                                    <a class="glyphicons lock" href="<?php echo yii::app()->createUrl('admin') ?>"><i></i><span>Administração</span></a>
                                </li>
                            <?php } ?>
                            <li id="menu-logout">
                                <a class="glyphicons unshare" href="<?php echo yii::app()->createUrl('site/logout') ?>"><i></i><span>Sair</span></a>
                            </li>
                        </ul>
                    </div>
                    <!-- // Scrollable Menu wrapper with Maximum Height END -->
                    <div class="copy" style="width: 170px !IMPORTANT;">
                        <div style="float: left" id="apoio">Apoio:</div>
                    </div>
                </div>

                <!-- // Sidebar Menu END -->

                <!-- Content -->
                <div id="content">
                    <ul class="breadcrumb hidden-print">
                        <li class="breadcrumb-prev">
                            <a onclick="history.go(-1);" class="glyphicons circle_arrow_left"><i></i>Voltar</a>
                        </li>
                    </ul>
                    <?php echo $content; ?>
                </div>
                <!-- // Content END -->

            </div>
            <div class="clearfix"></div>
            <!-- // Sidebar menu & content wrapper END -->
        </div>
        
        <!-- // Main Container Fluid END -->      
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mask.min.js" ></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js" ></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/uniform.js" ></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.qrcode.min.js" type="text/javascript"></script>
        <script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.min.js'></script>

        <script>
            $(document).ready(function () {
                $(".select-search-off").select2({width: 'resolve', minimumResultsForSearch: -1});
                $(".select-search-on").select2({width: 'resolve'});
                $(".select-schools, .select-ComplementaryAT, .select-schools").select2({width: 'resolve', maximumSelectionSize: 6});
                $(".select-disciplines").select2({width: 'resolve', maximumSelectionSize: 13});
                $(".select-school").select2({dropdownCssClass: 'school-dropdown'});
                $('button[type=submit]').on('click',function(){});
            });

            /**
             * Select2 Brazilian Portuguese translation
             */

            (function ($) {
                "use strict";

                $.extend($.fn.select2.defaults, {
                    formatNoMatches: function () {
                        return "Nenhum resultado encontrado";
                    },
                    formatInputTooShort: function (input, min) {
                        var n = min - input.length;
                        return "Informe " + n + " caractere" + (n === 1 ? "" : "s");
                    },
                    formatInputTooLong: function (input, max) {
                        var n = input.length - max;
                        return "Apague " + n + " caractere" + (n === 1 ? "" : "s");
                    },
                    formatSelectionTooBig: function (limit) {
                        return "Só é possível selecionar " + limit + " elemento" + (limit === 1 ? "" : "s");
                    },
                    formatLoadMore: function (pageNumber) {
                        return "Carregando mais resultados…";
                    },
                    formatSearching: function () {
                        return "Buscando…";
                    }
                });
            })(jQuery);

            $(function () {
                $("#UsersSchool_school_fk, #SchoolIdentification_inep_id").change(function () {
                    $(".school").submit();
                });
            });

            var isOpen = true;
            $(document).on('click', '#button-menu', function () {
                if (isOpen) {
                    $('#content').css('margin', '0');
                } else {
                    $('#content').css('margin', '0 0 0 191px');
                }
                isOpen = !isOpen;

            });

            //Ao clicar ENTER não fará nada.
            $('*').keypress(function (e) {
                if (e.keyCode === $.ui.keyCode.ENTER) {
                    e.preventDefault();
                }
            });

        </script>
    </body>
</html>
