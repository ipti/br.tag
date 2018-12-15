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
    <!-- Meta -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

    <!-- Bootstrap -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/css/bootstrap.css" rel="stylesheet" />

    <!-- Glyphicons Font Icons -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/fonts/glyphicons/css/glyphicons_social.css" rel="stylesheet" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/fonts/glyphicons/css/glyphicons_filetypes.css" rel="stylesheet" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/fonts/glyphicons/css/glyphicons_regular.css" rel="stylesheet" />

    <!-- Font Awsome Icons -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" />

    <!-- Uniform Pretty Checkboxes -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css" rel="stylesheet" />

    <!-- PrettyPhoto -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/gallery/prettyphoto/css/prettyPhoto.css" rel="stylesheet" />

    <!--[if IE]><!--><script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/other/excanvas/excanvas.js"></script><!--<![endif]-->
    <!--[if lt IE 8]><script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/other/json2.js"></script><![endif]-->

    <!-- Bootstrap Extended -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-select/bootstrap-select.css" rel="stylesheet" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-switch/static/stylesheets/bootstrap-switch.css" rel="stylesheet" />

    <!-- Select2 Plugin -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/forms/select2/select2.css" rel="stylesheet" />

    <!-- DateTimePicker Plugin -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/forms/bootstrap-datetimepicker/css/datetimepicker.css" rel="stylesheet" />

    <!-- JQueryUI -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />

    <!-- MiniColors ColorPicker Plugin -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/color/jquery-miniColors/jquery.miniColors.css" rel="stylesheet" />

    <!-- Notyfy Notifications Plugin -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.css" rel="stylesheet" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/notifications/notyfy/themes/default.css" rel="stylesheet" />

    <!-- Gritter Notifications Plugin -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

    <!-- Easy-pie Plugin -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.css" rel="stylesheet" />

    <!-- Google Code Prettify Plugin -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/other/google-code-prettify/prettify.css" rel="stylesheet" />

    <!-- Bootstrap Image Gallery -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-image-gallery/css/bootstrap-image-gallery.min.css" rel="stylesheet" />

    <!-- Main Theme Stylesheet :: CSS -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/css/style-light.css" rel="stylesheet" />
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/skins/css/blue-gray.min.css" rel="stylesheet" />

    <!-- NEWCSS -->
        <!-- Bootstrap -->



    </head>
    <body>
        <!-- Main Container Fluid -->
        <div class="container-fluid fluid menu-hidden">

            <!-- Top navbar -->
            <div class="navbar main hidden-print">

                <!-- Brand -->
                <a href="<?php echo Yii::app()->homeUrl; ?>" class="appbrand pull-left">
                    <span id="schoolyear"><?php echo Yii::app()->user->year; ?></span>
                </a>

                <!-- Menu Toggle Button -->
                <ul class="topnav pull-left">

                    <li id="menu-dashboard" class="<?= isActive( "site" )?>">
                        <a class="glyphicons home" href="/"><i></i>&nbsp;</a>
                    </li>

                    <li class="dropdown visible-abc">
                        <a href="" data-toggle="dropdown" class="glyphicons building <?= isActive( "school" )?>"><i></i>Escola <span class="caret"></span></a>
                        <ul class="dropdown-menu pull-left">
                            <li><a class="glyphicons calendar" href="<?php echo yii::app()->createUrl('calendar') ?>"><i></i>Calendário</a></li>
                            <li><a class="glyphicons table" href="<?php echo yii::app()->createUrl('timesheet') ?>"><i></i>Quadro de Horário</a></li>
                            <li><a class="glyphicons stats" href="<?php echo yii::app()->createUrl('curricularmatrix') ?>"><i></i>Matriz Curricular</a></li>
                            <li>
                                <a class="glyphicons cutlery" href="<?php echo yii::app()->createUrl('lunch/lunch') ?> "><i></i><span>Merenda Escolar</span></a>
                            </li>
                            <li>
                                <a class="glyphicons refresh" href="<?php echo yii::app()->createUrl('censo/validate') ?> "><i></i><span>Educacenso</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown visible-abc">
                        <a href="" data-toggle="dropdown" class="glyphicons adress_book <?= isActive( "classroom" )?>"><i></i>Turma<span class="caret"></span></a>
                        <ul class="dropdown-menu pull-left">
                            <li><a href="<?php echo yii::app()->createUrl('classroom') ?>">Buscar Turmas</a></li>
                            <li><a href="">Nova Turma</a></li>
                            <li><a href="">Reaproveitar Turmas</a></li>
                            <li class="<?= isActive("frequency")?>">
                                <a class="glyphicons check" href="<?php echo yii::app()->createUrl('classes/frequency') ?>"><i></i><span>Frequência</span></a>
                            </li>
                            <li class="<?= isActive("grades") ?>">
                                <a class="glyphicons list" href="<?php echo yii::app()->createUrl('enrollment/grades') ?> "><i></i><span>Notas</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown visible-abc">
                        <a href="" data-toggle="dropdown" class="glyphicons parents <?= isActive( "student" )?>"><i></i>Aluno<span class="caret"></span></a>
                        <ul class="dropdown-menu pull-left">
                            <li><a href="<?php echo yii::app()->createUrl('student') ?>">Novo Aluno</a></li>
                            <li><a href="">Nova Turma</a></li>
                            <li><a href="">Reaproveitar Turmas</a></li>
                        </ul>
                    </li>
                    <li class="dropdown visible-abc">
                        <a href="" data-toggle="dropdown" class="glyphicons nameplate <?= isActive( "instructor" )?>"><i></i>Professor<span class="caret"></span></a>
                        <ul class="dropdown-menu pull-left">
                            <li><a href="<?php echo yii::app()->createUrl('student') ?>">Novo Professor</a></li>
                            <li class="<?= isActive("courseplan") ?>">
                                <a class="glyphicons book_open" href="<?php echo yii::app()->createUrl('courseplan') ?>"><i></i><span>Plano de aula</span></a>
                            </li>
                            <li class="<?= isActive("classContents") ?>">
                                <a class="glyphicons notes_2" href="<?php echo yii::app()->createUrl('classes/classContents') ?>"><i></i><span>Aulas ministradas</span></a>
                            </li>
                        </ul>
                    </li>
                    <li id="menu-reports" class="<?= isActive("reports") ?>">
                        <a  class="glyphicons signal" href="<?php echo yii::app()->createUrl('reports') ?>"><i></i><span>Relatórios</span></a>
                    </li>

                </ul>
                <!-- Top Menu Right -->
                <ul class="topnav pull-right">
                    <li>
                        <div id="change-school" >
                            <form class="school" id2="school" action="<?php echo yii::app()->createUrl('site/changeschool') ?>" method="Post">
                                <?php
                                if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
                                    echo CHtml::activeDropDownList(
                                        SchoolIdentification::model(), 'inep_id', Chtml::listData(Yii::app()->user->usersSchools, 'inep_id', 'name'), array('empty' => 'Selecione a escola', 'class' => 'span5 select-school', 'id2'=>'school', 'options' => array(Yii::app()->user->school => array('selected' => true))));
                                } else {
                                    echo CHtml::activeDropDownList(
                                        UsersSchool::model(), 'school_fk', Chtml::listData(Yii::app()->user->usersSchools, 'school_fk', 'schoolFk.name'), array('empty' => 'Selecione a escola', 'class' => 'span5 select-school', 'id2'=>'school', 'options' => array(Yii::app()->user->school => array('selected' => true))));
                                }
                                ?>
                            </form>
                        </div>
                    </li>
                    <li id="menu-logout">
                        <a class="glyphicons unshare" href="<?php echo yii::app()->createUrl('site/logout') ?>"><i></i></a>
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
                            <li id="menu-logout">
                                <a class="glyphicons unshare" href="<?php echo yii::app()->createUrl('site/logout') ?>"><i></i><span>Sair</span></a>
                            </li>
                            <li id="menu-dashboard" class="<?= isActive( "site" )?>">
                                <a class="glyphicons home" href="/"><i></i><span>Página Inicial</span></a>
                            </li>
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
                            <li id="menu-student" class="<?= isActive("reports") ?>">
                                <a  class="glyphicons signal" href="<?php echo yii::app()->createUrl('reports') ?>"><i></i><span>Relatórios</span></a>
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
                            <li id="menu-plans" class="<?= isActive("courseplan") ?>">
                                <a class="glyphicons book_open" href="<?php echo yii::app()->createUrl('courseplan') ?>"><i></i><span>Plano de aula</span></a>
                            </li>
                            <li id="menu-contents" class="<?= isActive("classContents") ?>">
                                <a class="glyphicons notes_2" href="<?php echo yii::app()->createUrl('classes/classContents') ?>"><i></i><span>Aulas ministradas</span></a>
                            </li>
                            <li id="menu-classes" class="<?= isActive("frequency")?>">
                                <a class="glyphicons check" href="<?php echo yii::app()->createUrl('classes/frequency') ?>"><i></i><span>Frequência</span></a>
                            </li>
                            <li id="menu-grade" class="<?= isActive("grades") ?>">
                                <a class="glyphicons list" href="<?php echo yii::app()->createUrl('enrollment/grades') ?> "><i></i><span>Notas</span></a>
                            </li>
                            <li id="menu-lunch" class="<?= isActive("lunch") ?>">
                                <a class="glyphicons cutlery" href="<?php echo yii::app()->createUrl('lunch/lunch') ?> "><i></i><span>Merenda Escolar</span></a>
                            </li>
                            <li id="menu-censo" class="<?= isActive("validate") ?>">
                                <a class="glyphicons refresh" href="<?php echo yii::app()->createUrl('censo/validate') ?> "><i></i><span>Educacenso</span></a>
                            </li>
                            <li id="menu-calendar" class="<?= isActive("calendar") ?>">
                                <a class="glyphicons calendar" href="<?php echo yii::app()->createUrl('calendar') ?> "><i></i><span>Calendário Escolar</span></a>
                            </li>
                            <li id="menu-timesheet" class="<?= isActive("timesheet") ?>">
                                <a class="glyphicons table" href="<?php echo yii::app()->createUrl('timesheet') ?> "><i></i><span>Quadro de Horário</span></a>
                            </li>
                            <li id="menu-matrix" class="<?= isActive("curricularmatrix") ?>">
                                <a class="glyphicons stats" href="<?php echo yii::app()->createUrl('curricularmatrix') ?> "><i></i><span>Matriz Curricular</span></a>
                            </li>
                            <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
                                <li id="menu-admin" class="<?= isActive("admin") ?>">
                                    <a class="glyphicons lock" href="<?php echo yii::app()->createUrl('admin') ?>"><i></i><span>Administração</span></a>
                                </li>
                                <li id="menu-logout">
                                    <a class="glyphicons notes" href="<?php echo yii::app()->createUrl('resultsmanagement') ?>"><i></i><span>Gestão por Resultados</span></a>
                                </li>
                            <?php } ?>


                        </ul>
                    </div>
                  
                </div>

                <!-- // Sidebar Menu END -->

                <!-- Content -->
                <div id="content">
                    Ano Letivo: 2018
                    Usuário: Administrador
                    Alterar Senha
                    Online - Sincronizar dados
                    <?php echo $content; ?>
                </div>
                <!-- // Content END -->

            </div>
            <div class="clearfix"></div>
            <!-- // Sidebar menu & content wrapper END -->
        </div>



        <!-- JQuery -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/system/jquery.min.js"></script>

        <!-- JQueryUI -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

        <!-- JQueryUI Touch Punch -->
        <!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

        <!-- Modernizr -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/system/modernizr.js"></script>

        <!-- Bootstrap -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/js/bootstrap.min.js"></script>

        <!-- SlimScroll Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.min.js"></script>

        <!-- Common Demo Script -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/demo/common.js?1381491381"></script>

        <!-- Holder Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/other/holder/holder.js"></script>

        <!-- Uniform Forms Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

        <!-- PrettyPhoto -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>

        <!-- Global -->
        <script>
            var basePath = '<?php echo Yii::app()->theme->baseUrl; ?>/common/';
        </script>

        <!-- Bootstrap Extended -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-switch/static/js/bootstrap-switch.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/jasny-bootstrap/js/bootstrap-fileupload.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootbox.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>

        <!-- Google Code Prettify -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/other/google-code-prettify/prettify.js"></script>

        <!-- Gritter Notifications Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>

        <!-- Notyfy Notifications Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.js"></script>

        <!-- MiniColors Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/color/jquery-miniColors/jquery.miniColors.js"></script>

        <!-- DateTimePicker Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/forms/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

        <!-- Cookie Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/system/jquery.cookie.js"></script>

        <!-- Colors -->
        <script>
            var primaryColor = '#e25f39',
                dangerColor = '#bd362f',
                successColor = '#609450',
                warningColor = '#ab7a4b',
                inverseColor = '#45484d';
        </script>

        <!-- Themer -->
        <script>
            var themerPrimaryColor = primaryColor;
        </script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/demo/themer.js"></script>

        <!-- Twitter Feed -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/demo/twitter.js"></script>

        <!-- Easy-pie Plugin -->
        <!-- <script src="../../../../common/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.js"></script> -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/charts/easy-pie/jquery.easypiechart.js"></script>

        <!-- Sparkline Charts Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/charts/sparkline/jquery.sparkline.min.js"></script>

        <!-- Ba-Resize Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/plugins/other/jquery.ba-resize.js"></script>


        <!-- Optional Resizable Sidebars -->
        <!--[if gt IE 8]><!--><script src="<?php echo Yii::app()->theme->baseUrl; ?>/common/theme/scripts/demo/resizable.js?1381491381"></script><!--<![endif]-->

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js" ></script>

    </body>
</html>
