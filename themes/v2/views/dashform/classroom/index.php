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
    .card img{width:160px;margin:0 auto;display:block}
    body{background:#eff3f6}
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
                        <li><a href="#">Dashform&#174;</a></li>
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
        <div class="row text-center">
                <h2>Oi Jessica! Bem vinda ao Dashform&#174; da Turma</h2>
                <h5>Confira as opções abaixo, e caso precise de ajuda estamos aqui, basta clicar aqui</h5>
        </div>
        <div class="row container center-block">
            <div class="col-md-4">
                            <div class="card">
	    							<div class="card-content">
                                        <img src="/themes/v2/common/img/dash-censo.png" alt="..." class="">
	    								<h4 class="card-title text-center">
	    									<a class="center-block" href="#pablo">Analise a compatibilidade dos dados com o Censo 2019</a>
                                            <button class="btn btn-info">Verificar Censo<div class="ripple-container"></div></button>
	    								</h4>
                                    </div>
	    						</div>
            </div>
            <div class="col-md-4">
                            <div class="card">
	    							<div class="card-content">
                                        <img src="/themes/v2/common/img/dash-copy.png" alt="..." class="">
	    								<h4 class="card-title text-center">
	    									<a class="center-block" href="#pablo">Copie os dados da turma do ano letivo anterior </a>
                                            <button class="btn btn-info">Reaproveitar Turma<div class="ripple-container"></div></button>
	    								</h4>
	    							</div>
	    						</div>
            </div>
            <div class="col-md-4">
                                <div class="card">
	    							<div class="card-content">
                                        <img src="/themes/v2/common/img/dashboard-classroom.png" alt="..." class="">
	    								<h4 class="card-title text-center">
	    									<a class="center-block" href="#pablo">Criar uma nova turma para o ano letivo atual</a>
                                            <button class="btn btn-info">Cadastrar Turma<div class="ripple-container"></div></button>
	    								</h4>
	    							</div>
	    						</div>
            </div>
            
        </div>
        <div class="container">
            <div class="card">
                <div class="card-content">
                    <div class="col-md-8">
                        <div class="table-responsive">
                                    <div class="form-group form-white">
                                            <input type="text" class="form-control" placeholder="Buscar">
                                            <span class="material-input"></span>
                                        </div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Nome</th>
                                                    <th>Etapa</th>
                                                    <th>Turno</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">1º ANO A</td>
                                                    <td>Fundamental de 9 anos - 1º ANO</td>
                                                    <td>Manhã</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="">
                                                            <i class="material-icons">close</i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">1º ANO A</td>
                                                    <td>Fundamental de 9 anos - 1º ANO</td>
                                                    <td>Manhã</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="">
                                                            <i class="material-icons">close</i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">1º ANO A</td>
                                                    <td>Fundamental de 9 anos - 1º ANO</td>
                                                    <td>Manhã</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="">
                                                            <i class="material-icons">close</i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">1º ANO A</td>
                                                    <td>Fundamental de 9 anos - 1º ANO</td>
                                                    <td>Manhã</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="">
                                                            <i class="material-icons">close</i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">1º ANO A</td>
                                                    <td>Fundamental de 9 anos - 1º ANO</td>
                                                    <td>Manhã</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="">
                                                            <i class="material-icons">close</i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">1º ANO A</td>
                                                    <td>Fundamental de 9 anos - 1º ANO</td>
                                                    <td>Manhã</td>
                                                    <td class="td-actions text-right">
                                                        <button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                            <i class="material-icons">edit</i>
                                                        </button>
                                                        <button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="">
                                                            <i class="material-icons">close</i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                        <div class="col-md-6 center-block">
                                            <ul class="pagination pagination-info">
                                                    <li><a href="javascript:void(0);"> prev</a></li>
                                                    <li><a href="javascript:void(0);">1</a></li>
                                                    <li><a href="javascript:void(0);">2</a></li>
                                                    <li class="active"><a href="javascript:void(0);">3</a></li>
                                                    <li><a href="javascript:void(0);">4</a></li>
                                                    <li><a href="javascript:void(0);">5</a></li>
                                                    <li><a href="javascript:void(0);">next </a></li>
                                            </ul>
                                        </div>
                                    </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                    <ul class="nav nav-pills nav-pills-info nav-pills-icons nav-stacked row" role="tablist">
                                                
                                                <li class="col-md-4">
                                                    <a href="http://globo.com/" aria-expanded="true">
                                                        <i class="material-icons">dashboard</i>
                                                        DashForm&#174; Inicial
                                                    </a>
                                                </li>
                                                <li class="col-md-4">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">transfer_within_a_station</i>
                                                       Alunos por Turma
                                                    </a>
                                                </li>

                                                <li class="col-md-4">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">transfer_within_a_station</i>
                                                      Professores por Turma
                                                    </a>
                                                </li>
                                                
                                                <li class="col-md-4">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">list_alt</i>
                                                       Fichas de Matrícula
                                                    </a>
                                                </li>
                                                <li class="col-md-4">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">exposure</i>
                                                      Atas de Notas
                                                    </a>
                                                </li>
                                                <li class="col-md-4">
                                                    <a href="#schedule-2" role="tab" data-toggle="tab" aria-expanded="false">
                                                        <i class="material-icons">exposure</i>
                                                      Atas de Notas
                                                    </a>
                                                </li>
                                                
                                                
                                </ul>
                    </div>
                </div>


            </div>
        </div>
</div>   

