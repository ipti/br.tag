<?php 

?>
<style>
    th, td {
        text-align: center !important;
        vertical-align: middle !important;
    }
    /* Landscape orientation */
    @page{
        size: landscape;
    }
    /* Hidden the print button */
    @media print {
        #print {
            display: none;
    }
}
</style>
<h1>aaaaaa</h1>
<script>
    function imprimirPagina() {
        // printButton = document.getElementsByClassName('span12');
        // printButton.style.visibility = 'hidden';
        window.print();
        // printButton.style.visibility = 'visible';
    }
</script>