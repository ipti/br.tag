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
<div class="innerLR boquim">
    <div>
        <!--
        
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                jQuery.ajax({'type': 'GET',
                    'data': {'enrollment_id':<?php //echo $enrollment_id;?>},
                    'url': '<?php //echo Yii::app()->createUrl('reports/getEnrollmentDeclarationInformation') ?>',
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
        
        -->
        <br>
        <div id="report" style="font-size: 12px">

            <div id="container-header" style="text-align: center; width: 100%; margin: 0 auto;margin-top: -30px;">
                <div>
                    <img src="<?php echo yii::app()->baseUrl; ?>/images/boquim.png" width="40px" style="margin-bottom:10px">
                </div>
                <span>
                    ESTADO DE SERGIPE<br>
                    PREFEITURA MUNICIPAL DE BOQUIM<br>
                    SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO</span>
                <span style="clear:both;display:block"></span>
            </div>
            <br/><br/>
            <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
                <div style="text-align: center;">
                    <span>DIVISÃO DE INSPEÇÃO ESCOLAR</span><br><br>
                    <span>REQUERIMENTO Nº __________</span>
                </div>
                <br>
                
                Venho requerer da Divisão de Inspeção Escolar o documento de transferência de _________, filho(a) de ________ e _______, nascido(a) em __/__/____, matriculado(a) no ano de ____ na ****Escola Municipal _____****, Boquim-SE, no ____ Ano. No ensejo, informo que o(a) aluno(a) irá estudar no(a) ______.<br>
                Requerente: _____.<br>
                RG: ____.<br>
                Motivo da transferência: _____.
                
                <br><br><br><br>
                <span class="pull-right">
                    Boquim(SE), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
                </span>
                <br/><br/><br>
                <div style="text-align: center">
                    <span>_______________________________________________________________________________________</span><br>
                    <span>Assinatura do Requerente</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print{
        #report {
            margin: 0 50px 0 100px;
        }
    }
</style>