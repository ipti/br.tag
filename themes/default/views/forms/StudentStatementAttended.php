<?php
/* @var $this ReportsController */
/* @var $report Mixed */
/* @var $school SchoolIdentification*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsDeclarationReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="pageA4V">
    <?php $this->renderPartial('head'); ?>
    <div id="report" style="font-size: 14px">
        <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
            <br><br/><br/><br/>
            <div id="report_type_container" style="text-align: center">
                <span id="report_type_label" style="font-size: 16px">DECLARAÇÃO</span>
            </div>
            <br><br><br/>
            <p class="text-indent">Declaro para os devidos fins de direito e comprovação que o(a) aluno(a) <?= $student['name_student'] ?>, nascido(a) em <?= $student['birthday'] ?>, 
            filho(a) de <?= $student['filiation_1'] ?> e <?= $student['filiation_2'] ?>, cursou neste estabelecimento de ensino <?= $descCategory ?> 
            na modalidade <?= $modality[$student['modality']] ?> no ano letivo de <?= $student['school_year'] ?>.</p>
            <br><br>
            <p class="text-center"><strong>Situação do(a) aluno(a):</strong>           <span class="ml-30"> <span class="mr-10">(</span>) Aprovato(a) </span><span class="ml-30"><span class="mr-10">(</span>) Retido(a)</span></p>
            <br><br><br/>
            <p class="text-center">Este documento não contém rasuras e terá validade de 30 (trinta) dias, a contar da data de expedição.</p>
            <br><br><br><br>
            <span class="pull-right">
                <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
            </span>
            <br/><br/><br><br><br>
            <div style="text-align: center">
                <div class="signature">Gestor</div>
            </div>
        </div>
    </div>
    <br/><br/><br/><br/><br/><br/><br/><br/>
    <?php $this->renderPartial('footer'); ?>
</div>
<style>
    .ml-30 {
        margin-left: 30px
    }
    .mr-10 {
        margin-right: 10px;
    }

    .text-center {
        text-align: center;
    }

    .text-indent {
        text-indent: 50px;
    }

    .signature {
        width: 500px;
        border-top: solid 1px #000;
        margin: auto;
    }

    @media screen{
        .pageA4V{width:980px; height:1400px; margin:0 auto;}
        .pageA4H{width:1400px; height:810px; margin:0 auto;}
        #header-report ul#info, #header-report ul#addinfo {
            width: 100%;
            margin: 0;
            display: block;
            overflow: hidden;
        }
    }

    @media print {
        #header-report ul#info, #header-report ul#addinfo {
            width:100%;
            margin: auto;
            display: block;
            text-align: center;
        }

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
    }
</style>