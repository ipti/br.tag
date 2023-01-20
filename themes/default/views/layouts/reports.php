<?php
$cs = Yii::app()->clientScript;
$cs->scriptMap = array(
    'jquery.js' => false,
    'jquery.ba-bbq.js' => false
);
$baseUrl = Yii::app()->theme->baseUrl;
$cs->registerScriptFile($baseUrl . '/js/jquery.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile($baseUrl . '/js/jquery-ba-bbq.js', CClientScript::POS_HEAD);
/*
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
*/
$r = $imob = 0;

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
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/template.css?v=1.0" rel="stylesheet" type="text/css" />

        <!-- Glyphicons Font Icons -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/glyphicons.min.css" rel="stylesheet" type="text/css" />

        <!-- Select2 Plugin -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/select2.css" rel="stylesheet" />

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mask.min.js" ></script>

        <!-- Bootstrap -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js"></script>

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/util.js?v=1.0" ></script>

        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/uniform.js" ></script>

        <!-- Select2 Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/select2.js"></script>
        
        <!-- QRCode Plugin -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.qrcode.min.js" type="text/javascript"></script>
        <!-- Print -->
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" rel="stylesheet" type="text/css" />

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
            });
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
            
            $(function () {
                $("[id2='school']").change(function () {
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
                    size: 75,

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
                    fontcolor: '#3F45EA',

                    image: null
                    });
            });
        </script>

    </head>
    <body>
        <!-- Main Container Fluid -->
        <div>
            <!-- Sidebar menu & content wrapper -->
            <div>
                <div>
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </body>
</html>
