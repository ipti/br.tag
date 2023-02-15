<div id="mainPage" class="main" style="margin-top:40px; padding: 10px">
	<?php
	$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));
	?>
	<div class="span12">
		<h3>Sagres Edu</h3>
		</span>
	</div>
	<div class="separator"></div>
	<div class="separator"></div>
	<div class="separator"></div>
	<div class="row-fluid" style="padding-top: 15px;padding-bottom: 20px;">
		<div class="span3">
			<div>
				<h5>Data Inicial</h5>
				<input style="border-color: #e5e5e5;" type="date" name="data_inicio" placeholder="DD/MM/AAAA">
			</div>
		</div>
		<div class="span3">
			<div>
				<h5>Data Final</h5>
				<input style="border-color: #e5e5e5;" type="date" name="Data_final" placeholder="DD/MM/AAAA">
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<a href="?r=sagres/default/create" class="widget-stats">
				<div><i class="fa fa-building-o fa-4x"></i></div>
				<span class="txt">Cadastrar Unidade</span>
				<div class="clearfix"></div>
			</a>
		</div>

		<div class="span3">
			<a href="/?r=sagres/default/export&inep_id=28022122&yearSagresConsult=2022" class="widget-stats">
				<span class="glyphicons file_export"><i></i></span>
				<span class="txt">Exportar sagres</span>
				<div class="clearfix"></div>
			</a>
		</div>

		<div class="span3">
			<a href="?r=sagres/default/update&id=2" class="widget-stats">
				<div><i class="fa fa-edit fa-4x"></i></div>
				<span class="txt">Editar Unidade</span>
				<div class="clearfix"></div>
			</a>
		</div>
	</div>
</div>

<script>
	function downloadFile(url, filename) {
		$.get(url, function (data) {
			var blob = new Blob([data], { type: 'application/xml' });
			var url = URL.createObjectURL(blob);
			var link = document.createElement('a');
			link.href = url;
			link.download = filename;
			link.click();

			URL.revokeObjectURL(url);
		})
			.fail(function () {
				alert('Não foi possível fazer o download do arquivo.');
			});
	}

	$(document).ready(function () {
		var url = "/?r=sagres/default/export&inep_id=28022122&yearSagresConsult=2022";
		var filename = 'ExportSagres.xml';

		$('a[href="' + url + '"]').click(function (event) {
			event.preventDefault();
			downloadFile('/?r=sagres/default/export&inep_id=28022122&yearSagresConsult=2022', filename);
		});
	});

</script>