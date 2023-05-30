<?php
/** @var DefaultController $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);

?>
<div class="main">
<h1>Diário de Classe</h1>
<div class="classrooms">

</div>
<div class="row wrap">
 <?php 
 foreach ($classrooms as $class) {
	$stage = EdcensoStageVsModality::model()->findByPk($class->edcensoStageVsModalityFk)->name;
	echo '<div class="column">
			<a href="#" class="t-cards">
				<div class="t-cards-title">'.$class->name.'</div>
				<div class="t-cards-text clear-margin--left">'.$stage.'</div>
			</a>
		</div>';
};
?>


<?php 
	

?>
</div>
</div>