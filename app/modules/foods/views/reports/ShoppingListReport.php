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
        <table aria-describedby="page" class="column table table-bordered">
            <thead  class="background-dark font-bold">
            </thead>
            <tbody>
                <tr>
                    <td>
                        Alimento
                    </td>
                    <td>
                        Unidade para compra
                        (Kg/ L)
                    </td>
                    <td>
                        Custo Unitário (R$)
                    </td>
                    <td>
                        PB (g)
                        (1 aluno)
                    </td>
                    <td class="background-dark">
                        Custo total (R$)
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="background-dark">
                        R$ 0,00
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="font-bold">
                        Total
                    </td>
                    <td class="background-dark">
                        R$ 0,00
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="font-bold">
                        Quantidade de alunos
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" class="font-bold">
                        Quantidade de vezes que a preparação foi servida no mês
                    </td>
                    <td></td>
                </tr>
                <tr class="background-dark">
                    <td colspan="4" class="font-bold">
                        Custo mensal (R$)
                    </td>
                    <td>
                        R$ 0,00
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    function imprimirPagina() {
        window.print();
    }
</script>