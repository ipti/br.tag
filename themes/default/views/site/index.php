<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . '';
$this->breadcrumbs = array(
    '',
);

$year = Yii::app()->user->year;

$result = Yii::app()->db->createCommand("SELECT `year`,  
	se_total/@x * 100 as se_percent, 
	c_total/@y * 100 as c_percent,   
	@x := se_total as se_total,
	@y := c_total as c_total
FROM iMob 
JOIN (select @x := 1) AS i
JOIN (select @y := 1) AS j;
")->queryAll();

$result  = $result[count($result)-1];

$r = array('s1'=>$result['se_percent'],'s2'=>$result['se_total'],
		'c1'=>$result['c_percent'],'c2'=>$result['c_total']);

//inverteString("% de matrículas").inverteString("% de turmas")
$imob =  strrev(str_pad(ceil($r['s1']), 4, "0", STR_PAD_LEFT)).".".strrev(str_pad(ceil($r['c1']), 4, "0", STR_PAD_LEFT));

?>
<script type="text/javascript">
	$(document).ready(function(){

		var valor = '<?php echo json_encode($r) ?>';
		
        $("#imob").qrcode({
        	// render method: 'canvas', 'image' or 'div'
            render: 'canvas',

            // version range somewhere in 1 .. 40
            minVersion: 1,
            maxVersion: 40,

            // error correction level: 'L', 'M', 'Q' or 'H'
            ecLevel: 'H',

            // offset in pixel if drawn onto existing canvas
            left: 0,
            top: 0,

            // size in pixel
            size: 100,

            // code color or image element
            fill: '#000',

            // background color or image element, null for transparent background
            background: null,

            // content
            text: valor,

            // corner radius relative to module width: 0.0 .. 0.5
            radius: 0,

            // quiet zone in modules
            quiet: 1,

            // modes
            // 0: normal
            // 1: label strip
            // 2: label box
            // 3: image strip
            // 4: image box
            mode: 2,

            mSize: 0.10,
            mPosX: 0.5,
            mPosY: 0.5,

            label: '<?php echo $imob ?>',
            fontname: 'sans',
            fontcolor: '#496CAD',

            image: null
            });
    });
</script>

<div class="row-fluid">
	<div class="span12">
		<h3 class="heading-mosaic">Bem-vindo ao TAG</h3>
	</div>
</div>

<div class="innerLR home">
	<div class="row-fluid">
		<div class="span5">
			<p>A Tecnologia de Apoio à Gestão (TAG) é uma plataforma
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
			</p>

		</div>
		<div class="span6 offset1">
			<div class="row-fluid">
				<div class="span3">
					<a href="<?php echo Yii::app()->homeUrl; ?>?r=student/create"
						class="widget-stats"> <span class="glyphicons user_add"><i></i></span>
						<span class="txt">Adicionar aluno</span>
						<div class="clearfix"></div>
					</a>
				</div>
				<div class="span3">
					<a href="<?php echo Yii::app()->homeUrl; ?>?r=enrollment/create"
						class="widget-stats"> <span class="glyphicons notes_2"><i></i></span>
						<span class="txt">Matricular aluno</span>
						<div class="clearfix"></div>
					</a>
				</div>
				<div class="span3">
					<a href="<?php echo Yii::app()->homeUrl; ?>?r=instructor/create"
						class="widget-stats"> <span class="glyphicons nameplate"><i></i></span>
						<span class="txt">Adicionar professor</span>
						<div class="clearfix"></div>
					</a>
				</div>
				<div class="span3">
					<a href="<?php echo Yii::app()->homeUrl; ?>?r=classroom/create"
						class="widget-stats"> <span class="glyphicons adress_book"><i></i></span>
						<span class="txt">Adicionar turma</span>
						<div class="clearfix"></div>
					</a>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span3">
					<div id="imob"></div>
				</div>
			</div>
			<!--            <div class="row-fluid">
                <div class="span10 offset2">
                    <img class="logo-img" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/tag_logo.png" alt="Logo TAG" />
                </div>
            </div> -->
		</div>
	</div>
</div>
