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
            <br><br/>
            <br><br/>
            <div id="report_type_container" style="text-align: center">
                <span id="report_type_label" style="font-size: 16px">Termo de Advertência Escolar</span>
            </div>
            <br><br><br/>
            <p>Aluno(a): <?= $student['name'] ?></p>
            <br>
            <p>Filiação: <?= $student['mother'] ?> e <?= $student['father'] ?></p>
            <br>
            <p><span class="textClassroom">Série/Ano: <?= $student['classroom'] ?></span><span class="textClassroom">Turma: <?= $student['stage']?></span><span class="textClassroom">Turno: <?= $turn ?></span></p>
            <br>
            <br>
            <p>Fica o(a) aluno(a) acima citado(a) ADVERTIDO(A) a partir desta data, conforme Regimento Escolar desta Instituição Educacional e este documento deve ser anexado à pasta do(a) mesmo(a).</p>
            <br>
            <p>Motivos:</p>
            <textarea name="reasons" id="reasons" cols="30" rows="10"></textarea>
            <br>
            <br>
            <br>
            <br>
            <br>
            <span class="pull-right">
                <?= $school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . ' de ' . yii::t('default', date('F')) . ' de ' . date('Y') . '.' ?>
            </span>
            <br/><br/><br><br><br>
            <div class="signature">
                Gestor(a)
            </div>
            <div class="signature">
                Secretário(a) Escolar
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
        margin: auto 80px;
        float: left;
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