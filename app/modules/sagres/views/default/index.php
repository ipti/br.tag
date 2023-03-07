<?php
	$this->setPageTitle('TAG - ' . Yii::t('default', 'Sagres'));
?>

<div id="mainPage" class="main">
	<div class="row">
		<div class="column">
			<h1>Sagres Edu</h1>
		</div>
	</div>
	<div class="row">
		<div class="column no-grow">
			<div>
				<h5>Data Inicial</h5>
				<input id="data-inicio" style="border-color: #e5e5e5;" type="date" name="data_inicio"
					placeholder="DD/MM/AAAA">
			</div>
		</div>
		<div class="column no-grow">
			<div>
				<h5>Data Final</h5>
				<input id="data-final" style="border-color: #e5e5e5;" type="date" name="Data_final"
					placeholder="DD/MM/AAAA">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="column">
			<a href="?r=sagres/default/create" class="widget-stats">
				<div><i class="fa fa-building-o fa-4x"></i></div>
				<span class="txt">Cadastrar Unidade</span>
				<div class="clearfix"></div>
			</a>
		</div>

		<div class="column">
			<a href="?r=sagres/default/export" id="exportLink" class="widget-stats">
				<span class="glyphicons file_export"><i></i></span>
				<span class="txt">Exportar sagres</span>
				<div class="clearfix"></div>
			</a>
		</div>
		<div class="column">
			<a href="<?php echo Yii::app()->createUrl('sagres/default/update', array('id' => 1)) ?>"
				href="?r=sagres/default/update&id=2" class="widget-stats">
				<div><i class="fa fa-edit fa-4x"></i></div>
				<span class="txt">Editar Unidade</span>
				<div class="clearfix"></div>
			</a>
		</div>
	</div>
</div>

<script>
	// Selecione os elementos de input de data
	const dataInicioInput = document.querySelector('input[name="data_inicio"]');
	const dataFinalInput = document.querySelector('input[name="Data_final"]');

	// Adicione um ouvinte de eventos para o evento de clique no link
	document.getElementById('exportLink').addEventListener('click', function (e) {
		// Previna o comportamento padrão do link
		e.preventDefault();

		// Obtenha o valor das datas de início e fim
		const dataInicio = dataInicioInput.value;
		const dataFinal = dataFinalInput.value;

		// Obtenha o ano de início
		const data = new Date(dataInicio);
		data.setUTCHours(0, 0, 0, 0); // Define as horas, minutos, segundos e milissegundos como zero
		const yearInicio = data.getUTCFullYear();

		// Atualize o atributo "href" com as datas selecionadas
		const exportLink = document.getElementById('exportLink');
		const newHref = `?r=sagres/default/export&year=${yearInicio}&data_inicio=${dataInicio}&data_final=${dataFinal}`;
		exportLink.setAttribute('href', newHref);

		// Chame a função downloadFile para gerar e baixar o arquivo
		downloadFile(newHref, 'Educacao.xml');
	});

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
				alert('Erro ao realizar o download do arquivo');
			});
	}

</script>