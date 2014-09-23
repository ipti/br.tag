<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsFileReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));

$this->breadcrumbs = array(
    Yii::t('default', 'Reports') => array('/reports'),
    Yii::t('default', 'Students\'s File'),
);

$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>
<style>
    #report-logo{
        margin: auto auto 10px;
        width: 200px;
    }

</style>
<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Students\'s File'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div style="margin-top: 8px;">
        <div id="report-logo" class="visible-print">
            <img src="../../../images/sntaluzia.png">
        </div>
        <h3 class="heading visible-print"><?php echo Yii::t('default', 'Students\'s File'); ?></h3> 
        <?php
        echo CHtml::dropDownList('student', null, chtml::listData(StudentIdentification::model()->findAll(), 'id', 'name'), 
                array(
                    'class'=>"hidden-print",
                    'class' => 'select-search-on',
                    'prompt' => 'Selecione um Aluno',
                    'ajax' => array(
                        'type' => 'GET',
                        'data' => array('student_id' => 'js:this.value'),
                        'url' => CController::createUrl('reports/getStudentsFileInformation'),
                        'success' => "function(data){ gerarRelatorio(data); }",
                        'error' => "function(){ limpar(); }"
                )))
        ?>
        <div id="report">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Escola:</th><td colspan="7"><?php echo $school->inep_id . " - " . $school->name ?></td>
            <tr>
            <tr>
                <th>Estado:</th><td colspan="3"><?php echo $school->edcensoUfFk->name . " - " . $school->edcensoUfFk->acronym ?></td>
                <th>Municipio:</th><td colspan="3"><?php echo $school->edcensoCityFk->name ?></td>
            <tr>
            <tr>
                <th>Localização:</th><td colspan="3"><?php echo ($school->location == 1 ? "URBANA" : "RURAL") ?></td>
                <th>Dependência Administrativa:</th><td colspan="3"><?php
                    $ad = $school->administrative_dependence;
                    echo ($ad == 1 ? "FEDERAL" :
                            ($ad == 2 ? "ESTADUAL" :
                                ($ad == 3 ? "MUNICIPAL" :
                                    "PRIVADA" )));
                    ?></td>
            <tr>
        </table>
        <br>
        <table id="report-table" class="table table-bordered table-striped">
            <tr><th>Nome:</th><td colspan="5" id="name"></td></tr>
            <tr><th>Cidade de Nascimento:</th><td colspan="2" id="birth_city"></td><th>Estado de Nascimento:</th><td colspan="2" id="birth_uf"></td></tr>
            <tr><th>Nacionalidade:</th><td colspan="2" id="nation"></td><th>Data de Nascimento:</th><td colspan="2" id="birthday"></td></tr>
            
            <tr><th>Endereço:</th><td  colspan="5" id="address"></td></tr>
            <tr><th>Cidade:</th><td id="address_city"></td><th>Estado:</th><td id="address_uf"></td><th>CEP:</th><td id="cep"></td></tr>
            <tr><th>RG:</th><td colspan="5" id="rg"></td></tr>
            <tr><th>Certidão de Nascimento:</th><td id="cc_number"></td><th>Livro:</th><td id="cc_book"></td><th>Folha:</th><td id="cc_sheet"></td></tr>
            <tr><th>Cidade:</th><td colspan="2" id="cc_city"></td><th>Estado:</th><td colspan="2" id="cc_uf"></td></tr>
            <tr><th>Pai:</th><td id="father"></td><th>Profissão:</th><td></td><th>Vivo:</th><td>[_]Sim - [_]Não</td></tr>
            <tr><th>Mãe:</th><td id="mother"></td><th>Profissão:</th><td></td><th>Vivo:</th><td>[_]Sim - [_]Não</td></tr>
            <tr><th>Responsável:</th><td></td><th>Profissão:</th><td></td><th>Vivo:</th><td>[_]Sim - [_]Não</td></tr>
            </tbody>
        </table>
        <p>Emitido em <?php echo date('d/m/Y à\s h:i'); ?></p>
        <p class="visible-print">URL: <?php echo Yii::app()->getBaseUrl(true) . Yii::app()->request->requestUri ?></p>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        limpar();
    });
function gerarRelatorio(data){
    $("#report, #print").show();
    var infos = $.parseJSON(data);
    for(var i in infos){
        if(i!='id')
            $("#"+i).html(infos[i]);
        
        //falta a parte de baixo do relatório
    }

}

function limpar(){
    $("#report, #print").hide();
}

</script>