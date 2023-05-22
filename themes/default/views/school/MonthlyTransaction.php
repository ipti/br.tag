
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
 
  <div class='container-report mt-30 text-center'>
    <h3>Movimento Mensal</h3>
    <h5><?= $title ?></h5>
  </div>
  <div class='container-report mt-30'>
    <table class="table-border">
      <tr>
        <td colspan="4"><span class="font-bold">Nome da Escola: </span><div><?= $school->name ?></div></td>
      </tr>
      <tr>
        <td colspan="2"><span class="font-bold">Lagradouro</>:</span> <div><?= $school->address ?></div></td>
        <td><span class="font-bold">Nº:</span> <div><?= $school->address_number ?></div></td>
        <td><span class="font-bold">Bairro:</span> <div><?= $school->address_neighborhood ?></div></td>
      </tr>
      <tr>
        <td><span class="font-bold">Ano:</span> <div><?= date('Y') ?> </div></td>
        <td><span class="font-bold">Mês:</span><div class="contentEditable no-border" contenteditable="true"> </td>
        <td><span class="font-bold">Nº de Dias Letivos:</span> <div><?= $totalDays ?></div></td>
        <td><span class="font-bold">Turno:</span> <div class="contentEditable no-border" contenteditable="true"></div></td>
      </tr>
      <tr>
        <td>
          <span class="font-bold">Nº de dependências do prédio:  </span>
          <div class="contentEditable no-border" contenteditable="true"></div>
        </td>
        <td><span class="font-bold">Nº de salas de aula existentes no prédio: </span> <div><?= $schoolStrcture->classroom_count ?></div></td>
        <td colspan="2"><span class="font-bold">Nº de salas de aula utilizadas:</span><div><?= $schoolStrcture->used_classroom_count ?></div></td>
      </tr>
      <tr class="font-bold">
        <td class="no-padding">
          <table>
            <tr><td class="no-border-left no-border-top no-border-right text-center" colspan="2">Sala de Recurso Multifuncional:</td></tr>
            <tr>
              <td class="no-border"><span class="respClassrom cicle"></span>Sim</td>
              <td class="no-border"><span class="respClassrom cicle"></span>Não</td>
            </tr>
          </table>
        </td>
        <td class="no-padding" colspan="3">
          <table>
            <tr><td class="no-border-left no-border-top no-border-right text-center" colspan="3">Dependência administrativa do prédio</td></tr>
            <tr>
              <td class="no-border"><span class="cicle <?= $school->administrative_dependence == 2 ? 'background-black' : '' ?>"></span>Estadual</td>
              <td class="no-border"><span class="cicle <?= $school->administrative_dependence == 3 ? 'background-black' : '' ?>"></span>Municipal</td>
              <td class="no-border"><span class="cicle <?= $school->administrative_dependence == 4 ? 'background-black' : '' ?>"></span>Particular</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
  
  <div class='container-report mt-20'>
    <?php switch($type){ 
        case 1:
          $this->renderPartial('_initial_year', array());
        break;
        case 2:
          $this->renderPartial('_end_year', array());
        break;
        case 3:
          $this->renderPartial('_child_education', array());
        break;
      }
    ?>
  </div>

  <div id="box2" class='container-report mt-20'>
    <table class="table-border">
      <tr class="blue-background">
        <th>Nº</th>
        <th class="">PESSOAL DOCENTE EM EXERCÍCIO EM SALA DE AULA</th>
        <th>VÍNCULO</th>
        <th>COMPONENTES CURRICULARES/EIXOS LECIONADOS</th>
        <th>ANO</th>
        <th>TURMA</th>
        <th>C.H</th>
      </tr>
      <? for($i=1; $i <= 20; $i++): ?>
        <tr>
          <td class="font-bold text-center"><?= $i ?></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
        </tr>
      <? endfor; ?>
    </table>
  </div>

  <div class='container-report mt-20'>
    <table class="table-border">
      <tr class="blue-background">
        <th colspan="7">PESSOAL ADMINISTRATIVO E DOCENTE COM DESVIO DE FUNÇÃO</th>
      </tr>
      <tr>
        <th>Nº</th>
        <th class="">NOME DO(A) SERVIDOR(A)</th>
        <th>CARGO</th>
        <th>FUNÇÃO</th>
        <th>ANO</th>
        <th>VÍNCULO</th>
        <th>C.H</th>
      </tr>
      <? for($i=1; $i <= 15; $i++): ?>
        <tr>
          <td class="font-bold text-center"><?= $i ?></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
        </tr>
      <? endfor; ?>
    </table>
  </div>

  <div id="box3" class='container-report mt-20 mb-30'>
    <table class="table-border">
      <tr class="blue-background">
        <th colspan="6">PESSOAL LICENCIADO / FÉRIAS</th>
      </tr>
      <tr>
        <th>Nº</th>
        <th class="">NOME DO(A) SERVIDOR(A)</th>
        <th>CARGO</th>
        <th>FUNÇÃO</th>
        <th width="100" class="no-padding" colspan="2">
          <table>
            <tr><th class="no-border-top no-border-left no-border-right" colspan="2">LICENÇA</th></tr>
            <tr>
              <th class="width-50 no-border-left no-border-bottom">TIPO</th>
              <th class="width-50 no-border-left no-border-bottom">PERÍODO</th>
            </tr>
          </table>
        </th>
      </tr>
      <? for($i=1; $i <= 5; $i++): ?>
        <tr>
          <td class="font-bold text-center"><?= $i ?></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td width="100"><div class="contentEditable no-border" contenteditable="true"></div></td>
          <td width="100"><div class="contentEditable no-border" contenteditable="true"></div></td>
        </tr>
      <? endfor; ?>
    </table>
  </div>
  <div id="boxDate"  class="row-grid no-border">
    <div class="col no-border">
      <span class="pull-right">
        <?= $school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
      </span>
    </div>
  </div>
  <div class='container-report mt-40 mb-30 text-center'>
    <div class="row-grid no-border">
      <div class="col no-border">
        <div class="mr-10 no-border-left no-border-right no-border-bottom">Responsável pelo recebimento</div>
      </div>
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
   
  .mr-10 {
    margin-right: 10px;
  }

  .ml-10 {
    margin-left: 10px;
  } 

  .width-50 {
    width: 50%;
  }

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
    margin-top: 100px;
    margin-bottom: 100px;
  }
  h3,h4,h5 {
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

  .width-1 {
    width: 27px
  }

  .width-2 {
    width: 57px !important;
  }

  .height-1 {
    height: 50px !important;
  }

  .no-padding-right {
    padding-right: 0px !important;
  }

  .no-padding-left {
    padding-left: 0px !important;
  }

  .width-3 {
    width: 69px;
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

    table, #box1 { font-size: 80%; }
  }
</style>

<script>
  $(function(){
    $('.respClassrom').click(function(){
      $('.respClassrom').removeClass('background-black');
      $(this).addClass('background-black');
    });
  });
</script>
