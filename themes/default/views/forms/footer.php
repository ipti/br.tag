<!--<p class="info-issued">Gerado pelo Sistema de Informação TAG em: <?php echo date('d/m/Y'); ?></p>-->

<?php
if($_GET['cod'])
    $cod = $_GET['cod'];



$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
$inep_School = $school->inep_id;
$cod = $inep_School . date('ymdhi');

?>
<?php
$div1 = 0;
$div2 = 0;
$cont = 30;
$div1 = substr($cod,-1)*2+9;
echo "<div style='font-size:9px;line-height: 9px;text-align: center;' id='rodape'>";
$cod= $cod."-".$div1;
echo "<br>";
echo "<p>Documento autenticado digitalmente pelo sistema de informação TAG em: ". date('d/m/Y - H:i')."h e deve ser conferido na Internet no 
 endereço http://verificador.tag.ong.br</p>Código de Verificação: $cod";
echo "</div>";

?>