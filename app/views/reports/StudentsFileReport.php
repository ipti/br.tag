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

<div class="row-fluid hidden-print">
    <div class="span12">
        <h3 class="heading-mosaic hidden-print"><?php echo Yii::t('default', 'Ficha Individual do Aluno'); ?></h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div><?php
        echo CHtml::dropDownList('student', null, chtml::listData(StudentIdentification::model()->findAll(), 'id', 'name'), array(
            'class' => 'select-search-on hidden-print',
            'prompt' => 'Selecione um Aluno',
            'ajax' => array(
                'type' => 'GET',
                'data' => array('student_id' => 'js:this.value'),
                'url' => CController::createUrl('reports/getStudentsFileInformation'),
                'success' => "function(data){ gerarRelatorio(data); }",
                'error' => "function(){ limpar(); }"
        )));
        ?>
        <br>
        <div id="report">
            <?php  $this->renderPartial('head');?>
            <table id="report-table" class="table table-bordered table-striped">
                <tr><th>Nome:</th><td colspan="5" id="name"></td></tr>
                <tr><th>Cidade&nbsp;de&nbsp;Nascimento:</th><td colspan="2" id="birth_city"></td><th>Estado&nbsp;de&nbsp;Nascimento:</th><td colspan="2" id="birth_uf"></td></tr>
                <tr><th>Nacionalidade:</th><td colspan="2" id="nation"></td><th>Data&nbsp;de&nbsp;Nascimento:</th><td colspan="2" id="birthday"></td></tr>

                <tr><th>Endereço:</th><td  colspan="5" id="address"></td></tr>
                <tr><th>Cidade:</th><td id="address_city"></td><th>Estado:</th><td id="address_uf"></td><th>CEP:</th><td id="cep"></td></tr>
                <tr><th>RG:</th><td colspan="5" id="rg"></td></tr>
                <tr><th>Certidão&nbsp;de&nbsp;Nascimento:</th><td id="cc_number"></td><th>Livro:</th><td id="cc_book"></td><th>Folha:</th><td id="cc_sheet"></td></tr>
                <tr><th>Cidade:</th><td colspan="2" id="cc_city"></td><th>Estado:</th><td colspan="2" id="cc_uf"></td></tr>
                <tr><th>Pai:</th><td id="father"></td><th>Profissão:</th><td></td><th>Vivo:</th><td>[_]&nbsp;Sim&nbsp;-&nbsp;[_]&nbsp;Não</td></tr>
                <tr><th>Mãe:</th><td id="mother"></td><th>Profissão:</th><td></td><th>Vivo:</th><td>[_]&nbsp;Sim&nbsp;-&nbsp;[_]&nbsp;Não</td></tr>
                <tr><th>Responsável:</th><td></td><th>Profissão:</th><td></td><th>Vivo:</th><td>[_]&nbsp;Sim&nbsp;-&nbsp;[_]&nbsp;Não</td></tr>
                </tbody>
            </table>
            <br>
            <table class="table table-bordered table-striped" >
                <tr><th style="text-align: center">REQUERIMENTO DE MATRÍCULA</th></tr>
                <tr><td style="text-align: center">O(A) aluno(a) requer sua matrícula no ano correspondente, de acordo com a situação apresentada abaixo, a qual pede deferimento.</td></tr>
                </tbody>
            </table>
            <br>
            <table class="table table-bordered table-striped">
                <tr><th>Data</th><th>Ano</th><th>Assinatura&nbsp;do&nbsp;Requerente</th><th>Parecer do Responsável pela Unidade Executora</th></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                <tr><td>____/____/____</td><td>_______</td><td>_____________________________________</td><td class="td-status">[_]Deferido [_]Indeferido <span class="td-status-filereport">Ass.: __________________________________________________</span></td></tr>
                </tbody>
            </table>
            <?php $this->renderPartial('footer'); ?>
        </div>
    </div>
</div>
</div>
