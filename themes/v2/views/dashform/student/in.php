<?php
$cs = Yii::app()->clientScript;
$cs->registerScript('myjquery', "
$(document).ready(function(){
    introJs().start();
    drawmap();
    $('#censo-active').on('click',function(){
        introJs().addHints();
    });
    
    $.fn.editable.defaults.mode = 'inline';
    $('.editable a').editable({
        type: 'text',
        emptytext: 'Indeterminado',
        pk: 1,
        url: '/post',
        title: 'Enter username'
    });
})
");
$cs->registerScript('topoJson',"

");
$cs->registerCss('info-edit', '
.states :hover {
    fill: red;
  }
  
  .state-borders {
    fill: none;
    stroke: #fff;
    stroke-width: 0.5px;
    stroke-linejoin: round;
    stroke-linecap: round;
    pointer-events: none;
  }
        path {
            fill: #ccc;
            stroke: #fff;
            stroke-width: .5px;
        }
        
        path:hover {
            fill: red;
        }
     .info-edit
     {
       max-width: none;
       padding: unset;
     }
     .editable p,.info-edit i{color:#000}
     .editable a{
        margin:0em 0.3em;
        color:#000;
        font-weight:bold;
     }
     .editable .form-group{
         padding-bottom:unset;
         margin:unset;
     }
     #censo-active{font-size:unset}
');
$cs->registerCss('general', '
    .breadcrumb{background-color:unset;padding:0px;}
    .navbar{padding:0px;}
    @media (min-width:768px){.navbar-text{margin-left: -20px;}.navbar-header{width:130px}
    #backhistory{height:50px; line-height:50px}
     #censo-alert .col-md-1{width:12.33%}
     #censo-alert{padding-top:10px}
    }
    #backhistory i,#backhistory a{font-size:25px;color:#fff}
    .breadcrumb a{color:#fff}
    .introjs-hint-pulse{
        border-color:rgba(255, 141, 0, 0.27);
        background-color:rgba(240, 134, 2, 0.24);
    }
    .introjs-hint-dot{
        border-color:rgba(255, 120, 0, 0.36);
    }
    .nav-stacked>li{float:left}
    .nav-pills.nav-pills-info > li > a:focus, .nav-pills.nav-pills-info > li > a:hover {
        background-color: #00bcd4;
        color:#fff;
        box-shadow: 0 5px 20px 0px rgba(0, 0, 0, 0.2), 0 13px 24px -11px rgba(0, 188, 212, 0.6);
    }
   
    #style-1::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }

    #style-1::-webkit-scrollbar
    {
        width: 12px;
        background-color: #F5F5F5;
    }

    #style-1::-webkit-scrollbar-thumb
    {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #555;
    }
    .btn-simple{margin:0;padding:0}
    .wizard-card .tab-content,.wizard-card{min-height:unset}
');

?>
<script>
    //プロジェクション設定	
    function drawmap(){
        var w = 450;
        var h = 200;
        var geoPoint = {
        "type": "Point",
        "coordinates": [
            -105.01621,
            39.57422
        ]
        };

        var projection = d3
            .geoMercator() //投影法の指定
            .translate([w/2, h/2])
            .scale(60000)	//スケール（ズーム）の指定
            //.rotate([-0.25, 0.25, 0]) //地図を回転する　[x,y,z]
            .center([-37.50719669199343, -11.36121753275635]); //中心の座標を指定
        //パスジェネレーター生成
        var path = d3.geoPath().projection(projection);　
        
        //地図用のステージ(SVGタグ)を作成
        var map = d3.select("#map")
            .append("svg")
            .attr("width", w)
            .attr("height", h); 
        
        
        //地理データ読み込み
        d3.json("/themes/v2/common/maps/2806305.json", drawMaps);
        
        //地図を描画
        function drawMaps(geojson) {
            map.selectAll("path")
                .data(geojson.features)
                .enter()
                .append("path")
                .attr("d", path)  //パスジェネレーターを使ってd属性の値を生成している
                .attr("fill", "green")
                .attr("fill-opacity", 0.5)
                .attr("stroke", "#222");    
        }
        var aa = [-37.50719669199343, -11.36121753275635];
        var bb = [-37.5078, -11.368];
        map.selectAll("circle")
		.data([aa,bb]).enter()
		.append("circle")
		.attr("cx", function (d) { console.log(projection(d)); return projection(d)[0]; })
		.attr("cy", function (d) { return projection(d)[1]; })
		.attr("r", "3px")
		.attr("fill", "red");
        
    }
    

</script>
<div class="container-fluid">
    <nav class="navbar navbar-info">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a id="backhistory" href="" class="pull-left">
                        <i class="material-icons">arrow_back_ios</i>
                    </a>
                    
                    <a class="navbar-brand col-md-10" href="#">
                        <img class="col-md-12" alt="Brand" src="<?php echo Yii::app()->theme->baseUrl; ?>/common/img/tag-min-logo-outline-fill.svg">
                    </a>
                </div>
                <ol class="navbar-left breadcrumb navbar-text">
                        <li><a href="#">Dashform</a></li>
                        <li><a href="#">Escola</a></li>
                        <li class="active">Jose Carlos Feliz</li>
                </ol>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#pablo"><i class="material-icons">email</i><div class="ripple-container"></div></a>
                    </li>
                    <li>
                        <a href="#pablo"><i class="material-icons">face</i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#pablo" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">settings</i>
                            <b class="caret"></b>
                        <div class="ripple-container"></div></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-header">Dropdown header</li>
                            <li><a href="#pablo">Action</a></li>
                            <li><a href="#pablo">Another action</a></li>
                            <li><a href="#pablo">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#pablo">Separated link</a></li>
                            <li class="divider"></li>
                            <li><a href="#pablo">One more separated link</a></li>
                        </ul>
                    </li>
	            </ul>
            </div>
        </nav>

        <div class="row">
            <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h2 class="editable"> 
                                                <a data-toggle="tooltip" 
                                                data-placement="top" title="" data-container="body" 
                                                data-original-title="Tooltip on top" href="#" id="name">
                                                Abel Rosa Nicacio
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="col-md-2">
                                            <button id="censo-active" class="btn btn-warning btn-just-icon">
                                                <img src="<?php echo Yii::app()->theme->baseUrl.'/common/img/censo-logo-white.svg'?>">
                                                <p><small>&nbsp;Corrigir &nbsp;</small></p>
                                                <div class="ripple-container"></div>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="fileinput fileinput-new text-center center" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail img-raised">
                                                    <img src="<?php echo Yii::app()->theme->baseUrl.'/common/img/image_placeholder.jpg'?>" alt="...">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail img-circle img-raised"></div>
                                                <div>
                                                    <span class="btn btn-raised btn-round btn-default btn-file">
                                                        <span class="fileinput-new">Add Photo</span>
                                                        <span class="fileinput-exists">Change</span>
                                                        <input type="file" name="..."></span>
                                                    <br>
                                                    <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="map" class="col-md-9">
                                        </div>
                                    </div>
                                    
                                    <div class="info info-horizontal innerLR info-edit">
                                        <div class="icon icon-info">
                                            <i class="material-icons">school</i>
                                        </div>
                                        <div class="description editable">
                                            <h4 class="info-title">Perfil do Aluno</h4>
                                            <p>
                                                <p>
                                                    Nascido em <a href="#">26/08/2009</a> com a nacionalidade <a href="#">Brasileira</a>. Natural
                                                    de <a href="#">Estância</a>, <a href="#">Sergipe</a>. De gênero <a href="#">Masculino</a>
                                                    de cor/raça <a href="#">Parda</a>
                                                </p>
                                                <p>
                                                O Aluno <a href="#">Participa</a> do Bolsa Família e <a href="#">Não Possui</a> restrição alimentar
                                                </p>
                                                <p>
                                                <a href="#">Possui</a> deficiência. São elas <a href="#">Cegueira, Baixa Visão, Surdez</a>
                                                </p>
                                                <p>
                                                Necessita dos seguintes recursos em avaliações do INEP(Prova Brasil, SAEB):<a href="#">Auxilio Ledor, Auxilio transcrição</a>
                                                </p>
                                                <p>
                                                <a href="#">Possui (LA) </a>restrição na justiça.
                                                </p>
                                                <p>
                                                Mora a<a href="#">1 KM</a> da escola, na zona <a href="#">Rural</a>
                                                no CEP <a href="#">49090390</a> na Rua <a href="#">Pov Engenho Dagua</a>,
                                                Número: <a href="#">Indeterminado</a>, Bairro: <a href="#">Centro</a>,
                                                Cidade de <a href="#">Indiaroba</a>, Estado de <a href="#">Sergipe</a>
                                                </p>
                                        </div>
                                    </div>

                                    <div class="info info-horizontal innerLR info-edit">
                                        <div class="icon icon-info">
                                            <i class="material-icons">school</i>
                                        </div>
                                        <div class="description editable">
                                            <h4 class="info-title">Filiação</h4>
                                            <p>
                                                O tipo de filiação do aluno é <a href="#">Mãe e/ou Pai</a>
                                            </p>
                                            <p>
                                                A mãe chama-se <a href="#">Gerlani Rosa da Conceição</a>, possui
                                                RG sobe o número <a href="#"></a>, CPF de número <a href="#">842.785.605-91</a>.
                                                A escolaridade da mãe é <a href="#">Indeterminada</a>, possui a profissão de 
                                                <a href="#">Indeterminada</a>
                                                Esta turma tem aula com os seguintes professores/disciplinas: 
                                            </p>
                                            <p>
                                                O Pai chama-se <a href="#">Indeterminado</a>, possui
                                                RG sobe o número <a href="#"></a>, CPF de número <a href="#">842.785.605-91</a>.
                                                A escolaridade da mãe é <a href="#">Indeterminada</a>, possui a profissão de 
                                                <a href="#">Indeterminada</a>
                                                Esta turma tem aula com os seguintes professores/disciplinas: 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="info info-horizontal innerLR info-edit">
                                        <div class="icon icon-info">
                                            <i class="material-icons">school</i>
                                        </div>
                                        <div class="description editable">
                                            <h4 class="info-title">Identificação</h4>
                                            <p>
                                               O Aluno possui código do NIS de número: <a href="#">215459846</a>, ID do Inep: <a href="#">0255668549</a>,
                                               Cartão Nacional de Saúde: <a href="#">25549894569</a>, CPF: <a href="#">84565249579</a>.
                                            </p>
                                            <p>
                                               O seu documento de identidade está registrado sobe o número: <a href="#">24468479955</a>, 
                                               emitido pelo Orgão <a href="#">SSP/SE</a> no estado de <a href="#">Sergipe</a>
                                               na data de <a href="#">22/12/1987</a>
                                            </p>
                                            
                                            <p>
                                                A Certidão Civil do tipo <a href="#">Nascimento</a> está no modelo <a href="#">Antigo</a>, com
                                                número de termo <a href="#">12352</a>,Folha <a href="#">224</a>, Livro <a href="#">A50</a>,
                                                Data de emissão <a href="#">31/08/2009</a> registrado no Cartório <a href="#">CARTORIO DO 2º OFICIO DE ESTÂNCIA</a>, da 
                                                cidade de <a href="#">Estância</a> no estado de <a href="#">Sergipe</a>
                                            </p>
                                        </div>
                                    </div>





                                </div>
                        </div>    
                    </div>
            </div>
            <div class="col-md-6">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"> 
	                          <div class="panel panel-info col-md-6">
	                            <div class="panel-heading" role="tab" id="headingOne">
	                                <a role="button" aria-expanded="true">
	                                    <h4 class="panel-title text-info">
	                                    Ações Rápidas
	                                    </h4>
	                                </a>
	                            </div>
	                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" aria-expanded="true">
	                              <div class="panel-body">
                                            <ul class="nav nav-pills nav-pills-info nav-pills-icons nav-stacked" role="tablist">
                                                <!--
                                                    color-classes: "nav-pills-primary", "nav-pills-info", "nav-pills-success", "nav-pills-warning","nav-pills-danger"
                                                -->
                                                <li class="col-md-6">
                                                    <a href="http://globo.com/" aria-expanded="true">
                                                        <i class="material-icons">dashboard</i>
                                                        DashForm&trade; Inicial
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">settings</i>
                                                        Conselho Tutelar
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">today</i>
                                                       Relizar Matrícula
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">view_module</i>
                                                        Fazer Transferência
                                                    </a>
                                                </li>
                                                
                                                
                                            </ul>  
	                                </div>
	                            </div>
	                          </div>





	                          <div class="panel panel-info col-md-6">
                              <div class="panel-heading" role="tab" id="headingOne">
	                                <a role="button"  data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
	                                    <h4 class="panel-title text-info">
	                                    Relatórios Contextualizados
	                                    </h4>
	                                </a>
	                            </div>
	                            <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="true">
	                              <div class="panel-body">

                                        <ul class="nav nav-pills nav-pills-info nav-pills-icons nav-stacked row" role="tablist">
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">text_rotate_vertical</i>
                                                    Ficha Matrícula
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">compare_arrows</i>
                                                    Declaração Matrícula
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">equalizer</i>
                                                    Ficha de Notas
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">person_add_disabled</i>
                                                    Notificação Matrícula
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">equalizer</i>
                                                    Declaração Aluno
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">directions_bus</i>
                                                       Formulário Transf
                                                    </a>
                                                </li>
                                        </ul>
                                        


	                              </div>
	                            </div>
	                        </div>  

                            <div class="row">
                            <div class="card card-nav-tabs">
                                    <div class="header header-info">
                                        <div class="nav-tabs-navigation">
                                            <div class="nav-tabs-wrapper">
                                                <ul class="nav nav-tabs" data-tabs="tabs">
                                                    <li class="active">
                                                        <a href="#students" data-toggle="tab">
                                                            Matrículas
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#synapse" data-toggle="tab">
                                                            Histórico Escolar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#opendata" data-toggle="tab">
                                                            Dados externos
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
								    </div>
                                    <div class="card-content">
                                        <div class="tab-content text-center">
                                            <div class="tab-pane active" id="students">
                                                <div id="style-1" class="table-responsive pre-scrollable scrollbar">
                                                <table class="table table-striped editable">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Ano</th>
                                                            <th class="text-center">Turma</th>
                                                            <th class="text-center">Transporte</th>
                                                            <th class="text-center">Etapa de Ensino</th>
                                                            <th class="text-center">Situação</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                2018
                                                            </td>
                                                            <td class="text-center">
                                                                3º ANO C
                                                                <button type="button" rel="tooltip" class="btn btn-info btn-simple" data-original-title="" title="">
                                                                        <i class="material-icons">edit</i>
                                                                </button>
                                                            </td>
                                                            <td><a href="#">Ônibus</a></td>                                                          </td>
                                                            <td><a href="#">Fundamental de 9 anos Multi</a></td>
                                                            <td><a href="#">Matrículado</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                2017
                                                            </td>
                                                            <td class="text-center">
                                                                2º ANO B
                                                            </td>
                                                            <td>Ônibus</td>                                                          </td>
                                                            <td>Fundamental de 9 anos Multi</td>
                                                            <td>Matrículado</td>
                                                        </tr>
                                                        

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
							</div>
                            
                            </div>

            </div>
        </div>
        <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-notice wizard-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
                </div>
                <div id="wizard" data-color="blue" class="modal-content card wizard-card editable">
                    <form action="" method="">
                                    <div class="wizard-navigation">
                                        <ul>
                                            <li><a href="#location" data-toggle="tab">Turma</a></li>
                                            <li><a href="#type" data-toggle="tab">Transporte</a></li>
                                            <li><a href="#facilities" data-toggle="tab">Multi Etapa</a></li>
                                            <li><a href="#addinfo" data-toggle="tab">Adicionais</a></li>
                                        </ul>
                                    </div>

                                    <div class="tab-content">
                                        <div class="tab-pane" id="location">
                                            <h4 class="text-center">Qual a turma que deseja matricular o Aluno? </h4>
                                            <h5 class="text-center"><a href="#"></a></h5>
                                        </div>
                                        <div class="tab-pane" id="type">
                                            <h4 class="text-center">O aluno vai precisar de transporte escolar? </h4>
                                            <h5 class="text-center"><a href="#">Sim</a></h5>
                                        </div>
                                        <div class="tab-pane" id="facilities">
                                            <h4 class="text-center">A turma é multi-etapa? Caso seja qual é a etapa do aluno?</h4>
                                            <h5 class="text-center"><a href="#">Sim</a>,<a href="#">Fundamental de 9 anos - 1º ANO</a></h5>
                                            <h4 class="text-center">A turma é unificada? Caso seja qual é o tipo do aluno? </h4>
                                            <h5 class="text-center"><a href="#">Não</a>,<a href="#"></a></h5>
                                        </div>
                                        <div class="tab-pane" id="addinfo">
                                            <h4 class="text-center">Qual é o Tipo de Ingresso do Aluno?</h4>
                                            <h5 class="text-center"><a href="#">Sim</a></h5>
                                            <h4 class="text-center">Recebe escolarização em outro espaço?</h4>
                                            <h5 class="text-center"><a href="#">Não</a></h5>
                                            <h4 class="text-center">Qual a situação do aluno no ano anterior?</h4>
                                            <h5 class="text-center"><a href="#">Aprovado</a></h5>
                                            <h4 class="text-center">Qual a situação na série atual?</h4>
                                            <h5 class="text-center"><a href="#">Não</a></h5>
                                        </div>
                                    </div>
                                    <div class="wizard-footer">
                                        <div class="pull-right">
                                            <input type='button' class='btn btn-next btn-fill btn-info btn-wd' name='next' value='Avançar' />
                                            <input type='button' class='btn btn-finish btn-fill btn-success btn-wd' name='finish' value='Registrar' />
                                        </div>
                                        <div class="pull-left">
                                            <input type='button' class='btn btn-previous btn-fill btn-default btn-wd' name='previous' value='Voltar' />
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </form>
                    
                </div>
            </div>
        </div>   
     
</div>   

