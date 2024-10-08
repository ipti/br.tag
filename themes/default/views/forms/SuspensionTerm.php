<?php
/* @var $this ReportsController */
/* @var $report Mixed */
/* @var $school SchoolIdentification*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsDeclarationReport/_initialization.js?v='.TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="pageA4V">
    <?php $this->renderPartial('head'); ?>
    <div id="report" style="font-size: 14px">
        <div style="width: 100%; margin: 0 auto; text-align:justify;margin-top: -15px;">
            <br><br/>
            <br><br/>
            <div id="report_type_container" style="text-align: center">
                <span id="report_type_label" style="font-size: 16px">Suspensão Escolar</span>
            </div>
            <br><br><br/>
            <span class="pull-right">
                <?= $school->edcensoCityFk->name ?> (SE), ____/____/________
            </span>
            <br><br><br>
            <p style="line-height: 28px">Suspensão que se faz ao(à) aluno(a) _______________________________________________________ do _________ ano, turma ____________________, pelo período de ________ dias (de ____/____/________ a ____/____/________) pelo fato de o(a) mesmo(a):</p>
            <p style="word-break: break-all; line-height: 28px">___________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________</p>
            <br/><br/><br><br>
            <div style="" class="signature">
                Gestor(a) Escolar
            </div>
            <br/><br/><br><br>
            <span>
                Responsável Legal: ______________________________________________________________________________________ <?= $school->edcensoCityFk->name ?> (SE), ____/____/________
            </span>
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

    #reasons {
        width:  100%;
        resize: none;
    }

    #editable {
        width: 100px;
        float:left;
    }

    .textClassroom {
        float: left;
        margin-right: 30px
    }

    #report_type_label {
        font-weight: bold;
        text-transform: uppercase;
    }

    .boxAss {
        float: left;
        width: 50%;
        text-align: center;
    }

    .signature {
        width: 330px;
        border-top: solid 1px #000;
        margin: auto;
        text-align: center;
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

        textarea {
            border: none !important;
        }

        .signature {
            width: 300px;
            margin: auto 10px;
        }

    }
</style>
