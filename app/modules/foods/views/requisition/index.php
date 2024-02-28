<?php
/* @var $this RequisitionController */

$this->breadcrumbs=array(
    $this->module->id,
);

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/predict/predictAcessURL.js', CClientScript::POS_END);

// Adicionando o link do script do Chart.js
$cs->registerScriptFile('https://cdn.jsdelivr.net/npm/chart.js', CClientScript::POS_END);
?>


<div class="tag-inner" style="margin-top:20px">
    <div class="main">
        <div class="js-predict">
            <table id="example" class="js-tag-table tag-table-primary table table-condensed
                            table-striped table-hover table-primary table-vertical-center checkboxs" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">Alimento</th>
                        <th class="text-center">Código</th>
                        <th class="text-center">Data de reposição</th>
                        <th class="text-center">Quantidade</th>
                        <th class="text-center">Data de fim de estoque</th>
                        <th class="text-center">Gráfico de previsão</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>   
    </div>

    <!-- Modal -->
    <div class="modal fade modal-content" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Gráfico de Previsão</h4>
                </div>
                <div class="modal-body">
                    <canvas id="prediction_chart" style="width: 100%; height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Dados de comportamento do consumo do item ao longo do tempo
    <h3>Dados de comportamento do consumo do item ao longo do tempo</h3>
    <div id="secondRequestData"></div>

  Dados saída do item 
   <h3>Dados saída do item</h3>
    <div id="TerciaryRequestData"></div> -->
</div>