<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/sass/css/main.css');
?>
<style>
    .background-dark {
        background-color: lightgray;
    }

    .font-bold {
        font-weight: bold;
    }

    @media print {
        #print {
            display: none;
        }
    }
</style>
<div class="row buttons">
    <a id="print" class="t-button-secondary" onclick="imprimirPagina()">
        <span class="t-icon-printer"></span>imprimir
    </a>
</div>
<div id="page" class="pageA4H">
    <div class="row t-margin-medium--top">
        
    </div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>
