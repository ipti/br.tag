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
 foreach ($classrooms as $c) {
	echo '<div class="column">
			<a href="#" class="t-cards">
				<div class="t-tag-primary">'.$c["discipline_name"].'</div>
				<div class="t-cards-title">'.$c["name"].'</div>
				<div class="t-cards-text clear-margin--left">'.$c["stage_name"].'</div>
			</a>
		</div>';
	};
?>
</div>
</div>