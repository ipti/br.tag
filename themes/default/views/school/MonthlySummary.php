<?php
$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
$uf = EdcensoUf::model()->findByPk($school['edcenso_uf_fk']);
?>

<div class='container-report border mt-40'>
  <div class="row-grid legends">
    <div class="col  no-border p-5">
      <div class="row-grid no-border">
        <div class="col-2  no-border p-5">
        <?php
          if(isset($school->logo_file_name)){
              echo '<img id="logo" src="data:'.$school->logo_file_type.';base64,'.base64_encode($school->logo_file_content).'">';
          };
        ?>
        </div>
        <div class="col no-border p-5 text-center text-uppercase">
          <p>
            ESTADO DO <?= $uf->name ?></br>
            PREFEITURA MUNICIPAL DE <?= $school->edcensoCityFk->name ?></br>
            SECRETARIA MUNICIPAL DE EDUCAÇÃO
          </p>
        </div>
      </div>
    </div>
    <div class="col p-5 no-border-right no-border-top no-border-bottom font-bold blue-background titleBig text-center">RESUMO MENSAL DE FREQUÊNCIA</div>
  </div>
  <div class="row-grid no-border-bottom min-height-40">
    <div class="col no-border-left p-5">
      <span class="font-bold text-uppercase"> Unidade: </span>
      <div class="no-border"><?= $school['name']?></div>
    </div>
    <div class="col no-border-left p-5">
      <span class="font-bold text-uppercase">Bairro: </span>
      <div class="no-border"><?= $school['address_neighborhood'] ?></div>
    </div>
    <div class="col no-border-left no-border-right p-5">
      <span class="font-bold text-uppercase">Período: </span>
      <div class="contentEditable no-border" contenteditable="true"></div>
    </div>
  </div>
  <div class="row-grid blue-background height-head text-uppercase">
    <div class="col-1 no-border-left no-border-top text-center line-style-head">Matrícula</div>
    <div class="col-3 no-border-left no-border-top text-center line-style-head">Nome do(a) Servidor(a)</div>
    <div class="col no-border-left no-border-top text-center font-bold font-size-middle">
      <div class="row-grid no-border">
        <div class="col no-border-top no-border-left no-border-bottom no-border-right">Apuração</div>
      </div>
      <div class="row-grid no-border height-100 head-font-small line-height-25">
        <div class="col-3  no-border-bottom no-border-left no-border-right">Faltas</div>
        <div class="col no-border-bottom no-border-right">Presenças</div>
        <div class="col  no-border-bottom no-border-right">Faltas Just.</div>
      </div>
    </div>
    <div class="col no-border-top no-border-left no-border-bottom text-center font-bold font-size-middle">
      <div class="row-grid no-border">
        <div class="col no-border-bottom no-border-left no-border-right no-border-top">Licença/Afastamento</div>
      </div>
      <div class="row-grid no-border height-100 head-font-small line-height-25">
        <div class="col no-border-bottom no-border-right no-border-left">Código</div>
        <div class="col no-border-bottom no-border-right">Início</div>
        <div class="col no-border-bottom no-border-right">Término</div>
      </div>
    </div>
    <div class="col-1 text-center line-style-head no-border-top no-border-left no-border-right no-border-bottom">Cargo</div>
    <div class="col-1 text-center line-style-head no-border-top no-border-right no-border-bottom">Componente curricular/eixo</div>
    <div class="col-1 no-border-top no-border-right no-border-bottom text-center font-bold font-size-middle">
      <div class="row-grid no-border-left no-border-top no-border-bottom">
        <div class="col no-border-left no-border-right no-border-top no-border-bottom ">Turno</div>
      </div>
      <div class="row-grid no-border-left height-100 head-font-small line-height-25">
        <div class="col  no-border-left no-border-right no-border-top no-border-bottom">M</div>
        <div class="col no-border-top no-border-right no-border-bottom">T</div>
        <div class="col no-border-right no-border-top no-border-bottom">N</div>
      </div>
    </div> 
  </div>
  <? for($i=1; $i <= 12; $i++): ?>
    <div class="row-grid">
      <div class="col-1 no-border-right no-border-left no-border-top <?= $i==12 ? 'no-border-bottom' : '' ?>"><div class="contentEditable no-border" contenteditable="true"></div></div>
      <div class="col-3 no-border-right no-border-top <?= $i==12 ? 'no-border-bottom' : '' ?>"><div class="contentEditable no-border" contenteditable="true"></div></div>
      <div class="col no-border-bottom no-border-bottom no-border-top no-border-right ">
        <div class="row-grid no-border">
          <div class="col-3  no-border-left no-border-top no-border-right <?= $i==12 ? 'no-border-bottom' : '' ?>">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
          <div class="col no-border-top no-border-right <?= $i==12 ? 'no-border-bottom' : '' ?>">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
          <div class="col  no-border-right no-border-top <?= $i==12 ? 'no-border-bottom' : '' ?>">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
        </div>
      </div>
      <div class="col no-border-bottom">
        <div class="row-grid no-border">
          <div class="col no-border-right no-border-right no-border-left no-border-top no-border-bottom">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
          <div class="col  no-border-right no-border-top no-border-bottom">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
          <div class="col no-border-right no-border-top no-border-bottom">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
        </div>
      </div>
      <div class="col-1 no-border-left no-border-bottom"><div class="contentEditable no-border" contenteditable="true"></div></div>
      <div class="col-1 no-border-left no-border-bottom"><div class="contentEditable no-border" contenteditable="true"></div></div>
      <div class="col-1 no-border-left no-border-right no-border-bottom">
        <div class="row-grid no-border">
          <div class="col no-border-left no-border-right no-border-top no-border-bottom">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
          <div class="col no-border-top no-border-right no-border-bottom">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
          <div class="col no-border-right no-border-top no-border-bottom">
            <div class="contentEditable no-border" contenteditable="true"></div>
          </div>
        </div>
      </div>
    </div>
  <? endfor; ?>
  <div class="min-height-40 row-grid">
    <div class="col p-5 no-border-left no-border-right no-border-bottom">
      <span class="font-bold">Observação:</span>
      <div class="contentEditable no-border" contenteditable="true"></div>
    </div>
  </div>
</div>
<div class='container-report mt-10'>
  <div class="row-grid">
    <div class="no-border p-5 col-5 legends">
      <div class="row-grid no-border">
        <div class="col no-border">
          Legendas:
          <p>TS - Tratamento de Saúde</p>
          <p>LP - Licença Paternidade</p>
          <p>LM - Licença Maternidade</p>
          <p>AC - Acomp. do Cônjuge</p>
        </div>
        <div class="col no-border">
          <p class="mt-20">Lpre - Licença Prêmio</p>
          <p>FE - Férias</p>
          <p>ME - Mandato Eletivo</p>
          <p>MT -  Matrimônio</p>
        </div>
        <div class="col border-right no-border">
          <p class="mt-20">LT - Luto</p>
          <p>TF - Tratamento de Família</p>
          <p>FJD - Falta Justificada pela direção</p>
          <p>Outros: </p>
        </div>
      </div>
    </div>
    <div class="col-2 no-border text-center">
      <p class='dateBox'>
        Data: __/__/____
      </p>
    </div>
    <div class="col no-border text-center">
      <p class='assBox border-top'>
        Gestor(a)
      </p>
    </div>
  </div>
</div>

<style>
   
  .container-report {
    width: 950px;
    margin: auto;
  }

  .container-report:after {
    clear: both;
  }

  .mt-40 {
    margin-top: 40px;
  }

  .container-report:before, .container-report:after {
    display: table;
    content: "";
    line-height: 0;
  }

  .row-grid div {
    border: 1px solid black;
    overflow: hidden;
  }

  .border {
    border: 1px solid black;
  }

  .border-top {
    border-top: 1px solid black;
  }

  .no-border-top {
    border-top: none !important;
  }

  .border-bottom {
    border-bottom: 1px solid black;
  }

  .no-border-bottom {
    border-bottom: none !important;
  }

  .border-left {
    border-left: 1px solid black;
  }

  .no-border-left {
    border-left: none !important;
  }

  .border-right {
    border-right: 1px solid black;
  }

  .no-border-right {
    border-right: none !important;
  }

  .no-border {
    border: none !important;
  }

  .padding-default {
     padding: 10px;
  }

  .text-center {
    text-align: center;
  }

  .float-left {
    float: left;
  }

  .row-grid {
    display: flex;
    width: 100%;
  }

  .height-40 {
    height: 40px;
  }

  .height-30 {
    height: 30px;
  }

  .min-height-40 {
    min-height: 40px;
  }

  .mt-10 {
    margin-top: 10px;
  }

  .height-100 {
    height: 100%;
  }

  .height-29 {
    height: 29px;
  }

  .blue-background {
    background-color: #C6D9F1;
  }

  .col {
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    min-width: 0;
    max-width: 100%;
  }

  .col-3 {
    flex: 0 0 25%;
    max-width: 25%;
  }

  .col-1 {
    -ms-flex: 0 0 8.333333%;
    flex: 0 0 8.333333%;
    max-width: 8.333333%;
  }

  .col-2 {
    -ms-flex: 0 0 16.666667%;
    flex: 0 0 16.666667%;
    max-width: 16.666667%;
  }

  .col-4 {
    -ms-flex: 0 0 33.333333%;
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }

  .col-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }

  .line-style-head {
    line-height: 44px;
    font-weight: bold;
    font-size: 12px;
  }
  
  .font-size-middle {
    font-size: 12px;
  }

  .line-heigth-38 {
    line-height: 38px;
  }

  .head-blue {
    font-weight: bold;
    font-size: 14px;
  }

  .font-bold {
    font-weight: bold;
  }

  .contentEditable {
    padding: 2px 5px;
    font-size: 12px;
  }

  .height-head {
    height: 48px;
  }

  .legends {
    font-weight: bold;
    font-size: 11px
  }
  
  .head-font-small {
    font-size: 10px;
  }
  
  .assBox {
    margin-top: 46px;
  }

  .dateBox {
    margin-top: 33px;
  }

  .mt-20 {
    margin-top: 20px;
  }

  .p-5 {
    padding: 5px;
  }

  .titleBig {
    font-size: 20px;
    line-height: 55px;
  }
  
  .text-uppercase {
    text-transform: uppercase;
  }
  
  .line-height-25 {
    line-height: 25px;
  }

  body {
    -webkit-print-color-adjust: exact !important;
  }

  .table-border, th, td {
    border: 1px solid black;
    border-collapse: collapse;
  }

  td, th {
    padding: 8px;
  }
  
  table { 
    width: 100%;
    page-break-inside:auto
  }

  .no-padding table {
    border: none !important; 
  }

  tr { page-break-inside:avoid; page-break-after:auto }

  .no-padding {
    padding: 0 !important;
  }


  @media print{
    @page {
      size: landscape;
    }

    .blue-background {
      -webkit-print-color-adjust: exact;
      background-color: #C6D9F1 !important;
    }

    .bln-print {
      border-left: none !important;
    }

    .container-report { font-size: 90% !important; }
  }
</style>
