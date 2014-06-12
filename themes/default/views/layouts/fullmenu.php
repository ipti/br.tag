<?php
$cs = Yii::app()->clientScript;
$cs->scriptMap = array(
    'jquery.js' => false,
    'jquery.ba-bbq.js' => false
);
$baseUrl = Yii::app()->theme->baseUrl;
$cs->registerScriptFile($baseUrl . '/js/jquery.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile($baseUrl . '/js/jquery-ba-bbq.js', CClientScript::POS_HEAD);
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

        <!-- JQueryUI -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui-1.9.2.custom.min.js"></script>

        <!-- Bootstrap -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/responsive.min.css" rel="stylesheet" type="text/css" />
        <!--<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-select.css" rel="stylesheet" />-->

        <!-- Main Theme Stylesheet :: CSS -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/template.css" rel="stylesheet" type="text/css" />

        <!-- Glyphicons Font Icons -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/glyphicons.min.css" rel="stylesheet" type="text/css" />

        <!-- Select2 Plugin -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/select2.css" rel="stylesheet" />

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mask.min.js" ></script>

        <!-- Bootstrap -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js"></script>

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js" ></script>

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/uniform.js" ></script>

        <!-- Select2 Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2.js"></script>

        <!-- Calendar -->
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.css' />
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.print.css' media='print' />
        <script type='text/javascript' src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/fullcalendar/fullcalendar.min.js'></script>

        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui-1.9.2.custom.min.css'/>

        <script>
//            var dirty = false;  
            $(document).ready(function(){
                $(".select-search-off").select2({width: 'resolve',minimumResultsForSearch: -1}); 
                $(".select-search-on").select2({width: 'resolve'}); 
                $(".select-schools, .select-ComplementaryAT, .select-schools").select2({width: 'resolve', maximumSelectionSize: 6}); 
                $(".select-disciplines").select2({width: 'resolve', maximumSelectionSize: 13});
                $(".select-school").select2({dropdownCssClass: 'school-dropdown'});
//     
//                $('a').click(function(event){
//                    if(dirty){
//                        var con = confirm('Existem alterações para serem salvas... \nDeseja sair assim mesmo?');
//                        if(!con){
//                            event.preventDefault();
//                        }
//                    }
//                });
//                $('input, select').change(function(e){
//                    dirty = true; 
//                });
            });

            /**
             * Select2 Brazilian Portuguese translation
             */
            
            (function ($) {
                "use strict";

                $.extend($.fn.select2.defaults, {
                    formatNoMatches: function () { return "Nenhum resultado encontrado"; },
                    formatInputTooShort: function (input, min) { var n = min - input.length; return "Informe " + n + " caractere" + (n == 1? "" : "s"); },
                    formatInputTooLong: function (input, max) { var n = input.length - max; return "Apague " + n + " caractere" + (n == 1? "" : "s"); },
                    formatSelectionTooBig: function (limit) { return "Só é possível selecionar " + limit + " elemento" + (limit == 1 ? "" : "s"); },
                    formatLoadMore: function (pageNumber) { return "Carregando mais resultados…"; },
                    formatSearching: function () { return "Buscando…"; }
                });
            })(jQuery);
            
            $(function() {
                $("#UsersSchool_school_fk, #SchoolIdentification_inep_id").change(function() {
                    $(".school").submit();
                });
              });     
              
            var bagaca = true;
            $(document).on('click','#button-menu', function(){
                if(bagaca){
                    $('#content').css('margin','0');
                }else{
                    $('#content').css('margin','0 0 0 191px');
                }
                bagaca = !bagaca;
                    
            });
            
            //Ao clicar ENTER não fará nada.
            $('*').keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                    e.preventDefault();
                }
            });
            
               
        </script>

    </head>
    <body>

        <!-- Main Container Fluid -->
        <div class="container-fluid fluid menu-left">

            <!-- Top navbar -->
            <div class="navbar main hidden-print">

                <!-- Brand -->
                <?php //@done s1 - Url do logotipo redirecionar para página inicial ?>
                <a href="<?php echo Yii::app()->homeUrl; ?>" class="appbrand pull-left"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_logo.png" style="float:left;padding: 8px 0 0 0;height: 27px;" /><span><span>v3.0</span></span></a>

                <!-- Menu Toggle Button -->
                <button id="button-menu" type="button" class="btn btn-navbar hidden-desktop">
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>

                <!-- Top Menu Right -->
                <ul class="topnav pull-right">
                    <li>
                        <div id="change-school" >
                            <form class="school" action="?r=site/changeschool" method="Post">
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
                    <!-- Profile / Logout menu -->
                    <li class="account">
                        <a href="<?php echo Yii::app()->homeUrl; ?>?r=site/logout" class="glyphicons logout share"><span class="hidden-phone text">Sair</span><i></i></a>
                    </li>
                </ul>
            </div>
            <!-- Top navbar END -->

            <!-- Sidebar menu & content wrapper -->
            <div id="wrapper">

                <!-- Sidebar Menu -->

                <div id="menu" class="hidden-print">

                    <!-- Scrollable menu wrapper with Maximum height -->
                    <div class="slim-scroll" data-scroll-height="800px">

                        <!-- Sidebar Mini Stats -->
                        <div id="notif">
                            <div class="user">
                                <strong>
                                    <?php
                                    if (isset(Yii::app()->user->loginInfos->name))
                                        echo Yii::app()->user->loginInfos->name;
                                    else
                                        $this->redirect(Yii::app()->homeUrl . '?r=site/login');
                                    ?>
                                </strong>
                                <p>
                                    <?php
                                    $userId = Yii::app()->user->loginInfos->id;
                                    foreach (Yii::app()->getAuthManager()->getAuthItems(2, $userId) as $role => $roleOb)
                                        echo Yii::t('default', $role) . " ";
                                    ?>
                                </p>
                                <strong>Ano letivo:</strong> <?php echo Yii::app()->user->year; ?>
                            </div>

                            <div class="separator"></div>


                        </div>
                        <!-- // Sidebar Mini Stats END --> 

                        <!-- Regular Size Menu -->
                        <ul>
                            <!-- Menu Regular Item -->
                            <li class="glyphicons display"><a href="<?php echo Yii::app()->homeUrl; ?>"><i></i><span>Página inicial</span></a></li>
                            
                                <!-- Menu Item Escolas -->
                                <li class="hasSubmenu">
                                    <a data-toggle="collapse" class="glyphicons building" href="#menu_escolas"><i></i><span>Escolas</span></a>
                                    <ul class="collapse" id="menu_escolas">
                                        <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
                                        <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=school/create"><span>Adicionar escola</span></a></li>
                                        <?php } ?>
                                        <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=school"><span>Listar escolas</span></a></li>
                                    </ul>
                                </li>
                                <!-- // Menu Item Escolas -->
                            <!-- Menu Item Turmas -->
                            <li class="hasSubmenu">
                                <a data-toggle="collapse" class="glyphicons adress_book" href="#menu_turmas"><i></i><span>Turmas</span></a>
                                <ul class="collapse" id="menu_turmas">
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=classroom/create"><span>Adicionar turma</span></a></li>
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=classroom"><span>Listar turmas</span></a></li>
                                </ul>
                            </li>
                            <!-- // Menu Item Turmas -->

                            <!-- Menu Item Turmas -->
                            <li class="hasSubmenu">
                                <a data-toggle="collapse" class="glyphicons notes_2" href="#menu_matricula"><i></i><span>Matricula</span></a>
                                <ul class="collapse" id="menu_matricula">
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=enrollment/create"><span>Matricular aluno</span></a></li>
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=enrollment"><span>Listar matrículas</span></a></li>
                                </ul>
                            </li>
                            <!-- // Menu Item Turmas -->

                            <!-- Menu Item Alunos -->
                            <li class="hasSubmenu">
                                <a data-toggle="collapse" class="glyphicons parents" href="#menu_alunos"><i></i><span>Alunos</span></a>
                                <ul class="collapse" id="menu_alunos">
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=student/create"><span>Adicionar aluno</span></a></li>
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=student"><span>Listar alunos</span></a></li>
                                </ul>
                            </li>
                            <!-- // Menu Item Alunos -->

                            <!-- Menu Item Equipe Escolar -->
                            <li class="hasSubmenu">
                                <a data-toggle="collapse" class="glyphicons nameplate" href="#menu_equipe"><i></i><span>Professores</span></a>
                                <ul class="collapse" id="menu_equipe">
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=instructor/create"><span>Adicionar professor</span></a></li>
                                    <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=instructor#"><span>Listar professores</span></a></li>
                                </ul>
                            </li>
                            <!-- // Menu Item Equipe Escolar -->

                            <!-- Menu Item Frequência -->
                            <li class="hasSubmenu">
                                <a class="glyphicons check" href="<?php echo Yii::app()->homeUrl; ?>?r=frequency/index"><i></i><span>Frequência</span></a>
                            </li>
                            
                            <!-- Menu Item Relatórios -->
                            <li class="hasSubmenu">
                                <a class="glyphicons charts" href="<?php echo Yii::app()->homeUrl; ?>?r=reports/index"><i></i><span>Relatórios</span></a>
                            </li>
                            <!-- // Menu Item Relatórios -->
                            
                            <!-- Menu Item Admin -->
                            <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) { ?>
                                <li class="glyphicons lock"><a href="<?php echo Yii::app()->homeUrl; ?>?r=admin"><i></i><span>Administração</span></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- // Scrollable Menu wrapper with Maximum Height END -->

                    <div class="copy">TAG v3.0<br>Desenvolvido pelo <a href="http://ipti.org.br" target="_blank">IPTI<a/>.</div>
                </div>

                <!-- // Sidebar Menu END -->

                <!-- Content -->
                <div id="content">
                    <!-- Breadcrumb -->
                    <?php
                    if (isset($this->breadcrumbs)):
                        $newBread = array();
                    
                        if (Yii::app()->controller->route !== 'site/index')
                            $this->breadcrumbs = array_merge(array(Yii::t('default', '') => Yii::app()->homeUrl), $this->breadcrumbs);

                        $this->widget('zii.widgets.CBreadcrumbs', array(
                            'links' => $this->breadcrumbs,
                            'homeLink' => '</a>
                                <li class="breadcrumb-prev">
                                    <a onclick="history.go(-1);" class="glyphicons circle_arrow_left"><i></i>Voltar</a>
                                </li>
                                <li>Você está aqui:</li>
                                <li>
                                    <a href="index.php"> &nbsp;Página Inicial</a>
                                </li>',
                            'tagName' => 'ul',
                            'separator' => '',
                            'activeLinkTemplate' => '<li><a href="{url}">{label}</a> <li class="divider"></li></li>',
                            'inactiveLinkTemplate' => '<li><span>{label}</span></li>',
                            'htmlOptions' => array('class' => 'breadcrumb hidden-print')
                        ));
                    endif;
                    ?>
                    <!-- Breadcrumb END -->
                    <?php echo $content; ?>
                </div>
                <!-- // Content END -->
            </div>
            <div class="clearfix"></div>
            <!-- // Sidebar menu & content wrapper END -->
        </div>
        <!-- // Main Container Fluid END -->
    </body>
</html>
