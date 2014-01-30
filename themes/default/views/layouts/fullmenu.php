<?php
$cs=Yii::app()->clientScript;
$cs->scriptMap=array(
    'jquery.js'=>false,
    'jquery.ba-bbq.js'=>false
);
$baseUrl = Yii::app()->theme->baseUrl; 
$cs->registerScriptFile($baseUrl.'/js/jquery.min.js',CClientScript::POS_HEAD);
$cs->registerScriptFile($baseUrl.'/js/jquery-ba-bbq.js',CClientScript::POS_HEAD);
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
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap-select.css" rel="stylesheet" />

        <!-- Main Theme Stylesheet :: CSS -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/template.min.css" rel="stylesheet" type="text/css" />
        
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/uniform.css" rel="stylesheet" type="text/css" />

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
        <script>
            $(document).ready(function(){
                $(".select-search-off").select2({width: 'resolve',minimumResultsForSearch: -1}); 
                $(".select-search-on").select2({width: 'resolve'}); 
                $("select[multiple]").select2({width: 'resolve', maximumSelectionSize: 6}); 
            });
        </script>
        
        <!-- Auto Submit Dropdown School -->
        <script>               
            $(function() {
                $("#UsersSchool_school_fk").change(function() {
                    $(".school").submit();
                });
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
                <a href="<?php echo Yii::app()->homeUrl; ?>" class="appbrand pull-left"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_logo.png" style="float:left;padding: 7px 5px 0 44px;height: 34px;" /><span><span>v3.0</span></span></a>

                <!-- Menu Toggle Button --
                <button type="button" class="btn btn-navbar">
                        <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <!-- // Menu Toggle Button END -->
                <div class="menu-search">
                    <div class="input-append">
                        <?php /** <input class="span4" id="appendedInputButtons" type="text" placeholder="O que você está procurando?">
                        <button class="btn btn-default" type="button"><i class="icon-search"></i></button> **/ ?>
                    </div>
                </div>


                <!-- Top Menu Right -->
                <ul class="topnav pull-right">



                    <!-- Profile / Logout menu -->
                    <li class="account">
                        <a href="<?php echo Yii::app()->homeUrl; ?>?r=site/logout" class="glyphicons logout share"><span class="hidden-phone text">Sair</span><i></i></a>

                    </li>
                </ul>

                <!-- // Profile / Logout menu END -->

                </ul>
                <!-- // Top Menu Right END -->


            </div>
            <!-- Top navbar END -->

            <!-- Sidebar menu & content wrapper -->
            <div id="wrapper">

                <!-- Sidebar Menu -->

                <div data-spy="affix" data-offset-top="45" data-offset-bottom="0">

                    <div id="menu" class="hidden-phone hidden-print">

                        <!-- Scrollable menu wrapper with Maximum height -->
                        <div class="slim-scroll" data-scroll-height="800px">

                        <!-- Sidebar Mini Stats -->
                            <div id="notif">
                                <div class="user">
                                    <strong><?php echo Yii::app()->user->loginInfos->name; ?></strong>
                                    <p><?php 
                                        $userId = Yii::app()->user->loginInfos->id;
                                        foreach (Yii::app()->getAuthManager()->getAuthItems(2,$userId) as $role => $roleOb)
                                                echo Yii::t('default',$role)." ";
                                             ?></p>
                                </div>

                                <form class="school" action="?r=site/changeschool" method="Post">
                                    
                                    <div class="row">
                                    
                                    <?php
                                    echo CHtml::activeDropDownList(
                                            UsersSchool::model(), 'school_fk',  
                                            Chtml::listData(Yii::app()->user->loginInfos->usersSchools, 'school_fk', 'schoolFk.name'),
                                            array('empty'=>'Selecione a escola','class'=>'span2 select-search-on','options' => array(Yii::app()->user->school=>array('selected'=>true))));
                                    ?>
<?php /**                           Botão de Submit Oculto no Dropdown de Seleção de Escolas                                                        
                                    <button type="hidden" class="btn btn-icon btn-primary glyphicons circle_ok"><i>Ok</i></button>
**/ ?>                          <div class="separator"></div>
                                    </div>
                                </form>

                            </div>
                            <!-- // Sidebar Mini Stats END --> 

                            <!-- Regular Size Menu -->
                            <ul>


                                <!-- Menu Regular Item -->
                                <li class="glyphicons display"><a href="<?php echo Yii::app()->homeUrl; ?>"><i></i><span>Página inicial</span></a></li>

                                <?php if(Yii::app()->getAuthManager()->checkAccess('createSchool',Yii::app()->user->loginInfos->id)){ ?>
                                <!-- Menu Item Escolas -->
                                <li class="hasSubmenu">
                                    <a data-toggle="collapse" class="glyphicons building" href="#menu_escolas"><i></i><span>Escolas</span></a>
                                    <ul class="collapse" id="menu_escolas">
                                        <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=school/create"><span>Adicionar escola</span></a></li>
                                        <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=school"><span>Listar escolas</span></a></li>
                                    </ul>
                                    <?php //<span class="count">2</span> ?>
                                </li>
                                <!-- // Menu Item Escolas -->
                                <?php } ?>
                                <!-- Menu Item Turmas -->
                                <li class="hasSubmenu">
                                    <a data-toggle="collapse" class="glyphicons adress_book" href="#menu_turmas"><i></i><span>Turmas</span></a>
                                    <ul class="collapse" id="menu_turmas">
                                        <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=classroom/create"><span>Adicionar turma</span></a></li>
                                        <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=classroom"><span>Listar turmas</span></a></li>
                                        <?php //<li class=""><a href="#"><span>Frequencia de alunos</span></a></li> ?>
                                        <?php //<li class=""><a href="#"><span>Notas de alunos</span></a></li> ?>
                                    </ul>
                                    <?php //<span class="count">5</span> ?>
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
                                    <?php //<span class="count">2</span> ?>
                                </li>
                                <!-- // Menu Item Alunos -->

                                <!-- Menu Item Equipe Escolar -->
                                <li class="hasSubmenu">
                                    <a data-toggle="collapse" class="glyphicons nameplate" href="#menu_equipe"><i></i><span>Professores</span></a>
                                    <ul class="collapse" id="menu_equipe">
                                        <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=instructor/create"><span>Adicionar professor</span></a></li>
                                        <li class=""><a href="<?php echo Yii::app()->homeUrl; ?>?r=instructor#"><span>Listar professores</span></a></li>
                                    </ul>
                                    <?php //<span class="count">2</span> ?>
                                </li>
                                <!-- // Menu Item Equipe Escolar -->

                  <?php /**             <!-- Menu Item Relatórios -->
                                <li class="hasSubmenu">
                                    <a data-toggle="collapse" class="glyphicons charts" href="#menu_relatorios"><i></i><span>Relatórios</span></a>
                                    <ul class="collapse" id="menu_relatorios">
                                        <li class=""><a href="#"><span>Notas e frequência</span></a></li>
                                        <li class=""><a href="#"><span>Quantidade de alunos</span></a></li>
                                        <li class=""><a href="#"><span>Alunos com anemia</span></a></li>
                                    </ul>
                                    <span class="count">3</span>
                                </li>
                                <!-- // Menu Item Relatórios -->

                            </ul>

                  **/ ?>

                        </div>
                        <!-- // Scrollable Menu wrapper with Maximum Height END -->

                    </div>

                </div>

                <!-- // Sidebar Menu END -->

                <!-- Content -->
                <div id="content">

<!--                     Breadcrumb -->
                    <?php
                    if (isset($this->breadcrumbs)):

                        $newBread = array();
                    
                        if (Yii::app()->controller->route !== 'site/index')
                            $this->breadcrumbs = array_merge(array(Yii::t('default', '') => Yii::app()->homeUrl),$this->breadcrumbs);
                        
                        $this->widget('zii.widgets.CBreadcrumbs', array(
                            'links' => $this->breadcrumbs,
                            'homeLink' => '<li><a href="index.php" class="glyphicons home"><i></i> Página Inicial</a></li>',
                            'tagName' => 'ul',
                            'separator' => '',
                            'activeLinkTemplate' => '<li><a href="{url}">{label}</a> <li class="divider"></li></li>',
                            'inactiveLinkTemplate' => '<li><span>{label}</span></li>',
                            'htmlOptions' => array('class' => 'breadcrumb')
                        ));
                        endif; 
                        ?>
<!--                     Breadcrumb END -->
                    
                    <div class="separator bottom"></div>

                    <?php echo $content; ?>

                    <!--  Copyright Line -->
                    <div class="copy">TAG v3.0 - GPL - Desenvolvido pelo <a href="http://ipti.org.br" target="_blank">IPTI<a/>.</div>
                    <!--  End Copyright Line -->

                </div>

                <!-- // Content END -->

            </div>
            <div class="clearfix"></div>
            <!-- // Sidebar menu & content wrapper END -->

        </div>
        <!-- // Main Container Fluid END -->

    </body>
</html>
