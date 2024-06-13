<?php

$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

// Certifique-se de que $school está definido
if (!isset($school)) {
    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
}

?>

<div class="pageA4H">
    <div style="text-align: center;">
        <div style="position: relative; display: inline-block;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/brasao.png" alt="Brasão" style="width: 80px; position: absolute; top: -60px; left: 50%; transform: translateX(-50%);" />
        </div>
        <h4>ESTADO DO <?php echo strtoupper($school->edcensoUfFk->name); ?></h4>
        <h5>PREFEITURA MUNICIPAL DE <?php echo $school->edcensoCityFk->name; ?></h5>
        <h5>SECRETARIA MUNICIPAL DE EDUCAÇÃO</h5>
        <h1>CERTIFICADO</h1>
    </div>

<div class="container-certificate">
    <!-- <p>O(A) Diretor(a) da: ____________________________________</p> -->
    <p>O(A) Diretor(a) da <?php echo $school->name ?>
    no uso de suas atribuições legais, confere o presente Certificado do ___(ano de ensino)___ do ___(tipo de ensino)___ a</p>
    <p>filho(a) de ______________________________________</p>
    <p>e de ____________________________________________</p>
    <p>Nascido(a) em ____ de _______________ de ________, no Município de _______________________________</p>
    <p>Estado do _______________________________</p>
</div>



    <div class ="content-data">
        <div style="display: inline-block; width: 45%; text-align: center;">
            <p>_______________________________</p>
            <p>Secretário(a)</p>
        </div>
        <div style="display: inline-block; width: 45%; text-align: center;">
            <p>______________ (MA) de ______________ de _____</p>
        </div>
    </div>

    <div class="signature-section">
        <p>_______________________________</p>
        <p>Aluno(a)</p>
    </div>
    <div class="content-data-signature">
    <div>
        <p>Reconhecida pela Resolução: ___________ de ____/____/______</p>
        <p>Reconhecida pela Resolução do: _______________________________</p>
    </div>

    <div style="text-align: center;">
        <p>_______________________________</p>
        <p>Diretor(a) da Unidade de Ensino</p>
    </div>
    </div>
    <div class="row-fluid hidden-print" style="margin-top: 20px;">
        <div class="span12">
            <div class="buttons" style="text-align: center;">
                <a id="print" onclick="imprimirPagina()" class='btn btn-icon glyphicons print hidden-print' style="padding: 10px;">
                    <img alt="impressora" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Impressora.svg" class="img_cards" /> <?php echo Yii::t('default', 'Print') ?><i></i>
                </a>
            </div>
        </div>
    </div>

    <?php $this->renderPartial('footer'); ?>
</div>

<script>
    function imprimirPagina() {
        window.print();
    }
</script>

<style>
    .pageA4H {
        border-radius: 10px;
        padding: 10px;
        border: 2px solid #000;
        font-family: 'Arial', sans-serif;
        width: 90%;
        height: 100%;
        position: relative;
        box-sizing: border-box;
        margin: 23px 60px 23px 60px;


    }

    h1, h4, h5 {

        margin-top:5px;
    }
h4{
    font-size: 13.99px;
    font-weight: 700;
    color: #252A31;
}
h5{
    font-size: 13.99px;
    font-weight: 400;
    color: #252A31;
}
h1{
    font-weight: 900;
    font-size: 35.13px;
    color: #16205B;
    margin: 20px;

}
.content-data{

    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    align-items: center;
}
    p {
        margin: 5px 0;
    font-size: 14px;
    font-weight: 500;

    }
    .signature-section{
        margin-top: 25px;
        text-align: center;
    }

    .signature-section p {
        margin: 20px 0;
    }
    .container-certificate{
        display: flex;
    justify-content: center;
    flex-direction: column;
    text-align: justify;
    padding: 10px 60px;
    }
    .content-data-signature{
        display: flex;
    justify-content: space-around;
    gap: 200px;
    margin-top: 20px;
    }

    @media print {
        .hidden-print {
            display: none;
        }

        @page {
            size: landscape;
        }
    }
</style>
