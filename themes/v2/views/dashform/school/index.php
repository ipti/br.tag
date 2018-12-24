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
        var bb = [-37.50719669199343, -11.36121753275635];
        map.selectAll("circle")
		.data([aa,bb]).enter()
		.append("circle")
		.attr("cx", function (d) { console.log(projection(d)); return projection(d)[0]; })
		.attr("cy", function (d) { return projection(d)[1]; })
		.attr("r", "8px")
		.attr("fill", "red")
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
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h3 class="editable"> 
                                                <a data-toggle="tooltip" 
                                                data-placement="top" title="" data-container="body" 
                                                data-original-title="Tooltip on top" href="#" id="name">
                                                            Colegío  Antonio Carlos Valadares
                                                </a>
                                            </h2>
                                        </div>
                                        <div class="col-md-2">
                                            <button id="censo-active" class="btn btn-success btn-just-icon">
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
                                            <i class="material-icons">group_work</i>
                                        </div>
                                        <div class="description editable">
                                            <h4 class="info-title">Perfil Escolar</h4>
                                            <p>
                                                A escola <a href="#" id="name">Municipal</a>

                                            possui código do INEP <a href="#" id="inep">
                                                28025172
                                                </a>
                                                encontra-se em situação em <a href="#" id="situation">atividade</a> e
                                                <a data-hint="Não pode ser preenchido quando o campo 37 
                                                (Unidade vinculada a Escola de Educação Básica ou Unidade
                                                ofertante de Ensino Superior) não for preenchido com 2 
                                                (Unidade ofertante de Ensino Superior)." href="#" id="regulation">regulemanta</a> 
                                                no Orgão Regional de Educação <a data-hint="erro 2 censo" href="#" id="organ">DRE-01</a>
                                            para funcionamento durante o período de <a href="#" id="situation">20/02/2018</a> a <a href="#" id="situation">11/12/2018</a>
                                            </p>
                                            <p>
                                                Localizada em área <a href="#" id="location">Urbana</a> e <a>não diferenciada</a> 
                                                no Logradouro <a href="#" id="address">PCA DA BANDEIRA</a>,
                                                <a href="#" id="address">N° 30 </a>, Bairro/Povoado 
                                                <a href="#" id="neighboor">Centro</a>,
                                                CEP: <a href="#" id="cep">49090390</a> da
                                                Cidade: <a href="#"></a> do Estado de <a href="#">Sergipe.</a>
                                                Suas coordenadas são Latitude de <a>-11,51938273628</a> 
                                                e Longitude <a>-37,483746383</a>
                                            </p> 
                                            
                                        
                                        </div>
                                    </div>
                                    <div class="info info-horizontal innerLR info-edit">
                                        <div class="icon icon-info">
                                            <i class="material-icons">business</i>
                                        </div>
                                        <div class="description editable">
                                            <h4 class="info-title">Infraestrutura</h4>
                                            <p>
                                                Funcionando em um <a>Prédio Escolar</a>, <a>Próprio</a> e <a>não compartilhado</a>,
                                                a escola possui um estrutura com <a>17</a> salas de aula, sendo <a>17</a> em uso.
                                                <a>Oferece</a> alimentação para os alunos e possui as seguintes dependências:
                                                <a>Sala da Diretoria, Sala de Professores</a>
                                            </p>
                                            <p>
                                                O saneamento básico é feito da seguinte forma, suprimento de água por <a>Rede Pública</a>, energia <a>rede pública</a>.
                                                O esgotamento é realizado por <a>Fossa</a> e o lixo é destinado para <a>Coleta Períodica</a>
                                            </p>
                                            <p>
                                                A escola possui os seguintes equipamentos: <a>5</a>TV, <a>1</a> VCR, <a>2</a> DVD, <a>1</a> Antena parabólica,
                                                <a>0</a> copiadora, <a>2</a> impressora multifuncional, <a>1</a> retroprojetor, <a>1</a> impressora, <a>3</a> aparelho de som
                                                <a>1</a> datashow, <a> 0</a> fax, <a>1</a> máquina fotográfica,<a>6</a> computadores,<a>6</a> computadores administrativos,
                                                <a>0</a> computadores de uso estudantil. A escola <a>possui internet</a> e <a>tem</a> banda larga.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="info info-horizontal innerLR info-edit">
                                        <div class="icon icon-info">
                                            <i class="material-icons">import_contacts</i>
                                        </div>
                                        <div class="description editable">
                                            <h4 class="info-title">Educacional</h4>
                                            <p>
                                                A escola oferece ensino nas modadidades: <a>Regular</a>, <a>Especial</a>. O ensino <a>não é</a> organizado em ciclos e 
                                                <a>não oferece</a> atividades complemetares, <a>não possui</a> proposta pedagógica de formação por alternância,
                                                <a>não oferece</a> atividades do antendimento educacional especializado (AEE)
                                            </p>    
                                            <p>Unidade <a>não vinculada</a> a escola de código <a></a>.
                                                <a>Não cede</a> espaço para o Brasil alfabetizado, <a>cede</a> espaço nos finais de semana para a comunidade.
                                            </p><p>
                                                A escola <a>não oferece</a> educação indígena nas seguintes linguas: <a data-type="select2">Acona/Akona, Aimoré, Ajuru, Akuntsu</a>.
                                                Com relação ao material didático sociocultural, <a>não utiliza</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                        </div>    
                    </div>
                    <div class="col-md-4">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	                          <div class="panel panel-info">
	                            <div class="panel-heading" role="tab" id="headingOne">
	                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
	                                    <h4 class="panel-title text-info">
	                                    Ações Rápidas
	                                    <i class="material-icons">keyboard_arrow_down</i>
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
                                                        Configurações Gerais
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">today</i>
                                                        Calendário Escolar
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">view_module</i>
                                                        Quadro Horário
                                                    </a>
                                                </li>
                                                
                                                
                                            </ul>  
	                                </div>
	                            </div>
	                          </div>





	                          <div class="panel panel-info">
	                            <div class="panel-heading" role="tab" id="headingTwo">
	                              <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
	                                <h4 class="panel-title text-info">
	                                  Relatórios Contextualizados
	                                  <i class="material-icons">keyboard_arrow_down</i>
	                                </h4>
	                              </a>
	                            </div>
	                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" aria-expanded="false" style="height: 0px;">
	                              <div class="panel-body">
                                        <ul class="nav nav-pills nav-pills-info nav-pills-icons nav-stacked" role="tablist">
                                                <li class="col-md-6">
                                                <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                    <i class="material-icons">text_rotate_vertical</i>
                                                    Alunos Ordenados
                                                </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">compare_arrows</i>
                                                    Comparação de Matrículas
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">equalizer</i>
                                                    N° Profisionais por Turma
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">person_add_disabled</i>
                                                    Turmas sem instrutor
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">equalizer</i>
                                                    N° Alunos e professor
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">directions_bus</i>
                                                        Transporte Escolar
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">accessible</i>
                                                        Relaçao Acessibilidade
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#dashboard-2" role="tab" data-toggle="tab" aria-expanded="true">
                                                        <i class="material-icons">accessible</i>
                                                   Alunos Idade Incompatível
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">people</i>
                                                        Beneficiarios Bolsa Familia
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">receipt</i>
                                                        Documentos Pendentes
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">done_all</i>
                                                        Frequência Bolsa Família
                                                    </a>
                                                </li>
                                                <li class="col-md-6">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">face</i>
                                                        Alunos entre 5 e 14 anos
                                                    </a>
                                                </li>
                                        </ul>

	                              </div>
	                            </div>
	                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                                <div class="card card-nav-tabs">
                                    <div class="header header-info">
                                        <div class="nav-tabs-navigation">
                                            <div class="nav-tabs-wrapper">
                                                <ul class="nav nav-tabs" data-tabs="tabs">
                                                    <li class="active">
                                                        <a href="#profile" data-toggle="tab">
                                                            Synapse
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#messages" data-toggle="tab">
                                                            Saúde
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#opendata" data-toggle="tab">
                                                            Dados
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
								    </div>
                                    <div class="card-content">
                                        <div class="tab-content text-center">
                                            <div class="tab-pane active" id="profile">
                                                <p> I will be the leader of a company that ends up being worth billions of dollars, because I got the answers. I understand culture. I am the nucleus. I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. </p>
                                            </div>
                                            <div class="tab-pane" id="messages">
                                                <p> I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. I will be the leader of a company that ends up being worth billions of dollars, because I got the answers. I understand culture. I am the nucleus. I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at.</p>
                                            </div>
                                            <div class="tab-pane" id="settings">
                                                <p>I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. So when you get something that has the name Kanye West on it, it’s supposed to be pushing the furthest possibilities. I will be the leader of a company that ends up being worth billions of dollars, because I got the answers. I understand culture. I am the nucleus.</p>
                                            </div>
                                        </div>
                                    </div>
							</div>

            </div>
        </div>
        
        
</div>   

