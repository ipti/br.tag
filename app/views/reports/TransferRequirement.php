<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/TransferRequirement/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>

<br/>
<div class="innerLR district">
    <div>
        
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                jQuery.ajax({'type': 'GET',
                    'data': {'enrollment_id':<?php echo $enrollment_id;?>},
                    'url': '<?php echo Yii::app()->createUrl('reports/getTransferRequirementInformation') ?>',
                    'success': function (data) {
                        gerarRelatorio(data);
                    }, 'error': function () {
                        limpar();
                    }, 'cache': false});
                return false;
            }
            );
            /*]]>*/
        </script>
        
        <br>
        <div id="report" style="font-size: 12px">

            <div id="container-header" style="text-align: center; width: 100%; margin: 0 auto;margin-top: -30px;">
                <div>
                    <img src="data:<?=$school->logo_file_type?>;base64,<?=base64_encode($school->logo_file_content)?>" width="40px" style="margin-bottom:10px">
                </div>
                <span style="font-size: 14px">
                    ESTADO DE SERGIPE<br>
                    PREFEITURA MUNICIPAL DE <?=strtoupper($school->edcensoCityFk->name)?><br>
                    
                </span>
                <span style="clear:both;display:block"></span>
            </div>
            <br/><br/>
            <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
                <div style="text-align: center;">
                    <span style="font-size: 14px">DIVISÃO DE INSPEÇÃO ESCOLAR</span><br><br><br>
                    <span style="font-size: 16px; font-weight: bold">REQUERIMENTO Nº __________</span>
                </div>
                <br>
                
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Venho requerer da Divisão de Inspeção Escolar o documento de transferência de 
                <span class="name" style="font-weight: bold"></span>, 
                <?php
                    if ($gender == '1'){
                        echo "filho de ";
                    } else {
                        echo "filha de ";
                    }
                ?>
                <span class="mother"></span>
                e 
                <span class="father"></span>, 
                <?php
                    if ($gender == '1'){
                        echo "nascido em ";
                    } else {
                        echo "nascida em ";
                    }
                ?>
                <span class="birthday"></span>, 
                <?php
                    if ($gender == '1'){
                        echo "matriculado no ano de ";
                    } else {
                        echo "matriculada no ano de ";
                    }
                ?>
                <span class="enrollment_date"></span>
                no(a) 
                <?php echo $school->name . ', ' ?> 
                <span class="city"></span>-<span class="state"></span>, 
                <?php
                    $c;
                    switch ($class) {
                        case '4':
                            $c = '1º';
                            break;
                        case '5':
                            $c = '2º';
                            break;
                        case '6':
                            $c = '3º';
                            break;
                        case '7':
                            $c = '4º';
                            break;
                        case '8':
                            $c = '5º';
                            break;
                        case '9':
                            $c = '6º';
                            break;
                        case '10':
                            $c = '7º';
                            break;
                        case '11':
                            $c = '8º';
                            break;
                        case '14':
                            $c = '1º';
                            break;
                        case '15':
                            $c = '2º';
                            break;
                        case '16':
                            $c = '3º';
                            break;
                        case '17':
                            $c = '4º';
                            break;
                        case '18':
                            $c = '5º';
                            break;
                        case '19':
                            $c = '6º';
                            break;
                        case '20':
                            $c = '7º';
                            break;
                        case '21':
                            $c = '8º';
                            break;
                        case '41':
                            $c = '9º';
                            break;
                        case '25':
                        case '30':
                        case '35':
                            $c = '1º';
                            break;
                        case '26':
                        case '31':
                        case '36':
                            $c = '2º';
                            break;
                        case '27':
                        case '32':
                        case '37':
                            $c = '3º';
                            break;
                        case '28':
                        case '33':
                        case '38':
                            $c = '4º';
                            break;
                    }
                    echo 'no '. $c. ' Ano. ';
                ?>
                No ensejo, informo que 
                <?php
                    if ($gender == '1'){
                        echo "o aluno ";
                    } else {
                        echo "a aluna ";
                    }
                ?>
                irá estudar no(a) _______________________________________.<br><br>
                Requerente: ___________________________.<br>
                RG: ___________________________.<br>
                Motivo da transferência: ___________________________.
                
                <br><br><br><br>
                <span class="pull-right">
                    <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
                </span>
                <br/><br/><br><br><br>
                <div style="text-align: center">
                    <span>_______________________________________________________________________________________</span><br>
                    <span>Assinatura do Requerente</span>
                </div>
            </div>
        </div>
        <?php $this->renderPartial('footer'); ?>
    </div>
</div>

<style>
    @media print{
        #report {
            margin: 0 50px 0 100px;
        }
    }
</style>