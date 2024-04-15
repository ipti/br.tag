
<div id="body-students-file-form" class="pageA4V">
  <?php
    $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
    $schoolStrcture = SchoolStructure::model()->find('school_inep_id_fk', Yii::app()->user->school);
    $initial_date = strtotime($school->initial_date);
    $final_date =   strtotime($school->final_date);
    $datediff = $final_date - $initial_date;

    $totalDays = abs(round($datediff / (60 * 60 * 24)));
    $this->renderPartial('head');
  ?>  
  <div class='container-report <?= $type == 1 ? 'mt-40' : 'mt-30' ?> text-center'>
    <h3><?= $title ?></h3>
  </div>
  <div class='container-report mt-30'>
    <table class="table-border head-table font-size-10">
      <tr>
        <td colspan="4"><span class="font-bold">Nome da Escola: </span><div><?= $school->name ?></div></td>
      </tr>
      <tr>
        <td colspan="2"><span class="font-bold">Endereço</>:</span> <div><?= $school->address ?></div></td>
        <td><span class="font-bold">Nº:</span> <div><?= $school->address_number ?></div></td>
        <td><span class="font-bold">Bairro:</span> <div><?= $school->address_neighborhood ?></div></td>
      </tr>
      <tr>
        <td colspan="4"><span class="font-bold">Resolução de Reconhecimento do Curso:</span> <div class="contentEditable no-border" contenteditable="true">  </div></td>
      </tr>
      <tr>
        <td colspan="4"><span class="font-bold">Aluno(a):</span> <div class="contentEditable no-border" contenteditable="true">  </div></td>
      </tr>
      <tr>
        <td><span class="font-bold">Data de Nascimento:</span> <div class="contentEditable no-border" contenteditable="true">  </div></td>
        <td colspan="2"><span class="font-bold">Nacionalidade:</span> <div class="contentEditable no-border" contenteditable="true">  </div></td>
        <td><span class="font-bold">Naturalidade:</span> <div class="contentEditable no-border" contenteditable="true">  </div></td>
      </tr>
      <tr>
        <td colspan="4"><span class="font-bold">Filiação:</span> <div class="contentEditable no-border" contenteditable="true">  </div></td>
      </tr>
      <tr>
        <td colspan="4"><span class="font-bold">Observação:</span> <div class="contentEditable no-border" contenteditable="true">  </div></td>
      </tr>
    </table>
  </div>
  
  <?php switch($type){ 
      case 1:
        $this->renderPartial('_regular_education', array());
      break;
      case 2:
        $this->renderPartial('_education_eja', array());
      break;
     }
  ?>


  <div id="boxDate"  class="row-grid no-border mb-30 mt-40">
    <div class="col no-border">
      <span class="pull-right">
        <?= $school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
      </span>
    </div>
  </div>
  <div class='container-report mt-40 mb-30 text-center'>
    <div class="row-grid no-border">
      <div class="col no-border">
        <div class="mr-10 no-border-left no-border-right no-border-bottom">Secretário(a)</div>
      </div>
      <div class="col no-border">
        <div class="ml-10 no-border-left no-border-right no-border-bottom">Gestor(a)</div>
      </div>
    </div>
  </div>
</div>


<style>
  .no-padding {
    padding: 0 !important;
  }

  .container-report {
    width: 950px;
    margin: auto;
  }

  .container-report:after {
    clear: both;
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
  
  .mt-10 {
    margin-top: 10px;
  }

  .ml-10 {
    margin-left: 10px;
  }

  .mr-10 {
    margin-right: 10px;
  }

  .mt-40 {
    margin-top: 40px;
  }

  .mt-20 {
    margin-top: 20px;
  }

  .mt-30 {
    margin-top: 30px;
  }

  .mb-30 {
    margin-bottom: 30px;
  }

  .mt-100 {
    margin-top: 100px;
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

  .line-heigth-50 {
    line-height: 50px;
  }

  .head-blue {
    font-weight: bold;
    font-size: 14px;
  }

  .font-bold {
    font-weight: bold;
  }

  .contentEditable {
    /* font-size: 10px; */
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

  .boxSpecification {
    height: 92px;
    font-size: 20px;
    line-height: 78px;
  }

  .lh-80 {
    line-height: 80px;
  }

  .lh-46 {
    line-height: 46px;
  }

  .boxObs {
    height: 61px;
  }

  .boxTotal {
    height: 93px;
  }

  #boxDate {
    margin-top: 50px;
    margin-bottom: 50px;
  }
  h2,h4, h3 {
    color: #000;
  }

  body {
    -webkit-print-color-adjust: exact !important;
  }

  .table-border, th, td {
    border: 1px solid black;
    border-collapse: collapse;
  }

  td, th {
    padding: 5px;
  }
  
  table { 
    width: 100%;
    page-break-inside:auto
  }

  .no-padding table {
    border: none !important; 
  }

  tr { page-break-inside:avoid; page-break-after:auto }

  .cicle {
    width: 10px;
    height: 10px;
    border: solid 1px #000;
    border-radius: 50px;
    float: left;
    margin-top: 4px;
    margin-right: 10px;
    margin-left: 50px;
  }

  .background-black {
    background-color: #000;
  }

  .font-head {
    font-size: 12px;
    height: 130px;
    /* white-space: nowrap; */
  }

  .rotate {
    -moz-transform: rotate(-90.0deg);
    /* Opera 10.5 */
    -o-transform: rotate(-90.0deg);
    /* Saf3.1+, Chrome */
    -webkit-transform: rotate(-90.0deg);
    /* IE6,IE7 */
    filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
    /* IE8 */
    -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
    /* Standard */
    transform: rotate(-90deg);
  }
  
  .size-cel {
    width: 66px;
  }

  .size-head {
    width: 180px;
  }

  .font-size-10 {
    font-size: 10px;
  }

  .font-size-12{
    font-size: 12px;
  }

  .size-col-3 {
    width: 64px;
  }

  .width-20 {
    width: 20px;
  }

  .width-component {
    width: 64px;
  }

  .name-student {
    width: 70%;
    height: 24px;
    margin-left: 10px;
    text-align: left;
    font-weight: normal;
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

  @media print{
    .pageA4V{width:960px; height:1200px; margin:0 auto; font-size: 15px; }
    .pageA4H{width:1122px; height:810px; margin:0 auto; font-size: 15px;}
    .padding-5{
        padding: 5px 0 0 0;
    }

    #header-report ul#info, #header-report ul#addinfo {
      width:100%;
      margin: auto;
      display: block;
      text-align: center;
    }

    .margin-15{
        margin-top: 8px;
        margin-bottom: 7px;
    }

    /* #header-report{
        width:820px;
    } */

    /* #container-header {
        width: 425px !important;
    } */
    table, td, tr, th {
        border-color: black !important;
    }
    .report-table-empty td {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .blue-background {
      -webkit-print-color-adjust: exact;
      background-color: #C6D9F1 !important;
    }
    
    .background-black {
      -webkit-print-color-adjust: exact;
      background-color: #000 !important;
    }

    .bt-print {
      border-top: 1px solid black !important;
    }

    .br-print {
      border-right: 1px solid black !important;
    }

    .bl-print {
      border-left: 1px solid black !important;
    }

    .bln-print {
      border-left: none !important;
    }

    .boxSpecification {
      height: 91px;
    }

    .boxTotal {
      height: 92px;
    }

    .breakPage {
      page-break-after: always;
    }

    .size-cel {
      width: 66px !important;
    }
    
    .size-col-3 {
      width: 65px !important;
    }
    
    table { font-size: 95%; }
    
    .font-data-student {
      font-size: 90% !important;
    }

    .size-2 {
      font-size: 80%;
    }
    
    .head-table {
      font-size: 90% !important;
    }

    .mt-30 {
      margin-top: 15px;
    }

    #boxDate {
      margin-top: 30px;
      margin-bottom: 25px;
    }

    .head-table td, .head-table th {
      padding: 3px !important;
    } 
  }
</style>
