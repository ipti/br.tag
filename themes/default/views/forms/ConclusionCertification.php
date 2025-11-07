<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/ConclusionCertification/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
/**
 * @var $school SchoolIdentification;
 */
?>

<div class="pageA4V">
    <?php $this->renderPartial('head'); ?>
    <div>
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                    jQuery.ajax({'type': 'GET',
                        'data': {'enrollment_id':<?php echo $enrollment_id;?>},
                        'url': '<?php echo Yii::app()->createUrl('forms/getEnrollmentDeclarationInformation') ?>',
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
        <div id="report" style="font-size: 14px">

            <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
                <br><br/><br/><br/>
                <div id="report_type_container" style="text-align: center">
                    <span id="report_type_label" style="font-size: 16px">CERTIFICADO DE CONCLUSÃO</span>
                </div>
                <br><br><br/>
                <span style="clear:both;display:block"></span>
                Certificamos que
                <span class="name" style="font-weight: bold"></span>,
                <?php
                if ($gender == '1'){
                    echo "filho de ";
                } else {
                    echo "filha de ";
                }
                ?>
                <span style="font-weight:bold" class="filiation_1"></span>
                e
                <span style="font-weight:bold" class="filiation_2"></span>,
                <?php
                if ($gender == '1'){
                    echo "nascido em ";
                } else {
                    echo "nascida em ";
                }
                ?>
                <span style="font-weight:bold" class="birthday"></span>
                na cidade de
                <span style="font-weight:bold" class="city"></span>,
                estado de
                <span style="font-weight:bold;text-transform:uppercase" class="state"></span>,
                de nacionalidade
                <span style="font-weight:bold;text-transform:uppercase" class="nation"><?= $nation ?></span>,
                <?= $status ?> a etapa <span style="font-weight: bold;text-decoration:underline"><?= $alias ?></span> do(a) <span style="text-decoration:underline"><?= $modality ?></span> no ano de <span style="font-weight: bold;text-decoration:underline"><?= date('Y') ?></span>.
                <br/><br/><br/><br/>
                <span class="pull-right">
                    <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
                </span>
                <br/><br/><br/><br/>
                <p style="margin: 0 auto; text-align: center; width:600px">
                    _______________________________________________________<br>
                    <b>ASSINATURA DO DIRETOR(A)/SECRETÁRIO(A)</b>
                </p>
                <br>
            </div>
            <?php $this->renderPartial('footer'); ?>
        </div>
    </div>

    <style>

        #obs{
            width: 30%;
        }

        #optionForm{
            width: 33%;
        }

        .cell {
            border: 1px solid;
            line-height: 16px;
            width: 16px
        }
        @media print {
            #report {
                margin: 0 50px 0 100px;
            }

            #report_type_container{
                border-color: white !important;
            }
            #report_type_label{
                border-bottom: 1px solid black !important;
                font-size: 22px !important;
                font-weight: 500;
                font-family: serif;
            }
            table, td, tr, th {
                border-color: black !important;
            }
            .report-table-empty td {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
            .vertical-text {
                height: 110px;
                vertical-align: bottom !IMPORTANT;
            }
            .vertical-text div {
                transform: translate(5px, 0px) rotate(270deg);
                width: 5px;
                line-height: 13px;
                margin: 0px 10px 0px 0px;
            }


            #obs{
                padding-top: 3%;
                width: 100%;
                border-color: transparent;
                text-transform: uppercase;

            }

            #textOption{
                display: none;
            }
            /*#optionForm {*/
            /*width: 66%;*/
            /*border-color: transparent;*/
            /*color: white;*/
            /*}*/
            select , textarea {
                width: 66%!important;
                -webkit-appearance: none;
                -moz-appearance: none;
                text-indent: 1px;
                text-overflow: '';
                border-color: transparent;
                text-transform: uppercase;

            }

        }
    </style>
