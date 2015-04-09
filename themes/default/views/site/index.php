<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . '';
$this->breadcrumbs = array(
    '',
);

$year = Yii::app()->user->year;

?>


<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic">Bem-vindo ao TAG</h3>
    </div>
</div>

<div class="innerLR home">
    <div class="row-fluid">
        <div class="span11">

            <!--<div class="widget">
                <!-- Widget heading -->
                <div class="widget-head">
                    <h4 class="heading">Meta 1: Turmas Criadas</h4>
                </div>
                <!-- // Widget heading END -->
                <div class="widget-body">
                    <!-- Donut Chart -->
                    <div id="progressbar_class"></div>
                </div>
            </div>
            <div class="widget">
                <!-- Widget heading -->
                <div class="widget-head">
                    <h4 class="heading">Meta 2: Alunos Matrículados</h4>
                </div>
                <!-- // Widget heading END -->
                <div class="widget-body">
                    <!-- Donut Chart -->
                    <div id="progressbar_students"></div>
                </div>
            </div>-->
            <div class="row-fluid">
                <!--<div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('student/create') ?>"
                       class="widget-stats"> <span class="glyphicons user_add"><i></i></span>
                        <span class="txt">Adicionar aluno</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('sorcerer/configuration/student') ?>"
                       class="widget-stats"> <span class="glyphicons sort"><i></i></span>
                        <span class="txt">Matricular em lote</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('instructor/create') ?>"
                       class="widget-stats"> <span class="glyphicons nameplate"><i></i></span>
                        <span class="txt">Adicionar professor</span>
                        <div class="clearfix"></div>
                    </a>
                </div>
                <div class="span3">
                    <a href="<?php echo Yii::app()->createUrl('classroom/create') ?>"
                       class="widget-stats"> <span class="glyphicons adress_book"><i></i></span>
                        <span class="txt">Adicionar turma</span>
                        <div class="clearfix"></div>
                    </a>
                </div>-->
            </div>
            <!--            <div class="row-fluid">
    <div class="span10 offset2">
        <img class="logo-img" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_logo.png" alt="Logo TAG" />
    </div>
</div> -->
        </div>

        <div class="span5">
                <!-- <p>A Tecnologia de Apoio à Gestão (TAG) é uma plataforma
                        computacional que facilita a organização e o acompanhamento de
                        informações básicas das escolas do município de Santa Luzia do
                        Itanhi.</p>
                <p>Essa ferramenta foi concebida em parceria com os principais atores
                        locais com o objetivo de ser intuitiva e de agilizar o trabalho do
                        usuário.</p>
                <p>Nesta versão, você poderá executar operações como:</p>
                <ul>
                        <li>cadastro e matrícula de alunos;</li>
                        <li>consulta e edição dos dados da escola;</li>
                        <li>cadastro de turmas;</li>
                        <li>cadastro dos professores em sala de aula</li>
                </ul>
                <p class="good-work">
                        <b>Bom trabalho!</b>
                </p> -->

        </div>
    </div>
    <div>
        <script type="text/javascript">
            function init() {
                $("#progressbar_class").progressbar({"value": 30});
                $("#progressbar_class").bind('progressbarchange', function (event, ui) {
                    var selector = "#" + this.id + " > div";
                    var value = this.getAttribute("aria-valuenow");
                    if (value < 10) {
                        $(selector).css({'background': 'Red'});
                    } else if (value < 30) {
                        $(selector).css({'background': 'Orange'});
                    } else if (value < 50) {
                        $(selector).css({'background': 'Yellow'});
                    } else {
                        $(selector).css({'background': 'LightGreen'});
                    }
                });
            }
            $(document).ready(function(){
                init();
                $("#progressbar_class").trigger('progressbarchange');
            });

        </script>
    </div>
</div>
