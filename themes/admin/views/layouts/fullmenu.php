<?php
$baseUrl = Yii::app()->theme->baseUrl;

function isActive($pages){
    $currentPage = Yii::app()->controller->id;
    $active = false;
    if (is_array($pages)) {
        foreach($pages as $page){
            $active = $active || ($currentPage == $page);
        }
    }else{
        $active = $currentPage == $pages;
    }
    return $active ? 'active' : '';
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ba-bbq.js"></script>
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
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.min.css' />

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


            </div>
            <!-- Top navbar END -->

            <!-- Sidebar menu & content wrapper -->
            <div id="wrapper">
                <!-- Sidebar menu -->
                <div id="menu" class="hidden-print">
                    <div class="slim-scroll" data-scroll-height="800px">
                        <ul>
<!--                            <li id="menu-dashboard" class="--><?//= isActive( "site" )?><!--">-->
<!--                                <a class="glyphicons dashboard" href="/"><i></i><span>Dashboard</span></a>-->
<!--                            </li>-->
                            <li id="menu-school" class="<?= isActive("school") ?>">
                                <?php
                                $schoolurl = yii::app()->createUrl('school');
                                if (count(Yii::app()->user->usersSchools) == 1) {
                                    $schoolurl = yii::app()->createUrl('school/update', array('id' => yii::app()->user->school));
                                }
                                ?>
                                <a class="glyphicons building" href="<?php echo $schoolurl ?>"><i></i><span>Escola</span></a>
                            </li>
                            <li id="menu-classroom" class="<?= isActive( "classroom" )?>">
                                <a class="glyphicons adress_book" href="<?php echo yii::app()->createUrl('classroom') ?>"><i></i><span>Turmas</span></a>
                            </li>
                            <li id="menu-student" class="<?= isActive("student") ?>">
                                <a  class="glyphicons parents" href="<?php echo yii::app()->createUrl('student') ?>"><i></i><span>Alunos</span></a>
                            </li>
                            <!--<li id="menu-student" class="hasSubmenu <?=isActive("classroom") ?>">
                                <a data-toggle="collapse" class="glyphicons adress_book" href="#menu-classroom2"><i></i><span>Turma</span></a>
                                <ul class="collapse" id="menu-classroom2">                                
                                    <a class="glyphicons adress_book" href="<?php echo yii::app()->createUrl('classroom') ?>"><i></i><span>Procurar Turmas</span></a>
                                    <a class="glyphicons book_open" href="<?php echo yii::app()->createUrl('courseplan') ?>"><i></i><span>Plano de aula</span></a>
                                    <a class="glyphicons notes_2" href="<?php echo yii::app()->createUrl('classes/classContents') ?>"><i></i><span>Aulas ministradas</span></a>
                                    <a class="glyphicons check" href="<?php echo yii::app()->createUrl('classes/frequency') ?>"><i></i><span>Frequência</span></a>
                                    <a class="glyphicons list" href="<?php echo yii::app()->createUrl('enrollment/grades') ?> "><i></i><span>Notas</span></a>
                                </ul>
                            </li>-->

                            <li id="menu-instructor" class="<?= isActive("instructor")?>">
                                <a class="glyphicons nameplate" href="<?php echo yii::app()->createUrl('instructor') ?>"><i></i><span>Professores</span></a>
                            </li>

                            <li id="menu-logout">
                                <a class="glyphicons unshare" href="<?php echo yii::app()->createUrl('site/logout') ?>"><i></i><span>Sair</span></a>
                            </li>
                        </ul>
                    </div>
                    <!-- // Scrollable Menu wrapper with Maximum Height END -->
                    <!--<div class="copy" style="width: 170px !IMPORTANT;">
                        <div style="float: left" id="apoio">Apoio:</div>
                    </div>-->
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
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mask.min.js" ></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js" ></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/uniform.js" ></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2-locale-pt-BR.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.qrcode.min.js" type="text/javascript"></script>
        <script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.min.js'></script>
    </body>
</html>
